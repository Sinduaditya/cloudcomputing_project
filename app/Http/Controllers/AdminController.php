<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Controllers\AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Download;
use App\Models\ActivityLog;
use App\Models\TokenTransaction;
use App\Models\ScheduledTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Admin dashboard
     */
    public function index()
    {
        // Key metrics - add missing 'active_now'
        $metrics = [
            'total_users' => User::where('is_admin', false)->count(),
            'active_users' => User::where('is_admin', false)->where('is_active', true)->count(),
            'total_downloads' => Download::count(),
            'completed_downloads' => Download::where('status', 'completed')->count(),
            'failed_downloads' => Download::where('status', 'failed')->count(),
            'total_tokens' => User::sum('token_balance'),
            'pending_tasks' => ScheduledTask::where('status', 'scheduled')->count(),
            // Add this missing key:
            'active_now' => User::where('is_admin', false)
                ->where('updated_at', '>=', now()->subMinutes(5))
                ->count(),
        ];

        // Get system health data (required by dashboard)
        $health = [
            'storage' => [
                'free_space' => disk_free_space(storage_path()) / (1024 * 1024 * 1024), // GB
                'total_space' => disk_total_space(storage_path()) / (1024 * 1024 * 1024), // GB
                'usage_percent' => 100 - (disk_free_space(storage_path()) / disk_total_space(storage_path()) * 100),
            ],
            'queue' => [
                'default_queue_size' => DB::table('jobs')->where('queue', 'default')->count(),
                'scheduled_queue_size' => DB::table('jobs')->where('queue', 'scheduled')->count(),
                'failed_jobs' => DB::table('failed_jobs')->count(),
            ],
            'database' => [
                'users_count' => User::count(),
                'downloads_count' => Download::count(),
                'activity_logs_count' => ActivityLog::count(),
            ],
        ];

        // Get service status (required by dashboard)
        $services = [
            'youtube' => $this->checkServiceHealth('youtube'),
            'tiktok' => $this->checkServiceHealth('tiktok'),
            'instagram' => $this->checkServiceHealth('instagram'),
            'cloudinary' => $this->checkCloudinaryHealth(),
        ];

        // Recent activity
        $recentActivity = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Downloads by platform (for chart) - rename variable to match dashboard
        $platformStats = Download::select('platform', DB::raw('count(*) as count'))
            ->groupBy('platform')
            ->get()
            ->pluck('count', 'platform')
            ->toArray();

        // Add missing platforms with 0 count
        $platformStats = array_merge([
            'youtube' => 0,
            'tiktok' => 0,
            'instagram' => 0,
            'other' => 0
        ], $platformStats);

        // Downloads by day for last 14 days (for chart)
        $downloadsByDay = Download::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Format dates for chart
        $dates = [];
        $counts = [];
        for ($i = 0; $i < 14; $i++) {
            $date = Carbon::now()->subDays(13 - $i)->format('Y-m-d');
            $dates[] = Carbon::parse($date)->format('d M');
            $counts[] = $downloadsByDay[$date] ?? 0;
        }

        // Recent registrations - rename to match dashboard
        $newUsers = User::where('is_admin', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Failed downloads that need attention
        $failedDownloads = Download::with('user')
            ->where('status', 'failed')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'metrics',
            'health',
            'services',
            'recentActivity',
            'platformStats',
            'dates',
            'counts',
            'newUsers',
            'failedDownloads'
        ));
    }

    /**
     * Check service health
     */
    private function checkServiceHealth($service)
    {
        // This is a placeholder - in a real app, you'd have more sophisticated checks
        $serviceClass = 'App\\Services\\' . ucfirst($service) . 'Service';

        if (!class_exists($serviceClass)) {
            return false;
        }

        try {
            $serviceInstance = app($serviceClass);
            $isConfigured = method_exists($serviceInstance, 'isConfigured')
                ? $serviceInstance->isConfigured()
                : true;

            return $isConfigured;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check Cloudinary health
     */
    private function checkCloudinaryHealth()
    {
        // Since you're not using Cloudinary, return false
        return false;
    }

    /**
     * System health check
     */
    public function systemHealth()
    {
        $health = [
            'storage' => [
                'free_space' => disk_free_space(storage_path()) / (1024 * 1024 * 1024), // GB
                'total_space' => disk_total_space(storage_path()) / (1024 * 1024 * 1024), // GB
                'usage_percent' => 100 - (disk_free_space(storage_path()) / disk_total_space(storage_path()) * 100),
            ],
            'queue' => [
                'default_queue_size' => DB::table('jobs')->where('queue', 'default')->count(),
                'scheduled_queue_size' => DB::table('jobs')->where('queue', 'scheduled')->count(),
                'failed_jobs' => DB::table('failed_jobs')->count(),
            ],
            'files' => [
                'temp_files' => count(glob(storage_path('app/downloads/temp/*'))),
                'temp_size' => $this->getFolderSize(storage_path('app/downloads/temp')) / (1024 * 1024), // MB
            ],
            'database' => [
                'users_count' => User::count(),
                'downloads_count' => Download::count(),
                'activity_logs_count' => ActivityLog::count(),
                'token_transactions_count' => TokenTransaction::count(),
                'scheduled_tasks_count' => ScheduledTask::count(),
            ],
        ];

        // Check services availability
        $services = [
            'youtube' => $this->checkServiceHealth('youtube'),
            'tiktok' => $this->checkServiceHealth('tiktok'),
            'instagram' => $this->checkServiceHealth('instagram'),
            'cloudinary' => $this->checkCloudinaryHealth(),
            'queue' => $this->isQueueRunning(),
            'scheduler' => $this->isSchedulerRunning(),
        ];

        return view('admin.system-health', compact('health', 'services'));
    }

    /**
     * Get folder size in bytes
     */
    private function getFolderSize($folder)
    {
        $size = 0;

        if (!is_dir($folder)) {
            return 0;
        }

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder)) as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        return $size;
    }

    /**
     * Check if queue is running
     */
    private function isQueueRunning()
    {
        try {
            $lastJob = DB::table('jobs')->orderBy('id', 'desc')->first();
            $lastRun = $lastJob ? Carbon::parse($lastJob->created_at) : null;

            if (!$lastRun || $lastRun->diffInHours() > 1) {
                return [
                    'status' => 'idle',
                    'message' => 'No recent queue activity detected',
                ];
            }

            return [
                'status' => 'operational',
                'message' => 'Queue worker appears to be running',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error checking queue: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check if scheduler is running
     */
    private function isSchedulerRunning()
    {
        try {
            // Check if there are any scheduled tasks that should have run
            $missedTasks = ScheduledTask::where('status', 'scheduled')
                ->where('scheduled_for', '<', Carbon::now()->subHours(2))
                ->count();

            if ($missedTasks > 0) {
                return [
                    'status' => 'issue',
                    'message' => "Scheduler may not be running. {$missedTasks} scheduled tasks missed.",
                ];
            }

            return [
                'status' => 'operational',
                'message' => 'Scheduler appears to be running normally',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error checking scheduler: ' . $e->getMessage(),
            ];
        }
    }
}
