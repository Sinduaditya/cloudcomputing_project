<?php

namespace App\Services;

class AutoSchedulerService
{
    public function getAction(int $currentInstances, int $currentLoad, int $maxInstances = 10, int $minInstances = 1)
    {
        // Example logic:
        // - If load is very high, start new instance
        // - If load is very low, stop or terminate instance
        if ($currentLoad > 80 && $currentInstances < $maxInstances) {
            return 'start';
        }
        if ($currentLoad < 10 && $currentInstances > $minInstances) {
            return 'terminate';
        }
        if ($currentLoad < 30 && $currentInstances > $minInstances) {
            return 'stop';
        }
        return 'none';
    }
}
