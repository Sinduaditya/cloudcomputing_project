<?php

namespace App\Services;

class ScalingService
{
    // Example: scale up/down based on load
    public function decideScaling(int $currentInstances, int $currentLoad, int $maxInstances = 10, int $minInstances = 1)
    {
        // Simple threshold-based scaling
        if ($currentLoad > 80 && $currentInstances < $maxInstances) {
            return $currentInstances + 1; // scale up
        }
        if ($currentLoad < 30 && $currentInstances > $minInstances) {
            return $currentInstances - 1; // scale down
        }
        return $currentInstances; // no change
    }
}
