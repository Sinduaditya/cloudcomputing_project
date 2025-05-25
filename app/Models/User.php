<?php

namespace App\Models;

use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'token_balance', 'is_admin', 'is_active', 'provider', 'provider_id', 'avatar'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'token_balance' => 'integer',
    ];

    /**
     * Get all downloads for the user
     */
    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    /**
     * Get all token transactions for the user
     */
    public function tokenTransactions()
    {
        return $this->hasMany(TokenTransaction::class);
    }

    /**
     * Get all scheduled tasks for the user
     */
    public function scheduledTasks()
    {
        return $this->hasMany(ScheduledTask::class);
    }

    /**
     * Get all activity logs for the user
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Check if the user is an admin
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Get the avatar URL or default image
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Find or create user by OAuth provider
     */
    public static function findOrCreateByOAuth($oauthUser, $provider)
    {
        try {
            // Get provider ID
            $providerId = $oauthUser->getId();
            if (empty($providerId)) {
                throw new Exception('Provider ID is missing');
            }

            // Get email
            $email = $oauthUser->getEmail();
            if (empty($email)) {
                throw new Exception('Email is missing from OAuth provider');
            }

            // Try to find by provider ID first
            $user = self::where('provider', $provider)->where('provider_id', $providerId)->first();

            if (!$user) {
                // Then try to find by email
                $user = self::where('email', $email)->first();

                if (!$user) {
                    // Create new user
                    $user = self::create([
                        'name' => $oauthUser->getName() ?? 'User',
                        'email' => $email,
                        'provider' => $provider,
                        'provider_id' => $providerId,
                        'avatar' => $oauthUser->getAvatar(),
                        'token_balance' => config('app.default_token_balance', 100),
                        'is_active' => true,
                    ]);

                    // Record initial token transaction
                    \App\Models\TokenTransaction::create([
                        'user_id' => $user->id,
                        'amount' => config('app.default_token_balance', 100),
                        'balance_after' => config('app.default_token_balance', 100),
                        'type' => 'initial',
                        'description' => 'Initial token allocation',
                    ]);
                } else {
                    // Update existing user with OAuth info
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $providerId,
                        'avatar' => $oauthUser->getAvatar(),
                    ]);
                }
            }

            return $user;
        } catch (Exception $e) {
            Log::error('Error in findOrCreateByOAuth: ' . $e->getMessage(), [
                'provider' => $provider,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // Re-throw to be handled by the controller
        }
    }
}
