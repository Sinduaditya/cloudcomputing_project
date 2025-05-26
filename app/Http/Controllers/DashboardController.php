<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Download;
use App\Models\ScheduledTask;
use App\Models\TokenTransaction;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        // Get counts
        $stats = [
            'downloads_count' => $user->downloads()->count(),
            'completed_downloads' => $user->downloads()->where('status', 'completed')->count(),
            'pending_downloads' => $user->downloads()->whereIn('status', ['pending', 'processing', 'downloading', 'uploading'])->count(),
            'scheduled_tasks' => $user->scheduledTasks()->where('status', 'scheduled')->count(),
            'token_balance' => $user->token_balance,
            'total_downloaded_mb' => round($user->downloads()->where('status', 'completed')->sum('file_size') / (1024 * 1024), 2)
        ];

        // Get recent downloads
        $recentDownloads = $user->downloads()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get upcoming scheduled tasks
        $upcomingTasks = $user->scheduledTasks()
            ->where('status', 'scheduled')
            ->where('scheduled_for', '>=', now())
            ->orderBy('scheduled_for', 'asc')
            ->limit(3)
            ->get();

        // Get recent activities
        $recentActivities = $user->activityLogs()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get download statistics by platform
        $platformStats = $user->downloads()
            ->select('platform', DB::raw('count(*) as count'))
            ->groupBy('platform')
            ->get()
            ->pluck('count', 'platform')
            ->toArray();

        // Get token usage history (last 5 transactions)
        $recentTransactions = $user->tokenTransactions()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'recentDownloads',
            'upcomingTasks',
            'recentActivities',
            'platformStats',
            'recentTransactions'
        ));
    }

    /**
     * Display user's activity log
     *
     * @return \Illuminate\View\View
     */
    public function activity()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $query = \App\Models\ActivityLog::query()
                ->whereNotIn('action', ['page_view']);
                
            if (request('user')) {
                $query->whereHas('user', function($q) {
                    $q->where('email', 'like', '%'.request('user').'%')
                      ->orWhere('name', 'like', '%'.request('user').'%');
                });
            }
        } else {
            $query = $user->activityLogs()
                ->whereNotIn('action', ['page_view']);
        }

        // Filter berdasarkan action
        if (request('action')) {
            switch(request('action')) {
                case 'download':
                    $query->where('action', 'like', '%download%');
                    break;
                case 'schedule':
                    $query->where('action', 'like', '%schedule%');
                    break;
                case 'token':
                    $query->where('action', 'like', '%token%');
                    break;
                case 'admin':
                    $query->where('action', 'like', '%admin%');
                    break;
                case 'failed':
                    $query->where('action', 'like', '%fail%')
                          ->orWhere('action', 'like', '%error%');
                    break;
                default:
                    $query->where('action', request('action'));
            }
        }

        // Filter berdasarkan resource type
        if (request('resource_type')) {
            switch(request('resource_type')) {
                case 'Download':
                    $query->where('resource_type', 'Download')
                          ->orWhere('action', 'like', '%download%');
                    break;
                case 'Schedule':
                    $query->where('resource_type', 'Schedule')
                          ->orWhere('action', 'like', '%schedule%');
                    break;
                case 'TokenTransaction':
                    $query->where('resource_type', 'TokenTransaction')
                          ->orWhere('action', 'like', '%token%');
                    break;
                default:
                    $query->where('resource_type', request('resource_type'));
            }
        }

        // Filter berdasarkan resource ID
        if (request('resource_id')) {
            $query->where('resource_id', request('resource_id'));
        }

        // Filter berdasarkan IP address (untuk admin)
        if ($user->isAdmin() && request('ip_address')) {
            $query->where('ip_address', 'like', '%'.request('ip_address').'%');
        }

        // Filter berdasarkan tanggal
        if (request('from_date')) {
            $query->whereDate('created_at', '>=', request('from_date'));
        }
        if (request('to_date')) {
            $query->whereDate('created_at', '<=', request('to_date'));
        }

        // Sorting
        $sort = request('sort', 'created_at_desc');
        if ($sort === 'created_at_asc') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Stats for admin or user
        if ($user->isAdmin()) {
            $stats = [
                'total' => \App\Models\ActivityLog::whereNotIn('action', ['page_view'])->count(),
                'today' => \App\Models\ActivityLog::whereDate('created_at', today())
                    ->whereNotIn('action', ['page_view'])
                    ->count(),
                'users' => \App\Models\User::count(),
                'admin_actions' => \App\Models\ActivityLog::where('action', 'LIKE', '%admin%')->count()
            ];
        } else {
            $stats = [
                'total' => $user->activityLogs()->count(),
                'today' => $user->activityLogs()
                    ->whereDate('created_at', today())
                    ->whereNotIn('action', ['page_view'])
                    ->count(),
                'downloads' => $user->activityLogs()
                    ->where(function($q) {
                        $q->where('action', 'like', '%download%')
                          ->orWhere('resource_type', 'Download');
                    })
                    ->count(),
                'schedules' => $user->activityLogs()
                    ->where(function($q) {
                        $q->where('action', 'like', '%schedule%')
                          ->orWhere('resource_type', 'Schedule');
                    })
                    ->count()
            ];
        }

        $activities = $query->paginate(20);

        return view('dashboard.activity', compact('activities', 'stats'));
    }

    /**
     * Display user's statistics and analytics
     *
     * @return \Illuminate\View\View
     */
    public function stats()
    {
        $user = auth()->user();

        // Downloads by month (last 6 months)
        $downloadsByMonth = $user->downloads()
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Downloads by platform
        $downloadsByPlatform = $user->downloads()
            ->select('platform', DB::raw('count(*) as count'))
            ->groupBy('platform')
            ->get();

        // Token usage by type
        $tokenUsageByType = $user->tokenTransactions()
            ->select('type', DB::raw('sum(amount) as total'))
            ->groupBy('type')
            ->get();

        return view('dashboard.stats', compact('downloadsByMonth', 'downloadsByPlatform', 'tokenUsageByType'));
    }
}
