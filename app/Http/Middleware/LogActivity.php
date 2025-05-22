<?php
// app/Http/Middleware/LogActivity.php
namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log certain routes
        if (auth()->check() && !$this->shouldSkip($request)) {
            // Log the activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $this->determineAction($request),
                'ip_address' => $request->ip(),
                'details' => json_encode([
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'user_agent' => $request->userAgent()
                ])
            ]);
        }

        return $response;
    }

    private function shouldSkip(Request $request)
    {
        // Skip logging for certain routes
        $skipRoutes = [
            'login', 'logout', 'register',
            'downloads.index', 'schedules.index',
        ];

        return $request->routeIs($skipRoutes);
    }

    private function determineAction(Request $request)
    {
        if ($request->routeIs('downloads.*')) {
            return 'browse_downloads';
        }

        if ($request->routeIs('schedules.*')) {
            return 'browse_schedules';
        }

        return 'page_view';
    }
}
