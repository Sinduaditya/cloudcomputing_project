<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Controllers\Admin\SystemController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Download;
use App\Models\ScheduledTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display system settings
     */
    public function settings()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_environment' => config('app.env'),
            'app_debug' => config('app.debug'),
            'app_url' => config('app.url'),
            'token_price' => config('app.token_price', 0.1),
            'default_token_balance' => config('app.default_token_balance', 100),
            'mb_per_token' => config('download.mb_per_token', 0.2),
            'min_tokens_per_download' => config('download.min_tokens', 5),
            'max_download_size' => config('download.max_size', 0),
            'max_storage_days' => config('app.max_storage_days', 7),
        ];

        // Service statuses
        $services = [
            'youtube' => [
                'enabled' => config('app.youtube_enabled', true),
                'api_key' => !empty(config('services.youtube.api_key')),
            ],
            'tiktok' => [
                'enabled' => config('app.tiktok_enabled', true),
                'api_key' => !empty(config('services.tiktok.api_key')),
            ],
            'instagram' => [
                'enabled' => config('app.instagram_enabled', true),
                'api_key' => !empty(config('services.instagram.api_key')),
            ],
            'cloudinary' => [
                'enabled' => !empty(config('cloudinary.cloud_name')),
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => !empty(config('cloudinary.api_key')),
            ],
        ];

        // Queue config
        $queue = [
            'driver' => config('queue.default'),
            'connection' => config('queue.connections.' . config('queue.default')),
            'failed_table' => config('queue.failed.table'),
        ];

        return view('admin.system.settings', compact('settings', 'services', 'queue'));
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'max_storage_days' => 'required|integer|min:1|max:365',
            'youtube_enabled' => 'sometimes|boolean',
            'tiktok_enabled' => 'sometimes|boolean',
            'instagram_enabled' => 'sometimes|boolean',
            'maintenance_mode' => 'sometimes|boolean',
        ]);

        try {
            // Save old settings for logging
            $oldSettings = [
                'app_name' => config('app.name'),
                'max_storage_days' => config('app.max_storage_days', 7),
                'youtube_enabled' => config('app.youtube_enabled', true),
                'tiktok_enabled' => config('app.tiktok_enabled', true),
                'instagram_enabled' => config('app.instagram_enabled', true),
                'maintenance_mode' => app()->isDownForMaintenance(),
            ];

            // Update .env file values
            $this->updateEnvValue('APP_NAME', '"' . $request->app_name . '"');
            $this->updateEnvValue('MAX_STORAGE_DAYS', $request->max_storage_days);
            $this->updateEnvValue('YOUTUBE_ENABLED', $request->has('youtube_enabled') ? 'true' : 'false');
            $this->updateEnvValue('TIKTOK_ENABLED', $request->has('tiktok_enabled') ? 'true' : 'false');
            $this->updateEnvValue('INSTAGRAM_ENABLED', $request->has('instagram_enabled') ? 'true' : 'false');

            // Handle maintenance mode
            $wasInMaintenance = app()->isDownForMaintenance();
            $shouldBeInMaintenance = $request->has('maintenance_mode');

            if ($shouldBeInMaintenance && !$wasInMaintenance) {
                Artisan::call('down', [
                    '--message' => 'The site is currently down for maintenance. Please check back later.',
                ]);
            } elseif (!$shouldBeInMaintenance && $wasInMaintenance) {
                Artisan::call('up');
            }

            // Clear cache
            Artisan::call('config:clear');

            // Log admin activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'admin_system_settings_updated',
                'details' => json_encode([
                    'old_settings' => $oldSettings,
                    'new_settings' => [
                        'app_name' => $request->app_name,
                        'max_storage_days' => $request->max_storage_days,
                        'youtube_enabled' => $request->has('youtube_enabled'),
                        'tiktok_enabled' => $request->has('tiktok_enabled'),
                        'instagram_enabled' => $request->has('instagram_enabled'),
                        'maintenance_mode' => $shouldBeInMaintenance,
                    ],
                ]),
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'System settings updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error updating system settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()
                ->with('error', 'Failed to update system settings: ' . $e->getMessage());
        }
    }

    /**
     * Update Cloudinary settings
     */
    public function updateCloudinary(Request $request)
    {
        $request->validate([
            'cloud_name' => 'required|string',
            'api_key' => 'required|string',
            'api_secret' => 'required|string',
        ]);

        try {
            // Update .env file values
            $this->updateEnvValue('CLOUDINARY_CLOUD_NAME', $request->cloud_name);
            $this->updateEnvValue('CLOUDINARY_API_KEY', $request->api_key);
            $this->updateEnvValue('CLOUDINARY_API_SECRET', $request->api_secret);

            // Clear cache
            Artisan::call('config:clear');

            // Log admin activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'admin_cloudinary_settings_updated',
                'details' => json_encode([
                    'cloud_name' => $request->cloud_name,
                    'api_key' => '******', // Don't log the actual key
                ]),
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Cloudinary settings updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error updating Cloudinary settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()
                ->with('error', 'Failed to update Cloudinary settings: ' . $e->getMessage());
        }
    }

    /**
     * Show maintenance tools
     */
    public function maintenance()
    {
        // Get cache size
        $cacheSize = 0;
        $cacheDir = storage_path('framework/cache/data');
        if (is_dir($cacheDir)) {
            $cacheSize = $this->getFolderSize($cacheDir);
        }

        // Get downloads temp folder size
        $tempSize = 0;
        $tempDir = storage_path('app/downloads/temp');
        if (is_dir($tempDir)) {
            $tempSize = $this->getFolderSize($tempDir);
        }

        // Get logs size
        $logsSize = 0;
        $logsDir = storage_path('logs');
        if (is_dir($logsDir)) {
            $logsSize = $this->getFolderSize($logsDir);
        }

        // Format sizes for display
        $formatSize = function($bytes) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $i = 0;
            while ($bytes > 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return round($bytes, 2) . ' ' . $units[$i];
        };

        $storageStats = [
            'cache_size' => $formatSize($cacheSize),
            'temp_size' => $formatSize($tempSize),
            'logs_size' => $formatSize($logsSize),
            'total_size' => $formatSize($cacheSize + $tempSize + $logsSize),
        ];

        // Get downloads stats
        $downloadsStats = [
            'total' => Download::count(),
            'completed' => Download::where('status', 'completed')->count(),
            'failed' => Download::where('status', 'failed')->count(),
            'pending' => Download::whereIn('status', ['pending', 'processing', 'downloading', 'uploading'])->count(),
            'old_completed' => Download::where('status', 'completed')
                ->where('created_at', '<', Carbon::now()->subDays(config('app.max_storage_days', 7)))
                ->count(),
        ];

        // Get scheduled tasks stats
        $scheduledStats = [
            'total' => ScheduledTask::count(),
            'scheduled' => ScheduledTask::where('status', 'scheduled')->count(),
            'processing' => ScheduledTask::where('status', 'processing')->count(),
            'completed' => ScheduledTask::where('status', 'completed')->count(),
            'failed' => ScheduledTask::where('status', 'failed')->count(),
            'overdue' => ScheduledTask::where('status', 'scheduled')
                ->where('scheduled_for', '<', Carbon::now())
                ->count(),
        ];

        return view('admin.system.maintenance', compact(
            'storageStats',
            'downloadsStats',
            'scheduledStats'
        ));
    }

    /**
     * Run maintenance tasks
     */
    public function runMaintenance(Request $request)
    {
        $request->validate([
            'task' => 'required|string|in:clear_cache,clear_temp,clear_logs,clear_old_downloads,queue_restart,rerun_failed_jobs,cancel_overdue_tasks',
        ]);

        try {
            $task = $request->task;
            $result = '';

            switch ($task) {
                case 'clear_cache':
                    Artisan::call('cache:clear');
                    $result = 'Cache cleared successfully.';
                    break;

                case 'clear_temp':
                    $tempDir = storage_path('app/downloads/temp');
                    if (is_dir($tempDir)) {
                        $files = glob($tempDir . '/*');
                        foreach ($files as $file) {
                            if (is_file($file)) {
                                unlink($file);
                            }
                        }
                    }
                    $result = 'Temporary files cleared successfully.';
                    break;

                case 'clear_logs':
                    $logsDir = storage_path('logs');
                    if (is_dir($logsDir)) {
                        $files = glob($logsDir . '/*.log');
                        foreach ($files as $file) {
                            if (is_file($file) && basename($file) !== 'laravel.log') {
                                unlink($file);
                            }
                        }
                        // Truncate current log file
                        file_put_contents(storage_path('logs/laravel.log'), '');
                    }
                    $result = 'Log files cleared successfully.';
                    break;

                case 'clear_old_downloads':
                    $days = config('app.max_storage_days', 7);
                    $cutoffDate = Carbon::now()->subDays($days);

                    // Mark old downloads as expired
                    $count = Download::where('status', 'completed')
                        ->where('created_at', '<', $cutoffDate)
                        ->update([
                            'status' => 'expired',
                            'file_path' => null,
                            'storage_url' => null,
                        ]);

                    $result = "{$count} old downloads marked as expired.";
                    break;

                case 'queue_restart':
                    Artisan::call('queue:restart');
                    $result = 'Queue workers restarted successfully.';
                    break;

                case 'rerun_failed_jobs':
                    Artisan::call('queue:retry', ['id' => 'all']);
                    $result = 'Failed jobs have been queued for retry.';
                    break;

                case 'cancel_overdue_tasks':
                    $count = ScheduledTask::where('status', 'scheduled')
                        ->where('scheduled_for', '<', Carbon::now())
                        ->update([
                            'status' => 'cancelled',
                            'error_message' => 'Automatically cancelled as overdue by system maintenance',
                        ]);

                    $result = "{$count} overdue scheduled tasks cancelled.";
                    break;
            }

            // Log admin activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'admin_maintenance_task',
                'details' => json_encode([
                    'task' => $task,
                    'result' => $result,
                ]),
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', $result);

        } catch (\Exception $e) {
            Log::error('Error running maintenance task', [
                'task' => $request->task ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Maintenance task failed: ' . $e->getMessage());
        }
    }

    /**
     * Display system information
     */
    public function info()
    {
        // Get PHP info
        $phpInfo = [
            'version' => PHP_VERSION,
            'os' => PHP_OS,
            'max_upload' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time') . 's',
            'memory_limit' => ini_get('memory_limit'),
            'post_max_size' => ini_get('post_max_size'),
        ];

        // Get Laravel info
        $laravelInfo = [
            'version' => app()->version(),
            'environment' => config('app.env'),
            'debug' => config('app.debug') ? 'Enabled' : 'Disabled',
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
        ];

        // Get server info
        $serverInfo = [
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
            'server_protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
            'request_time' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'] ?? time()),
        ];

        // Get database info
        try {
            $databaseInfo = [
                'driver' => config('database.default'),
                'database' => config('database.connections.' . config('database.default') . '.database'),
                'version' => DB::select('SELECT version() as version')[0]->version ?? 'Unknown',
            ];

            // Get table counts
            $tables = [
                'users' => DB::table('users')->count(),
                'downloads' => DB::table('downloads')->count(),
                'token_transactions' => DB::table('token_transactions')->count(),
                'scheduled_tasks' => DB::table('scheduled_tasks')->count(),
                'activity_logs' => DB::table('activity_logs')->count(),
            ];
        } catch (\Exception $e) {
            $databaseInfo = [
                'driver' => config('database.default'),
                'error' => $e->getMessage(),
            ];
            $tables = [];
        }

        return view('admin.system.info', compact(
            'phpInfo',
            'laravelInfo',
            'serverInfo',
            'databaseInfo',
            'tables'
        ));
    }

    /**
     * Display system logs
     */
    public function logs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logContents = '';

        if (file_exists($logFile)) {
            // Get last 1000 lines
            $logContents = $this->getTailOfFile($logFile, 1000);
        }

        // Get all log files
        $logFiles = glob(storage_path('logs/*.log'));
        $logFilesList = [];

        foreach ($logFiles as $file) {
            $logFilesList[] = [
                'name' => basename($file),
                'size' => $this->formatBytes(filesize($file)),
                'modified' => date('Y-m-d H:i:s', filemtime($file)),
            ];
        }

        return view('admin.system.logs', compact('logContents', 'logFilesList'));
    }

    /**
     * View specific log file
     */
    public function viewLog($filename)
    {
        $logFile = storage_path('logs/' . $filename);
        $logContents = '';

        if (file_exists($logFile) && is_file($logFile) && pathinfo($logFile, PATHINFO_EXTENSION) === 'log') {
            // Get last 1000 lines
            $logContents = $this->getTailOfFile($logFile, 1000);
        }

        return view('admin.system.view-log', compact('logContents', 'filename'));
    }

    /**
     * Clear a specific log file
     */
    public function clearLog($filename)
    {
        try {
            $logFile = storage_path('logs/' . $filename);

            if (file_exists($logFile) && is_file($logFile) && pathinfo($logFile, PATHINFO_EXTENSION) === 'log') {
                file_put_contents($logFile, '');

                // Log admin activity
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'admin_log_cleared',
                    'details' => json_encode(['filename' => $filename]),
                    'ip_address' => request()->ip(),
                ]);

                return back()->with('success', "Log file {$filename} cleared successfully.");
            }

            return back()->with('error', "Log file {$filename} not found or not a valid log file.");

        } catch (\Exception $e) {
            Log::error('Error clearing log file', [
                'filename' => $filename,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to clear log file: ' . $e->getMessage());
        }
    }

    /**
     * Update .env file with new values
     */
    private function updateEnvValue($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $envContent = file_get_contents($path);

            // If key exists, replace its value
            if (strpos($envContent, "{$key}=") !== false) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                // Key doesn't exist, append it
                $envContent .= PHP_EOL . "{$key}={$value}";
            }

            file_put_contents($path, $envContent);
        }
    }

    /**
     * Get folder size in bytes
     */
    private function getFolderSize($dir)
    {
        $size = 0;

        foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : $this->getFolderSize($each);
        }

        return $size;
    }

    /**
     * Format bytes to human-readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get the tail of a file
     */
    private function getTailOfFile($file, $lines = 100)
    {
        $handle = fopen($file, "r");
        $linecounter = $lines;
        $pos = -2;
        $beginning = false;
        $text = [];

        while ($linecounter > 0) {
            $t = " ";

            while ($t != "\n") {
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }

                $t = fgetc($handle);
                $pos--;
            }

            if ($beginning) {
                rewind($handle);
            }

            $text[$lines - $linecounter] = fgets($handle);

            if ($beginning) {
                break;
            }

            $linecounter--;
        }

        fclose($handle);

        return implode("", $text);
    }
}
