<?php

namespace App\Services;

use App\Models\User;
use App\Models\TokenTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TokenService
{
    /**
     * Deduct tokens from user balance
     */
    public function deductTokens(User $user, int $amount, string $type, string $description = null, $resourceId = null)
    {
        try {
            // Update user balance in database
            $user->token_balance -= $amount;
            $user->save();

            // Log transaction
            TokenTransaction::create([
                'user_id' => $user->id,
                'amount' => -$amount,
                'type' => $type,
                'description' => $description,
                'resource_id' => $resourceId,
                'balance_after' => $user->token_balance
            ]);

            // Use Laravel's file-based cache instead of Redis
            $this->cacheUserBalance($user);

            return true;
        } catch (\Exception $e) {
            Log::error('Error deducting tokens', [
                'user_id' => $user->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Refund tokens to user balance
     */
    public function refundTokens(User $user, int $amount, string $description = null, $resourceId = null)
    {
        try {
            // Update user balance
            $user->token_balance += $amount;
            $user->save();

            // Log transaction
            TokenTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'refund',
                'description' => $description,
                'resource_id' => $resourceId,
                'balance_after' => $user->token_balance
            ]);

            // Use Laravel's file-based cache
            $this->cacheUserBalance($user);

            return true;
        } catch (\Exception $e) {
            Log::error('Error refunding tokens', [
                'user_id' => $user->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Cache user token balance using Laravel's file cache
     */
    private function cacheUserBalance(User $user)
    {
        // Cache token balance for 24 hours using Laravel's cache
        Cache::put('user_balance:' . $user->id, $user->token_balance, now()->addHours(24));
    }

    /**
     * Get cached user token balance
     */
    public function getCachedBalance(User $user)
    {
        $cachedBalance = Cache::get('user_balance:' . $user->id);

        if ($cachedBalance !== null) {
            return (int) $cachedBalance;
        }

        // If not in cache, get from database and cache it
        $this->cacheUserBalance($user);
        return $user->token_balance;
    }
}
