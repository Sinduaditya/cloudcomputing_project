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
            // Process the download
            $result = $downloadService->processDownload($this->download);

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
                    'url' => $this->download->storage_url,
                ]),
                'ip_address' => request()->ip() ?? '0.0.0.0',
            ]);

            Log::info('Download processed successfully', [
                'download_id' => $this->download->id,
                'url' => $result['url']
            ]);
        } catch (Exception $e) {
            Log::error('Download job failed', [
                'download_id' => $this->download->id,
                'error' => $e->getMessage()
            ]);

            // Update download status
            $this->download->status = 'failed';
            $this->download->error_message = $e->getMessage();
            $this->download->save();

            // Refund tokens to user
            $user = $this->download->user;
            $tokenService->refundTokens(
                $user,
                $this->download->token_cost,
                'Refund for failed download: ' . $this->download->title,
                $this->download->id
            );

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

            throw $e;
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
    }
}
