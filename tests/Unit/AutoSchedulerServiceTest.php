<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\AutoSchedulerService;

class AutoSchedulerServiceTest extends TestCase
{
    public function test_start_when_high_load()
    {
        $service = new AutoSchedulerService();
        $this->assertEquals('start', $service->getAction(3, 90));
    }

    public function test_terminate_when_very_low_load()
    {
        $service = new AutoSchedulerService();
        $this->assertEquals('terminate', $service->getAction(3, 5));
    }

    public function test_stop_when_low_load()
    {
        $service = new AutoSchedulerService();
        $this->assertEquals('stop', $service->getAction(3, 20));
    }

    public function test_none_when_normal_load()
    {
        $service = new AutoSchedulerService();
        $this->assertEquals('none', $service->getAction(3, 50));
    }

    public function test_no_terminate_below_min()
    {
        $service = new AutoSchedulerService();
        $this->assertEquals('none', $service->getAction(1, 5));
    }
}
