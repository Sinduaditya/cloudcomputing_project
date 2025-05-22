<?php
// app/Http/Middleware/CheckTokenBalance.php
namespace App\Http\Middleware;

use Closure;
use App\Services\DownloadService;
use Illuminate\Http\Request;

class CheckTokenBalance
{
    protected $downloadService;

    public function __construct(DownloadService $downloadService)
    {
        $this->downloadService = $downloadService;
    }

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // For download requests, check if user has minimum tokens
        if ($request->isMethod('post') && $request->has('url') && $request->has('format')) {
            try {
                $url = $request->url;
                $platform = $this->downloadService->determinePlatform($url);
                $metadata = $this->downloadService->getVideoMetadata($url, $platform);

                $estimatedCost = $this->downloadService->calculateTokenCost(
                    $platform,
                    $metadata['duration'] ?? 0,
                    $request->format,
                    $request->quality ?? '720p'
                );

                if ($user->token_balance < $estimatedCost) {
                    return redirect()->back()->withErrors([
                        'token_balance' => "You need at least {$estimatedCost} tokens for this download. Your current balance is {$user->token_balance}."
                    ]);
                }
            } catch (\Exception $e) {
                // If we can't determine the cost, let it pass and controller will handle it
            }
        }

        return $next($request);
    }
}
