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
        $activities = auth()->user()->activityLogs()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.activity', compact('activities'));
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
