<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\system\maintenance.blade.php -->
@extends('layouts.admin')

@section('title', 'System Maintenance')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">System Maintenance</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.system.info') }}" class="neo-btn btn-info">
                <i class="fas fa-info-circle me-2"></i> System Info
            </a>
            <a href="{{ route('admin.system.logs') }}" class="neo-btn btn-secondary">
                <i class="fas fa-file-alt me-2"></i> System Logs
            </a>
            <a href="{{ route('admin.system.settings') }}" class="neo-btn" style="color: #ffffff;">
                <i class="fas fa-cog me-2"></i> Settings
            </a>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="neo-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-hdd me-2"></i> Storage</h5>
                    <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 14px;">
                        {{ $storageStats['total_size'] }}
                    </span>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Cache Size:</span>
                        <span class="badge bg-info" style="border: 1px solid #121212;">{{ $storageStats['cache_size'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Temp Files:</span>
                        <span class="badge bg-warning" style="border: 1px solid #121212;">{{ $storageStats['temp_size'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Log Files:</span>
                        <span class="badge bg-danger" style="border: 1px solid #121212;">{{ $storageStats['logs_size'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="neo-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-download me-2"></i> Downloads</h5>
                    <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 14px;">
                        {{ number_format($downloadsStats['total']) }} Total
                    </span>
                </div>
                <div class="card-body"  style="padding: 24px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Completed:</span>
                        <span class="badge bg-success" style="border: 1px solid #121212;">{{ number_format($downloadsStats['completed']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Pending/Processing:</span>
                        <span class="badge bg-warning" style="border: 1px solid #121212;">{{ number_format($downloadsStats['pending']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Failed:</span>
                        <span class="badge bg-danger" style="border: 1px solid #121212;">{{ number_format($downloadsStats['failed']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Old Completed (ready for cleanup):</span>
                        <span class="badge bg-secondary" style="border: 1px solid #121212;">{{ number_format($downloadsStats['old_completed']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="neo-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Scheduled Tasks</h5>
                    <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 14px;">
                        {{ number_format($scheduledStats['total']) }} Total
                    </span>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Scheduled:</span>
                        <span class="badge bg-info" style="border: 1px solid #121212;">{{ number_format($scheduledStats['scheduled']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Processing:</span>
                        <span class="badge bg-warning" style="border: 1px solid #121212;">{{ number_format($scheduledStats['processing']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Completed:</span>
                        <span class="badge bg-success" style="border: 1px solid #121212;">{{ number_format($scheduledStats['completed']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Failed:</span>
                        <span class="badge bg-danger" style="border: 1px solid #121212;">{{ number_format($scheduledStats['failed']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Overdue:</span>
                        <span class="badge bg-dark" style="border: 1px solid #121212;">{{ number_format($scheduledStats['overdue']) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Tasks -->
    <div class="row">
        <div class="col-md-6">
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-broom me-2"></i> Cleanup Tasks</h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="list-group-item-neo mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-trash-alt me-2"></i> Clear Cache</h6>
                            <span class="badge bg-info" style="border: 1px solid #121212;">{{ $storageStats['cache_size'] }}</span>
                        </div>
                        <p class="text-muted mb-3 small">Clears Laravel cache, including routes, views, and application cache.</p>
                        <form action="{{ route('admin.system.run-maintenance') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task" value="clear_cache">
                            <button type="submit" class="neo-btn w-100" onclick="return confirm('Are you sure you want to clear all cache?')">
                                <i class="fas fa-broom me-2"></i> Clear Cache
                            </button>
                        </form>
                    </div>

                    <div class="list-group-item-neo mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-file-archive me-2"></i> Clear Temporary Files</h6>
                            <span class="badge bg-warning" style="border: 1px solid #121212;">{{ $storageStats['temp_size'] }}</span>
                        </div>
                        <p class="text-muted mb-3 small">Removes all temporary files created during download processing.</p>
                        <form action="{{ route('admin.system.run-maintenance') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task" value="clear_temp">
                            <button type="submit" class="neo-btn w-100" onclick="return confirm('Are you sure you want to clear all temporary files?')">
                                <i class="fas fa-broom me-2"></i> Clear Temporary Files
                            </button>
                        </form>
                    </div>

                    <div class="list-group-item-neo mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i> Clear Log Files</h6>
                            <span class="badge bg-danger" style="border: 1px solid #121212;">{{ $storageStats['logs_size'] }}</span>
                        </div>
                        <p class="text-muted mb-3 small">Clears all log files except the current day's log file.</p>
                        <form action="{{ route('admin.system.run-maintenance') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task" value="clear_logs">
                            <button type="submit" class="neo-btn w-100" onclick="return confirm('Are you sure you want to clear log files? This cannot be undone.')">
                                <i class="fas fa-broom me-2"></i> Clear Log Files
                            </button>
                        </form>
                    </div>

                    <div class="list-group-item-neo">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-calendar-times me-2"></i> Clear Old Downloads</h6>
                            <span class="badge bg-secondary" style="border: 1px solid #121212;">{{ number_format($downloadsStats['old_completed']) }}</span>
                        </div>
                        <p class="text-muted mb-3 small">
                            Marks completed downloads older than {{ config('app.max_storage_days', 7) }} days as expired and removes their file references.
                        </p>
                        <form action="{{ route('admin.system.run-maintenance') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task" value="clear_old_downloads">
                            <button type="submit" class="neo-btn w-100" onclick="return confirm('Are you sure you want to clear old downloads? This will remove file references for downloads older than {{ config('app.max_storage_days', 7) }} days.')">
                                <i class="fas fa-broom me-2"></i> Clear Old Downloads
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i> System Tasks</h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="list-group-item-neo mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-sync-alt me-2"></i> Restart Queue Workers</h6>
                            <span class="badge bg-primary" style="border: 1px solid #121212;">Queue: {{ config('queue.default') }}</span>
                        </div>
                        <p class="text-muted mb-3 small">
                            Signals all queue workers to restart after their current job is processed.
                            This is useful after deployment or configuration changes.
                        </p>
                        <form action="{{ route('admin.system.run-maintenance') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task" value="queue_restart">
                            <button type="submit" class="neo-btn w-100">
                                <i class="fas fa-sync-alt me-2"></i> Restart Queue Workers
                            </button>
                        </form>
                    </div>

                    <div class="list-group-item-neo mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-redo me-2"></i> Retry Failed Jobs</h6>
                            <span class="badge bg-warning" style="border: 1px solid #121212;">
                                @if(DB::table('failed_jobs')->exists())
                                    {{ DB::table('failed_jobs')->count() }} Failed
                                @else
                                    0 Failed
                                @endif
                            </span>
                        </div>
                        <p class="text-muted mb-3 small">
                            Attempts to retry all failed jobs in the queue. Check logs after running this to verify results.
                        </p>
                        <form action="{{ route('admin.system.run-maintenance') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task" value="rerun_failed_jobs">
                            <button type="submit" class="neo-btn w-100"
                                {{ DB::table('failed_jobs')->exists() ? '' : 'disabled' }}
                                onclick="return confirm('Are you sure you want to retry all failed jobs?')">
                                <i class="fas fa-redo me-2"></i> Retry Failed Jobs
                            </button>
                        </form>
                    </div>

                    <div class="list-group-item-neo">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-times-circle me-2"></i> Cancel Overdue Tasks</h6>
                            <span class="badge bg-danger" style="border: 1px solid #121212;">{{ $scheduledStats['overdue'] }} Overdue</span>
                        </div>
                        <p class="text-muted mb-3 small">
                            Cancels all scheduled tasks that were scheduled to run in the past but are still in "scheduled" status.
                        </p>
                        <form action="{{ route('admin.system.run-maintenance') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task" value="cancel_overdue_tasks">
                            <button type="submit" class="neo-btn w-100"
                                {{ $scheduledStats['overdue'] > 0 ? '' : 'disabled' }}
                                onclick="return confirm('Are you sure you want to cancel all overdue scheduled tasks?')">
                                <i class="fas fa-times-circle me-2"></i> Cancel Overdue Tasks
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="neo-card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Danger Zone</h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="alert alert-danger" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                        <i class="fas fa-exclamation-circle me-2"></i> These actions can disrupt service and should only be performed during maintenance windows.
                    </div>

                    <div class="list-group-item-neo mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0"><i class="fas fa-power-off me-2"></i> Maintenance Mode</h6>
                            <span class="badge {{ app()->isDownForMaintenance() ? 'bg-danger' : 'bg-success' }}" style="border: 1px solid #121212;">
                                {{ app()->isDownForMaintenance() ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        <p class="text-muted mb-3 small">
                            {{ app()->isDownForMaintenance()
                                ? 'The site is currently in maintenance mode. All requests will see a maintenance page.'
                                : 'Enable maintenance mode to prevent users from accessing the site during maintenance.' }}
                        </p>
                        <form action="{{ route('admin.system.update-settings') }}" method="POST">
                            @csrf
                            <input type="hidden" name="app_name" value="{{ config('app.name') }}">
                            <input type="hidden" name="max_storage_days" value="{{ config('app.max_storage_days', 7) }}">

                            @if(config('app.youtube_enabled', true))
                                <input type="hidden" name="youtube_enabled" value="1">
                            @endif

                            @if(config('app.tiktok_enabled', true))
                                <input type="hidden" name="tiktok_enabled" value="1">
                            @endif

                            @if(config('app.instagram_enabled', true))
                                <input type="hidden" name="instagram_enabled" value="1">
                            @endif

                            @if(!app()->isDownForMaintenance())
                                <input type="hidden" name="maintenance_mode" value="1">
                                <button type="submit" class="neo-btn btn-danger w-100" onclick="return confirm('Are you sure you want to enable maintenance mode? This will make the site inaccessible to users.')">
                                    <i class="fas fa-power-off me-2"></i> Enable Maintenance Mode
                                </button>
                            @else
                                <button type="submit" class="neo-btn btn-success w-100" onclick="return confirm('Are you sure you want to disable maintenance mode? This will make the site accessible again.')">
                                    <i class="fas fa-power-off me-2"></i> Disable Maintenance Mode
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .neo-card {
        border: 3px solid #212529;
        border-radius: 8px;
        box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
        background: #ffffff;
        overflow: hidden;
    }

    .neo-card .card-header {
        border-bottom: 2px solid #212529;
        padding: 1rem;
    }

    .neo-btn {
        display: inline-block;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border: 2px solid #212529;
        border-radius: 0.375rem;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.2);
        transition: transform 0.1s, box-shadow 0.1s;
        cursor: pointer;
        text-decoration: none;
        color: #212529;
    }

    .neo-btn:hover {
        transform: translate(-1px, -1px);
        box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2);
    }

    .neo-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 2px 2px 0 rgba(0, 0, 0, 0.2);
    }

    .neo-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .neo-btn.btn-secondary {
        background: #f8f9fa;
    }

    .neo-btn.btn-info {
        background: linear-gradient(90deg, #a1c4fd 0%, #c2e9fb 100%);
    }


    .neo-btn.btn-success {
        background: linear-gradient(90deg, #84fab0 0%, #8fd3f4 100%);
    }

    .list-group-item-neo {
        padding: 1rem;
        margin-bottom: 1rem;
        border: 2px solid #212529;
        border-radius: 8px;
        background-color: #ffffff;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
