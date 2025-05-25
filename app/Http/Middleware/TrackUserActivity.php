<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log for authenticated users and non-asset requests
        if (auth()->check() && !$this->shouldSkip($request)) {
            // Determine action and resource
            list($action, $resourceType, $resourceId) = $this->determineActionAndResource($request);

            // Create activity log
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'resource_type' => $resourceType,
                'resource_id' => $resourceId,
                'ip_address' => $request->ip(),
                'details' => json_encode([
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'user_agent' => $request->userAgent(),
                    'referrer' => $request->header('referer'),
                    'route' => $request->route() ? $request->route()->getName() : null,
                ])
            ]);
        }

        return $response;
    }

    /**
     * Check if logging should be skipped for this request
     */
    private function shouldSkip(Request $request)
    {
        // Skip assets, API requests, and common pages
        return $request->is('assets/*', 'js/*', 'css/*', 'images/*', 'api/*') ||
               $request->routeIs('login', 'logout', 'password.*') ||
               Str::contains($request->path(), ['livewire']) ||
               $request->ajax();
    }

    /**
     * Determine the action and related resource from the request
     */
    private function determineActionAndResource(Request $request)
    {
        $action = 'page_view';
        $resourceType = null;
        $resourceId = null;

        // Downloads
        if ($request->routeIs('downloads.create')) {
            $action = 'download_create';
        }
        elseif ($request->routeIs('downloads.show') && $request->route('download')) {
            $action = 'download_view';
            $resourceType = 'Download';
            $resourceId = $request->route('download');
        }
        elseif ($request->routeIs('downloads.index')) {
            $action = 'downloads_list';
        }

        // Schedules
        elseif ($request->routeIs('schedules.create')) {
            $action = 'schedule_create';
        }
        elseif ($request->routeIs('schedules.index')) {
            $action = 'schedules_list';
        }

        // Tokens
        elseif ($request->routeIs('tokens.*')) {
            $action = 'tokens_manage';
        }

        // Admin actions
        elseif (Str::startsWith($request->path(), 'admin')) {
            $action = 'admin_action';

            if (Str::contains($request->path(), 'users')) {
                $action = 'admin_users_manage';
            }
            elseif (Str::contains($request->path(), 'tokens')) {
                $action = 'admin_tokens_manage';
            }
        }

        return [$action, $resourceType, $resourceId];
    }
}
