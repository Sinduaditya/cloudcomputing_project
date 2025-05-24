<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\DownloadService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckTokenBalance
{
    protected $downloadService;
    protected $tokenService;

    /**
     * Create a new middleware instance.
     *
     * @param DownloadService $downloadService
     * @param TokenService $tokenService
     */
    public function __construct(DownloadService $downloadService, TokenService $tokenService)
    {
        $this->downloadService = $downloadService;
        $this->tokenService = $tokenService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip check for non-authenticated users
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if the user is an admin (they can bypass token checks)
        if ($user->is_admin) {
            return $next($request);
        }

        // For new download requests, check token balance
        if ($request->isMethod('post') &&
            ($request->routeIs('downloads.store') || $request->routeIs('schedules.store'))) {

            try {
                $url = $request->input('url');

                if (!$url) {
                    return $next($request);
                }

                // Get download metadata and estimate token cost
                $metadata = $this->downloadService->analyze($url);

                if (isset($metadata['token_cost'])) {
                    $estimatedCost = $metadata['token_cost'];

                    // Check if user has enough tokens
                    if ($user->token_balance < $estimatedCost) {
                        if ($request->expectsJson()) {
                            return response()->json([
                                'error' => "Insufficient tokens. You need {$estimatedCost} tokens for this download.",
                                'token_balance' => $user->token_balance,
                                'required_tokens' => $estimatedCost
                            ], 403);
                        }

                        return redirect()->back()->withInput()->withErrors([
                            'token_balance' => "You need at least {$estimatedCost} tokens for this download. Your current balance is {$user->token_balance}."
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Log the error but allow the request to continue
                // The controller will handle detailed validation
                Log::warning('Token cost estimation failed in middleware', [
                    'url' => $request->input('url'),
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $next($request);
    }
}
