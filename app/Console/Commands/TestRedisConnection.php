<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class TestRedisConnection extends Command
{
    protected $signature = 'redis:test';
    protected $description = 'Test Redis connection to Upstash';

    public function handle()
    {
        $this->info("Testing Redis connection...");

        // Display configuration
        $this->info("Redis Client: " . config('database.redis.client'));
        $this->info("Redis Host: " . config('database.redis.default.host'));
        $this->info("Redis Scheme: " . config('database.redis.default.scheme', 'Not set'));
        $this->info("Redis SSL: " . (config('database.redis.default.ssl') ? 'Yes' : 'No'));

        try {
            $result = Redis::ping();
            $this->info("Connection successful! Response: " . $result);

            // Test basic operations
            Redis::set('test_key', 'Testing Upstash: ' . now());
            $value = Redis::get('test_key');
            $this->info("Test value retrieved: " . $value);

            return 0;
        } catch (\Exception $e) {
            $this->error("Connection failed: " . $e->getMessage());
            $this->error("Check your .env file for conflicting Redis configurations");

            return 1;
        }
    }
}
