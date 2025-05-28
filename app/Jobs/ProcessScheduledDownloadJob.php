<?php

namespace App\Jobs;

use App\Models\ScheduledTask;
use App\Models\Download;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\DownloadService;
use App\Services\TokenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;
use Illuminate\Support\Facades\Log;

class ProcessScheduledDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $scheduledTask;

    public $tries = 2;
    public $timeout = 600; // Increase timeout to 10 minutes

    public function __construct(ScheduledTask $scheduledTask)
    {
        $this->scheduledTask = $scheduledTask;
        $this->onQueue('scheduled');
    }

    public function handle(DownloadService $downloadService, TokenService $tokenService)
    {
        // Reload the task from database to get fresh data
        $task = ScheduledTask::find($this->scheduledTask->id);

        if (!$task) {
            Log::error("Scheduled task not found: {$this->scheduledTask->id}");
            return;
        }

        // Skip if already processed
        if (!in_array($task->status, ['scheduled', 'processing'])) {
            Log::info("Task {$task->id} already processed with status: {$task->status}");
            return;
        }

        // Update status to processing
        $task->status = 'processing';
        $task->save();

        $user = User::find($task->user_id);

        if (!$user) {
            Log::error("User not found for scheduled task #{$task->id}");
            $this->markTaskAsFailed($task, 'User not found');
            return;
        }

        try {
            Log::info("Processing scheduled download job for task #{$task->id}", [
                'url' => $task->url,
                'platform' => $task->platform,
                'format' => $task->format,
                'quality' => $task->quality ?? '720p',
                'user_id' => $user->id
            ]);

            // Get video metadata
            $metadata = $downloadService->analyze($task->url);

            // Calculate token cost
            $tokenCost = $downloadService->calculateTokenCost(
                $task->platform,
                $metadata['duration'] ?? 0,
                $task->format,
                $task->quality ?? '720p'
            );

            // Check token balance
            if ($user->token_balance < $tokenCost) {
                $errorMessage = "Saldo token tidak mencukupi. Dibutuhkan {$tokenCost} token, tersedia {$user->token_balance} token.";

                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'scheduled_download_failed',
                    'resource_type' => 'ScheduledTask',
                    'resource_id' => $task->id,
                    'details' => json_encode([
                        'reason' => 'Insufficient tokens',
                        'required' => $tokenCost,
                        'available' => $user->token_balance,
                    ]),
                ]);

                $this->markTaskAsFailed($task, $errorMessage);
                return;
            }

            // Create download record
            $download = Download::create([
                'user_id' => $user->id,
                'platform' => $task->platform,
                'url' => $task->url,
                'title' => $metadata['title'] ?? 'Scheduled Download',
                'format' => $task->format,
                'quality' => $task->quality ?? '720p',
                'token_cost' => $tokenCost,
                'duration' => $metadata['duration'] ?? 0,
                'status' => 'pending',
            ]);

            Log::info("Created download record for task #{$task->id}", ['download_id' => $download->id]);

            // Deduct tokens
            $tokenService->deductTokens(
                $user,
                $tokenCost,
                'download_cost',
                'Scheduled Download: ' . ($metadata['title'] ?? 'Video'),
                $download->id
            );

            // Update task with download_id
            $task->download_id = $download->id;
            $task->save();

            // Process the download
            try {
                $result = $downloadService->processDownload($download);

                // Reload download to get updated status
                $download = Download::find($download->id);

                if ($download->status === 'completed') {
                    // Mark task as completed
                    $task->status = 'completed';
                    $task->completed_at = now();
                    $task->save();

                    Log::info("Scheduled download completed successfully for task #{$task->id}", [
                        'download_id' => $download->id,
                        'download_status' => $download->status
                    ]);

                    // Log activity
                    ActivityLog::create([
                        'user_id' => $user->id,
                        'action' => 'scheduled_download_completed',
                        'resource_type' => 'ScheduledTask',
                        'resource_id' => $task->id,
                        'details' => json_encode([
                            'download_id' => $download->id,
                            'title' => $download->title,
                            'tokens_used' => $tokenCost,
                        ]),
                    ]);
                } else {
                    throw new Exception("Download did not complete successfully. Status: {$download->status}");
                }

            } catch (Exception $e) {
                Log::error('Scheduled download processing failed', [
                    'task_id' => $task->id,
                    'download_id' => $download->id ?? null,
                    'error' => $e->getMessage(),
                ]);

                // Mark download as failed if it exists
                if (isset($download)) {
                    $download->status = 'failed';
                    $download->error_message = $e->getMessage();
                    $download->save();
                }

                // Refund tokens
                $tokenService->refundTokens(
                    $user,
                    $tokenCost,
                    'Refund for failed scheduled download: ' . ($metadata['title'] ?? 'Video'),
                    $download->id ?? null
                );

                $this->markTaskAsFailed($task, 'Download failed: ' . $e->getMessage());
            }

        } catch (Exception $e) {
            Log::error('Scheduled task processing error', [
                'task_id' => $task->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->markTaskAsFailed($task, 'Processing error: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to mark task as failed
     */
    private function markTaskAsFailed(ScheduledTask $task, $reason)
    {
        $task->status = 'failed';
        $task->error_message = $reason;
        $task->failed_at = now();
        $task->save();

        Log::error("Marked scheduled task #{$task->id} as failed: {$reason}");
    }

    /**
     * Handle job failure
     */
    public function failed(Exception $exception)
    {
        $task = ScheduledTask::find($this->scheduledTask->id);
        if ($task) {
            $this->markTaskAsFailed($task, 'Job failed: ' . $exception->getMessage());
        }
    }
}
