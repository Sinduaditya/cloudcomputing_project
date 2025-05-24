<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ScalingService;

class ScalingServiceTest extends TestCase
{
    public function test_scale_up_when_high_load()
    {
        $service = new ScalingService();
        $this->assertEquals(4, $service->decideScaling(3, 90));
    }

    public function test_scale_down_when_low_load()
    {
        $service = new ScalingService();
        $this->assertEquals(2, $service->decideScaling(3, 20));
    }

    public function test_no_scale_when_normal_load()
    {
        $service = new ScalingService();
        $this->assertEquals(3, $service->decideScaling(3, 50));
    }

    public function test_no_scale_above_max()
    {
        $service = new ScalingService();
        $this->assertEquals(10, $service->decideScaling(10, 90));
    }

    public function test_no_scale_below_min()
    {
        $service = new ScalingService();
        $this->assertEquals(1, $service->decideScaling(1, 10));
    }
}
