<?php

namespace App\Console\Commands;

use App\Jobs\ProcessScheduledDownloadJob;
use App\Models\ScheduledTask;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledDownloads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downloads:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all scheduled downloads that are due';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Looking for due scheduled tasks...');

        // Find tasks that are scheduled and due
        $dueTasks = ScheduledTask::where('status', 'scheduled')
            ->where('scheduled_for', '<=', now())
            ->get();

        $count = $dueTasks->count();

        if ($count === 0) {
            $this->info('No scheduled tasks due for processing.');
            return 0;
        }

        $this->info("Found {$count} scheduled tasks to process.");

        foreach ($dueTasks as $task) {
            try {
                $this->info("Processing task #{$task->id} for URL: {$task->url}");

                // Update status to processing
                $task->status = 'processing';
                $task->save();

                // Dispatch job to process the task
                ProcessScheduledDownloadJob::dispatch($task);

                Log::info("Dispatched job for scheduled task", [
                    'task_id' => $task->id,
                    'user_id' => $task->user_id,
                    'url' => $task->url
                ]);
            } catch (\Exception $e) {
                $this->error("Error dispatching task #{$task->id}: {$e->getMessage()}");

                // Mark task as failed
                $task->status = 'failed';
                $task->error_message = "Error dispatching job: {$e->getMessage()}";
                $task->save();

                Log::error("Error dispatching scheduled task job", [
                    'task_id' => $task->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info('Finished processing scheduled tasks.');
        return 0;
    }
}
