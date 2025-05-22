<?php

namespace App\Console\Commands;

use App\Jobs\ProcessScheduledDownloadJob;
use App\Models\ScheduledTask;
use App\Services\DownloadService;
use App\Services\TokenService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledDownloads extends Command
{
    protected $signature = 'downloads:process-scheduled';
    protected $description = 'Process scheduled downloads that are due';

    protected $downloadService;
    protected $tokenService;

    public function __construct(DownloadService $downloadService, TokenService $tokenService)
    {
        parent::__construct();
        $this->downloadService = $downloadService;
        $this->tokenService = $tokenService;
    }

    public function handle()
    {
        $now = now();
        $this->info("Checking for scheduled downloads due at {$now}");

        try {
            // Reset stuck tasks (processing for more than 30 minutes)
            $stuckTasks = ScheduledTask::where('status', 'processing')
                ->where('updated_at', '<', $now->subMinutes(30))
                ->get();

            if ($stuckTasks->count() > 0) {
                $this->info("Found {$stuckTasks->count()} stuck tasks to reset");
                foreach ($stuckTasks as $task) {
                    $this->warn("Resetting stuck task #{$task->id} (processing since {$task->updated_at})");
                    $task->status = 'scheduled';
                    $task->error_message = 'Reset after being stuck in processing state';
                    $task->save();
                }
            }

            // Get scheduled tasks due for processing
            $scheduledTasks = ScheduledTask::where('status', 'scheduled')
                ->where('scheduled_for', '<=', $now)
                ->get();

            $count = $scheduledTasks->count();
            $this->info("Found {$count} scheduled download(s) to process");

            if ($count === 0) {
                return 0;
            }

            foreach ($scheduledTasks as $task) {
                $this->info("Processing scheduled task #{$task->id}");

                try {
                    // Mark as processing to prevent duplicate processing
                    $task->status = 'processing';
                    $task->save();

                    // Dispatch job to database queue
                    ProcessScheduledDownloadJob::dispatch($task);
                    $this->info("Dispatched task #{$task->id} to database queue");

                } catch (\Exception $e) {
                    $this->error("Error dispatching task #{$task->id}: " . $e->getMessage());

                    // Mark as failed
                    $task->status = 'failed';
                    $task->error_message = "Error: " . $e->getMessage();
                    $task->save();

                    Log::error("Failed to dispatch scheduled task #{$task->id}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Command error: " . $e->getMessage());
            Log::error("Scheduled downloads processing error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return 1;
        }
    }
}
