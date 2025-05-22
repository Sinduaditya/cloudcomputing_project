<?php
// app/Http/Controllers/Auth/OAuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    /**
     * Redirect to OAuth provider
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle callback from OAuth provider
     */
    public function handleProviderCallback($provider)
    {
        try {
            $oauthUser = Socialite::driver($provider)->user();

            // Find or create user
            $user = User::findOrCreateByOAuth($oauthUser, $provider);

            // Login user
            Auth::login($user, true);

            // Redirect to dashboard
            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'OAuth login failed: ' . $e->getMessage());
        }
    }
}
