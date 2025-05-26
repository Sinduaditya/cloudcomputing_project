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
// Hapus import Redis
// use Illuminate\Support\Facades\Redis;

class ProcessScheduledDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $scheduledTask;

    public $tries = 2;
    public $timeout = 300;

    public function __construct(ScheduledTask $scheduledTask)
    {
        $this->scheduledTask = $scheduledTask;
        $this->onQueue('scheduled');
    }

    public function handle(DownloadService $downloadService, TokenService $tokenService)
    {
        if ($this->task->status !== 'scheduled') {
            return;
        }

        $this->task->update(['status' => 'running']);

        $task = $this->scheduledTask;
        $user = User::find($task->user_id);

        if (!$user) {
            Log::error("User not found for scheduled task #{$task->id}");
            $this->markTaskAsFailed('User not found');
            return;
        }

        $platform = $task->platform;
        $url = $task->url;

        try {
            Log::info("Processing scheduled download job for task #{$task->id}", [
                'url' => $url,
                'platform' => $platform,
                'format' => $task->format,
                'quality' => $task->quality ?? '720p'
            ]);

            // Get video metadata
            $metadata = $downloadService->getVideoMetadata($url, $platform);

            // Calculate token cost
            $tokenCost = $downloadService->calculateTokenCost($platform, $metadata['duration'] ?? 0, $task->format, $task->quality ?? '720p');

            // Check token balance
            if ($user->token_balance < $tokenCost) {
                ActivityLog::create([
                    'user_id' => $user->id,
                    'resource_type' => 'ScheduledTask',
                    'resource_id' => $task->id,
                    'details' => json_encode([
                        'reason' => 'Insufficient tokens',
                        'required' => $tokenCost,
                        'available' => $user->token_balance,
                    ]),
                ]);

                $this->markTaskAsFailed("Saldo token tidak mencukupi. Dibutuhkan {$tokenCost} token.");
                return;
            }

            // Create download record
            $download = Download::create([
                'user_id' => $user->id,
                'platform' => $platform,
                'url' => $task->url,
                'title' => $metadata['title'] ?? 'Scheduled Download',
                'format' => $task->format,
                'quality' => $task->quality,
                'token_cost' => $tokenCost,
                'duration' => $metadata['duration'] ?? 0,
                'status' => 'pending',
            ]);

            Log::info("Created download record for task #{$task->id}", ['download_id' => $download->id]);

            // Deduct tokens
            $tokenService->deductTokens($user, $tokenCost, 'download_cost');

            // Process the download
            try {
                // Proses download disini
                $result = $downloadService->processDownload($download);

                // Update download record
                $download = Download::find($download->id); // Refresh dari database
                $download->status = 'completed';
                $download->save();

                // Update scheduled task with download_id and mark as completed
                $task->download_id = $download->id;
                $task->status = 'completed';
                $task->save();

                // Log success
                Log::info("Scheduled download completed for task #{$task->id}", [
                    'download_id' => $download->id,
                    'url' => $task->url,
                    'task_status' => $task->status
                ]);
            } catch (\Exception $e) {
                $download->status = 'failed';
                $download->error_message = $e->getMessage();
                $download->save();

                $this->markTaskAsFailed('Download failed: ' . $e->getMessage());
                Log::error('Scheduled download failed', [
                    'task_id' => $task->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        } catch (\Exception $e) {
            $this->markTaskAsFailed('Error: ' . $e->getMessage());
            Log::error('Scheduled task processing error', [
                'task_id' => $task->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
        $this->task->update(['status' => 'completed']);
    }

    /**
     * Helper method to mark task as failed
     */
    private function markTaskAsFailed($reason)
    {
        $this->scheduledTask->status = 'failed';
        $this->scheduledTask->error_message = $reason;
        $this->scheduledTask->save();
    }
}
