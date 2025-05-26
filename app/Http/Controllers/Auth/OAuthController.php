<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Controllers\Auth\OAuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class OAuthController extends Controller
{
    /**
     * Redirect to OAuth provider
     */
    public function redirectToProvider($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            Log::error("OAuth redirect error: {$e->getMessage()}", [
                'provider' => $provider,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect('/login')->with('error', "Unable to connect to {$provider}: {$e->getMessage()}");
        }
    }

    /**
     * Handle callback from OAuth provider
     */

    public function handleProviderCallback($provider)
    {
        try {
            Log::info('Handling OAuth callback', ['provider' => $provider]);

            // Get user from provider
            $oauthUser = Socialite::driver($provider)->user();

            if (!$oauthUser) {
                throw new Exception('Unable to get user from OAuth provider');
            }

            Log::info('OAuth user retrieved', [
                'id' => $oauthUser->getId(),
                'email' => $oauthUser->getEmail(),
                'name' => $oauthUser->getName(),
                'provider' => $provider,
            ]);

            // Find or create user
            $user = User::findOrCreateByOAuth($oauthUser, $provider);

            if (!$user) {
                throw new Exception('Unable to create or find user');
            }

            // Login user
            Auth::login($user, true);

            Log::info('User logged in via OAuth', ['user_id' => $user->id]);

            // Redirect to dashboard
            return redirect()
                ->intended('/dashboard')
                ->with('success', 'Successfully logged in with ' . ucfirst($provider));
        } catch (Exception $e) {
            Log::error("OAuth callback error: {$e->getMessage()}", [
                'provider' => $provider,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect('/login')->with('error', "OAuth login with {$provider} failed. Please try again or use email/password login.");
        }
    }
}
