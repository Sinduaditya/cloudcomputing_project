<?php

namespace App\Console\Commands;

use App\Models\ScheduledTask;
use App\Jobs\ProcessScheduledDownloadJob;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessScheduledDownloads extends Command
{
    protected $signature = 'downloads:process-scheduled';
    protected $description = 'Process scheduled downloads that are due';

    public function handle()
    {
        $now = Carbon::now();

        // Find all scheduled tasks that are due (past their scheduled time)
        $dueTasks = ScheduledTask::where('status', ScheduledTask::STATUS_SCHEDULED)
            ->where('scheduled_for', '<=', $now)
            ->get();

        $this->info("Found {$dueTasks->count()} scheduled downloads due for processing");

        foreach ($dueTasks as $task) {
            try {
                $this->info("Processing scheduled task ID: {$task->id} - {$task->url}");

                // Update status to prevent duplicate processing
                $task->status = 'processing';
                $task->save();

                // Dispatch the job
                ProcessScheduledDownloadJob::dispatch($task)->onQueue('scheduled');

                Log::info("Dispatched scheduled download job", [
                    'task_id' => $task->id,
                    'scheduled_for' => $task->scheduled_for,
                    'url' => $task->url
                ]);

            } catch (\Exception $e) {
                $this->error("Failed to process task {$task->id}: " . $e->getMessage());
                Log::error("Failed to dispatch scheduled download job", [
                    'task_id' => $task->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info('Scheduled downloads processing completed');
    }
}
