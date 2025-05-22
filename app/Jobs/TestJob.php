<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Jobs\TestJob.php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Log::info('Test job processed successfully via Redis queue!');

        // Simulate processing
        sleep(2);

        Log::info('Test job completed.');
    }
}
