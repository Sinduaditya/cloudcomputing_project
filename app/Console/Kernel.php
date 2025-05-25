<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ProcessScheduledDownloads::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Run the scheduler every minute to check for due downloads
        $schedule->command('downloads:process-scheduled')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->onOneServer()
                 ->appendOutputTo(storage_path('logs/scheduler.log'));

        // Clean up old temporary files daily
        $schedule->exec('find ' . storage_path('app/downloads/temp') . ' -type f -mtime +1 -delete')
                 ->daily()
                 ->onOneServer();

        // Prune failed jobs weekly
        $schedule->command('queue:prune-failed --hours=168') // 7 days
                 ->weekly()
                 ->onOneServer();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
