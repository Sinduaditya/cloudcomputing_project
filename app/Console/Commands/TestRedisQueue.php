<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Console\Commands\TestRedisQueue.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Jobs\TestJob;

class TestRedisQueue extends Command
{
    protected $signature = 'redis:test-queue';
    protected $description = 'Test Redis queue functionality';

    public function handle()
    {
        try {
            // Test basic Redis connection
            Redis::set('test_key', 'Connection working!');
            $value = Redis::get('test_key');

            $this->info("Redis connection successful: $value");
            Redis::del('test_key');

            // Test Redis queue functionality
            $this->info('Dispatching test job to Redis queue...');
            TestJob::dispatch()->onQueue('default');

            $this->info('Test job dispatched. Check your queue worker logs to confirm processing.');

            return 0;
        } catch (\Exception $e) {
            $this->error("Redis test failed: " . $e->getMessage());

            return 1;
        }
    }
}
