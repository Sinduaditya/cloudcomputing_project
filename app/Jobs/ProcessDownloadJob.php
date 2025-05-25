<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Jobs\ProcessDownloadJob.php
namespace App\Jobs;

use App\Models\Download;
use App\Models\ActivityLog;
use App\Services\DownloadService;
use App\Services\TokenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $download;
    public $tries = 2;  // Number of times to attempt
    public $timeout = 600; // 10 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(Download $download)
    {
        $this->download = $download;
    }

    /**
     * Execute the job.
     */
    public function handle(DownloadService $downloadService, TokenService $tokenService)
    {
        Log::info('Starting download processing job', ['download_id' => $this->download->id]);

        try {
            // Update download status to processing
            $this->download->status = 'processing';
            $this->download->save();

            // Process the download
            $result = $downloadService->processDownload($this->download);

            // Make sure download is complete
            $this->ensureCompleted();

            try {
                // Log activity
                ActivityLog::create([
                    'user_id' => $this->download->user_id,
                    'action' => 'download_completed',
                    'resource_id' => $this->download->id,
                    'resource_type' => 'Download',
                    'details' => json_encode([
                        'platform' => $this->download->platform,
                        'format' => $this->download->format,
                        'file_size' => $this->download->file_size,
                        'url' => $this->download->storage_url ?? '',
                    ]),
                    'ip_address' => request()->ip() ?? '0.0.0.0',
                ]);
            } catch (Exception $e) {
                // Don't let activity logging fail the job
                Log::error('Failed to create activity log for completed download', [
                    'download_id' => $this->download->id,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('Download processed successfully', [
                'download_id' => $this->download->id,
                'url' => $result['url'] ?? $this->download->storage_url ?? 'URL not available'
            ]);

        } catch (Exception $e) {
            Log::error('Download job failed', [
                'download_id' => $this->download->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Check if file exists despite the error
            $this->tryToRecoverFromError();

            // If still not completed, mark as failed
            if ($this->download->status !== 'completed') {
                $this->download->status = 'failed';
                $this->download->error_message = $e->getMessage();
                $this->download->save();

                try {
                    // Refund tokens to user
                    $user = $this->download->user;
                    $tokenService->refundTokens(
                        $user,
                        $this->download->token_cost,
                        'Refund for failed download: ' . $this->download->title,
                        $this->download->id
                    );
                } catch (Exception $e) {
                    // Don't let token refund fail the job
                    Log::error('Failed to refund tokens', [
                        'download_id' => $this->download->id,
                        'error' => $e->getMessage()
                    ]);
                }

                try {
                    // Log activity
                    ActivityLog::create([
                        'user_id' => $this->download->user_id,
                        'action' => 'download_failed',
                        'resource_id' => $this->download->id,
                        'resource_type' => 'Download',
                        'details' => json_encode([
                            'error' => $e->getMessage(),
                        ]),
                        'ip_address' => request()->ip() ?? '0.0.0.0',
                    ]);
                } catch (Exception $e) {
                    // Don't let activity logging fail the job
                    Log::error('Failed to create activity log for failed download', [
                        'download_id' => $this->download->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    /**
     * Try to recover from error if file exists
     */
    private function tryToRecoverFromError()
    {
        // Reload from database to get latest status
        $freshDownload = Download::find($this->download->id);
        $this->download = $freshDownload ?: $this->download;

        if (!empty($this->download->storage_url)) {
            Log::info('Found storage URL despite error, marking as completed', [
                'download_id' => $this->download->id
            ]);

            $this->download->status = 'completed';
            $this->download->completed_at = now();
            $this->download->save();
        }
    }

    /**
     * Ensure download is marked as completed if it has a storage URL
     */
    private function ensureCompleted()
    {
        // Reload from database to get latest status
        $freshDownload = Download::find($this->download->id);
        $this->download = $freshDownload ?: $this->download;

        if ($this->download->status !== 'completed' && !empty($this->download->storage_url)) {
            Log::info('Setting download status to completed', [
                'download_id' => $this->download->id
            ]);

            $this->download->status = 'completed';
            $this->download->completed_at = now();
            $this->download->save();
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception)
    {
        Log::error('Download job failed in failed handler', [
            'download_id' => $this->download->id,
            'error' => $exception->getMessage()
        ]);

        // Try to recover from error one last time
        $this->tryToRecoverFromError();

        // Only if we still don't have a completed status, mark as failed
        if ($this->download->status !== 'completed') {
            $this->download->status = 'failed';
            $this->download->error_message = $exception->getMessage();
            $this->download->save();
        }
    }
}
