<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\schedules\show.blade.php -->
@extends('layouts.admin')

@section('title', 'Schedule Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Schedule Details <span class="badge bg-primary" style="font-size: 16px; border: 2px solid #212529;">ID: {{ $schedule->id }}</span></h1>
        <div>
            <a href="{{ route('admin.schedules.index') }}" class="neo-btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Schedules
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Details -->
        <div class="col-lg-8">
            <div class="neo-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Schedule Information</h5>
                    <span class="badge rounded-pill
                        @if($schedule->status == 'scheduled') bg-primary
                        @elseif($schedule->status == 'processing') bg-warning
                        @elseif($schedule->status == 'completed') bg-success
                        @elseif($schedule->status == 'cancelled') bg-secondary
                        @elseif($schedule->status == 'failed') bg-danger
                        @else bg-info @endif"
                        style="border: 2px solid #212529; font-size: 14px; padding: 8px 12px;">
                        {{ ucfirst($schedule->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Countdown or Status Display -->
                    @if($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture())
                        <div class="countdown-container mb-4 p-3 text-center" style="border: 3px solid #212529; border-radius: 8px; background: linear-gradient(45deg, #ff9a9e, #fad0c4);">
                            <h5>Scheduled to run in:</h5>
                            <div class="countdown-timer" data-time="{{ $schedule->scheduled_for->timestamp }}">
                                <div class="d-flex justify-content-center">
                                    <div class="time-unit px-3">
                                        <span class="time-value days fw-bold" style="font-size: 1.5rem;">--</span>
                                        <span class="time-label d-block">Days</span>
                                    </div>
                                    <div class="time-unit px-3">
                                        <span class="time-value hours fw-bold" style="font-size: 1.5rem;">--</span>
                                        <span class="time-label d-block">Hours</span>
                                    </div>
                                    <div class="time-unit px-3">
                                        <span class="time-value minutes fw-bold" style="font-size: 1.5rem;">--</span>
                                        <span class="time-label d-block">Minutes</span>
                                    </div>
                                    <div class="time-unit px-3">
                                        <span class="time-value seconds fw-bold" style="font-size: 1.5rem;">--</span>
                                        <span class="time-label d-block">Seconds</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($schedule->status == 'scheduled' && $schedule->scheduled_for->isPast())
                        <div class="alert alert-warning mb-4" style="border: 3px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading">Schedule Overdue</h5>
                                    <p class="mb-0">This download was scheduled for {{ $schedule->scheduled_for->format('M d, Y H:i:s') }} but hasn't started processing yet. There might be a delay in the queue system.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($schedule->status == 'processing')
                        <div class="alert alert-info mb-4" style="border: 3px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-spinner fa-spin fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading">Currently Processing</h5>
                                    <p class="mb-0">This scheduled download is currently being processed. The download details will be available once complete.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($schedule->status == 'completed')
                        <div class="alert alert-success mb-4" style="border: 3px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading">Schedule Completed</h5>
                                    <p class="mb-0">This scheduled download was successfully processed on {{ $schedule->completed_at->format('M d, Y H:i:s') }}.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($schedule->status == 'failed')
                        <div class="alert alert-danger mb-4" style="border: 3px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading">Schedule Failed</h5>
                                    <p class="mb-0">This scheduled download failed to process. {{ $schedule->error_message ?? 'No specific error message was recorded.' }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($schedule->status == 'cancelled')
                        <div class="alert alert-secondary mb-4" style="border: 3px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-ban fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading">Schedule Cancelled</h5>
                                    <p class="mb-0">This scheduled download was cancelled and will not be processed.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Schedule Details Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border: 2px solid #212529;">
                            <tbody>
                                <tr>
                                    <th style="width: 150px; background-color: #f8f9fa;">Title</th>
                                    <td>{{ $schedule->title ?? 'Untitled' }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">URL</th>
                                    <td>
                                        <a href="{{ $schedule->url }}" target="_blank" class="text-break">
                                            {{ $schedule->url }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Platform</th>
                                    <td>
                                        @if($schedule->platform == 'youtube')
                                            <span class="platform-badge youtube">
                                                <i class="fab fa-youtube"></i>
                                            </span>
                                            YouTube
                                        @elseif($schedule->platform == 'tiktok')
                                            <span class="platform-badge tiktok">
                                                <i class="fab fa-tiktok"></i>
                                            </span>
                                            TikTok
                                        @elseif($schedule->platform == 'instagram')
                                            <span class="platform-badge instagram">
                                                <i class="fab fa-instagram"></i>
                                            </span>
                                            Instagram
                                        @else
                                            <span class="platform-badge other">
                                                <i class="fas fa-link"></i>
                                            </span>
                                            {{ ucfirst($schedule->platform) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Format</th>
                                    <td>
                                        @if(strpos($schedule->format, 'mp4') !== false)
                                            <span class="badge bg-primary" style="border: 1px solid #212529;">MP4</span>
                                            <small>{{ $schedule->quality }}</small>
                                        @elseif(strpos($schedule->format, 'mp3') !== false)
                                            <span class="badge bg-success" style="border: 1px solid #212529;">MP3</span>
                                        @else
                                            <span class="badge bg-secondary" style="border: 1px solid #212529;">{{ strtoupper($schedule->format) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Scheduled For</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-calendar-alt me-2 text-primary"></i>
                                            <span>{{ $schedule->scheduled_for->format('F d, Y H:i:s') }}</span>
                                            @if($schedule->status == 'scheduled')
                                                @if($schedule->scheduled_for->isPast())
                                                    <span class="badge bg-danger ms-2" style="border: 1px solid #212529;">
                                                        Overdue
                                                    </span>
                                                @elseif($schedule->scheduled_for->diffInHours(now()) < 24)
                                                    <span class="badge bg-warning ms-2" style="border: 1px solid #212529;">
                                                        Coming Soon
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Created</th>
                                    <td>{{ $schedule->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Updated</th>
                                    <td>{{ $schedule->updated_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                @if($schedule->completed_at)
                                <tr>
                                    <th style="background-color: #f8f9fa;">Completed</th>
                                    <td>{{ $schedule->completed_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th style="background-color: #f8f9fa;">Token Cost</th>
                                    <td>
                                        <span class="badge bg-warning" style="border: 1px solid #212529; font-size: 14px;">
                                            {{ $schedule->token_cost ?? '0' }} tokens
                                        </span>
                                    </td>
                                </tr>
                                @if($schedule->status == 'failed' && $schedule->error_message)
                                <tr>
                                    <th style="background-color: #f8f9fa;">Error Message</th>
                                    <td class="text-danger">{{ $schedule->error_message }}</td>
                                </tr>
                                @endif
                                @if($schedule->job_id)
                                <tr>
                                    <th style="background-color: #f8f9fa;">Job ID</th>
                                    <td><code>{{ $schedule->job_id }}</code></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- System Details Card (for admins) -->
                    <div class="system-details mt-4">
                        <h5 class="mb-3">System Details</h5>
                        <div class="p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="detail-item mb-2">
                                        <span class="fw-bold">IP Address:</span>
                                        {{ $schedule->ip_address ?? 'Not recorded' }}
                                    </div>
                                    <div class="detail-item">
                                        <span class="fw-bold">User Agent:</span>
                                        <span class="text-truncate d-block" style="max-width: 100%;">
                                            {{ $schedule->user_agent ?? 'Not recorded' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-item mb-2">
                                        <span class="fw-bold">Queue Name:</span>
                                        {{ $schedule->queue ?? 'scheduled' }}
                                    </div>
                                    <div class="detail-item">
                                        <span class="fw-bold">Scheduling Method:</span>
                                        {{ $schedule->method ?? 'Laravel Scheduler' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Download (if exists) -->
            @if(isset($download) && $download)
                <div class="neo-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Related Download</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">{{ $download->title ?? 'Downloaded Media' }}</h6>
                            <span class="badge rounded-pill
                                @if($download->status == 'pending') bg-info
                                @elseif($download->status == 'processing') bg-warning
                                @elseif($download->status == 'completed') bg-success
                                @elseif($download->status == 'cancelled') bg-secondary
                                @elseif($download->status == 'failed') bg-danger
                                @else bg-info @endif"
                                style="border: 2px solid #212529;">
                                {{ ucfirst($download->status) }}
                            </span>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="detail-item mb-2">
                                    <span class="fw-bold">Format:</span>
                                    {{ strtoupper($download->format) }}
                                    @if($download->quality)
                                        <small>({{ $download->quality }})</small>
                                    @endif
                                </div>
                                <div class="detail-item">
                                    <span class="fw-bold">Size:</span>
                                    @if($download->file_size)
                                        {{ round($download->file_size / (1024 * 1024), 2) }} MB
                                    @else
                                        Unknown
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item mb-2">
                                    <span class="fw-bold">Processed On:</span>
                                    {{ $download->created_at->format('d M Y, h:i A') }}
                                </div>
                                <div class="detail-item">
                                    <span class="fw-bold">Tokens Used:</span>
                                    <span class="badge bg-warning" style="border: 1px solid #212529;">
                                        {{ $download->token_cost ?? '0' }} tokens
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.downloads.show', $download) }}" class="neo-btn">
                                <i class="fas fa-eye me-1"></i> View Download Details
                            </a>

                            @if($download->status == 'completed')
                                <a href="{{ route('downloads.file', $download) }}" class="neo-btn btn-success">
                                    <i class="fas fa-download me-1"></i> Download File
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Activity Log -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Activity Log</h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($activities) && count($activities) > 0)
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Details</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activities as $activity)
                                        <tr>
                                            <td>
                                                @php
                                                    $actionClass = match(true) {
                                                        str_contains($activity->action, 'created') => 'text-primary',
                                                        str_contains($activity->action, 'updated') => 'text-info',
                                                        str_contains($activity->action, 'completed') => 'text-success',
                                                        str_contains($activity->action, 'started') => 'text-warning',
                                                        str_contains($activity->action, 'fail') || str_contains($activity->action, 'error') => 'text-danger',
                                                        str_contains($activity->action, 'cancel') => 'text-secondary',
                                                        default => ''
                                                    }
                                                @endphp
                                                <span class="{{ $actionClass }} fw-bold">
                                                    {{ str_replace('_', ' ', ucwords($activity->action)) }}
                                                </span>
                                            </td>
                                            <td>{{ $activity->details }}</td>
                                            <td>{{ $activity->created_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <i class="fas fa-history fa-2x mb-3 text-muted"></i>
                            <p class="mb-0">No activity logs available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- User Card -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($schedule->user->name) }}&size=64&background=ff4b2b&color=fff"
                            class="rounded-circle me-3" alt="{{ $schedule->user->name }}"
                            style="border: 3px solid #212529; width: 64px; height: 64px;">
                        <div>
                            <h5 class="mb-1">{{ $schedule->user->name }}</h5>
                            <p class="mb-0">{{ $schedule->user->email }}</p>
                        </div>
                    </div>

                    <div class="user-stats mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="p-2 text-center" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                                    <h6 class="mb-0">{{ $userStats['total_downloads'] ?? '0' }}</h6>
                                    <small>Total Downloads</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 text-center" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                                    <h6 class="mb-0">{{ $userStats['scheduled_downloads'] ?? '0' }}</h6>
                                    <small>Total Schedules</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.show', $schedule->user_id) }}" class="neo-btn">
                            <i class="fas fa-user me-2"></i> View User Profile
                        </a>
                        <a href="{{ route('admin.users.downloads', $schedule->user_id) }}" class="neo-btn btn-secondary">
                            <i class="fas fa-download me-2"></i> View All User's Downloads
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Admin Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        @if($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture())
                            <a href="{{ route('schedules.edit', $schedule) }}" class="neo-btn">
                                <i class="fas fa-calendar-alt me-2"></i> Edit Schedule
                            </a>

                            <button type="button" class="neo-btn btn-warning" data-bs-toggle="modal" data-bs-target="#executeNowModal">
                                <i class="fas fa-play-circle me-2"></i> Execute Now
                            </button>

                            <button type="button" class="neo-btn btn-secondary" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="fas fa-ban me-2"></i> Cancel Schedule
                            </button>
                        @endif

                        @if($schedule->status == 'failed')
                            <button type="button" class="neo-btn" data-bs-toggle="modal" data-bs-target="#retryModal">
                                <i class="fas fa-redo me-2"></i> Retry Schedule
                            </button>
                        @endif

                        <form action="{{ route('admin.schedules.delete', $schedule) }}" method="POST" id="deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="neo-btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-2"></i> Delete Schedule
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Other Schedules Card -->
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">User's Other Schedules</h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($otherSchedules) && count($otherSchedules) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($otherSchedules as $otherSchedule)
                                @if($otherSchedule->id != $schedule->id)
                                    <a href="{{ route('admin.schedules.show', $otherSchedule) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($otherSchedule->platform == 'youtube')
                                                    <i class="fab fa-youtube text-danger"></i>
                                                @elseif($otherSchedule->platform == 'tiktok')
                                                    <i class="fab fa-tiktok"></i>
                                                @elseif($otherSchedule->platform == 'instagram')
                                                    <i class="fab fa-instagram text-purple"></i>
                                                @else
                                                    <i class="fas fa-link"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <div class="text-truncate" style="max-width: 180px;">
                                                        {{ $otherSchedule->title ?? 'Untitled' }}
                                                    </div>
                                                    <small class="text-muted">{{ $otherSchedule->scheduled_for->format('M d') }}</small>
                                                </div>
                                                <div>
                                                    <small>{{ $otherSchedule->scheduled_for->format('H:i') }}</small>
                                                    <span class="badge rounded-pill
                                                        @if($otherSchedule->status == 'scheduled') bg-primary
                                                        @elseif($otherSchedule->status == 'processing') bg-warning
                                                        @elseif($otherSchedule->status == 'completed') bg-success
                                                        @elseif($otherSchedule->status == 'cancelled') bg-secondary
                                                        @elseif($otherSchedule->status == 'failed') bg-danger
                                                        @else bg-info @endif"
                                                        style="border: 1px solid #212529; font-size: 10px;">
                                                        {{ ucfirst($otherSchedule->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <i class="fas fa-calendar-alt fa-2x mb-3 text-muted"></i>
                            <p class="mb-0">No other schedules found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Execute Now Modal -->
<div class="modal fade" id="executeNowModal" tabindex="-1" aria-labelledby="executeNowModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title" id="executeNowModalLabel">Execute Schedule Now</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to execute this schedule immediately?</p>
                <div class="alert alert-info" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                    <i class="fas fa-info-circle me-2"></i> This will override the scheduled time and start processing the download immediately.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #212529;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('schedules.execute', $schedule) }}" method="POST">
                    @csrf
                    <button type="submit" class="neo-btn">Execute Now</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this scheduled download?</p>
                <div class="alert alert-warning" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                    <i class="fas fa-exclamation-triangle me-2"></i> This will prevent the scheduled download from being processed at the scheduled time.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #212529;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Keep Schedule</button>
                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="neo-btn btn-warning">Cancel Schedule</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Retry Modal -->
<div class="modal fade" id="retryModal" tabindex="-1" aria-labelledby="retryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title" id="retryModalLabel">Retry Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to retry this failed schedule?</p>
                <div class="alert alert-info" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                    <i class="fas fa-info-circle me-2"></i> This will create a new schedule with the same settings and execute it immediately.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #212529;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('schedules.retry', $schedule) }}" method="POST">
                    @csrf
                    <button type="submit" class="neo-btn">Retry Now</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title" id="deleteModalLabel">Delete Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete this scheduled download?</p>
                <div class="alert alert-danger" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                    <i class="fas fa-exclamation-triangle me-2"></i> This action cannot be undone. The schedule will be permanently removed from the system.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #212529;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="neo-btn btn-danger" onclick="document.getElementById('deleteForm').submit();">
                    Delete Permanently
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .platform-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 4px;
        border: 1px solid #121212;
        box-shadow: 2px 2px 0 rgba(0,0,0,0.1);
        color: white;
        font-size: 12px;
        margin-right: 6px;
    }

    .platform-badge.youtube {
        background-color: #ff0000;
    }

    .platform-badge.tiktok {
        background-color: #000000;
    }

    .platform-badge.instagram {
        background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
    }

    .platform-badge.other {
        background-color: #6c757d;
    }

    .neo-card {
        border: 3px solid #212529;
        border-radius: 8px;
        box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
        background: #ffffff;
        overflow: hidden;
    }

    .neo-card .card-header {
        background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%);
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
        background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%);
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

    .neo-btn.btn-secondary {
        background: #f8f9fa;
    }

    .neo-btn.btn-warning {
        background: linear-gradient(90deg, #f6d365 0%, #fda085 100%);
    }

    .neo-btn.btn-success {
        background: linear-gradient(90deg, #a8e063 0%, #56ab2f 100%);
    }

    .neo-btn.btn-danger {
        background: linear-gradient(90deg, #ff6b6b 0%, #ff8e8e 100%);
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        border-bottom: 2px solid #212529;
        background-color: #f8f9fa;
    }

    .table td {
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
    // Countdown timer functionality
    function updateCountdown() {
        const countdownElement = document.querySelector('.countdown-timer');
        if (!countdownElement) return;

        const targetTime = parseInt(countdownElement.dataset.time) * 1000;
        const now = new Date().getTime();
        const difference = targetTime - now;

        if (difference < 0) {
            document.querySelector('.days').innerHTML = '0';
            document.querySelector('.hours').innerHTML = '0';
            document.querySelector('.minutes').innerHTML = '0';
            document.querySelector('.seconds').innerHTML = '0';
            return;
        }

        // Calculate time components
        const days = Math.floor(difference / (1000 * 60 * 60 * 24));
        const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((difference % (1000 * 60)) / 1000);

        // Update the elements
        document.querySelector('.days').innerHTML = days;
        document.querySelector('.hours').innerHTML = hours;
        document.querySelector('.minutes').innerHTML = minutes;
        document.querySelector('.seconds').innerHTML = seconds;
    }

    // Initialize countdown and set interval
    document.addEventListener('DOMContentLoaded', function() {
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // Auto-refresh if scheduled time is close
        @if($schedule->status == 'scheduled' && $schedule->scheduled_for->diffInMinutes(now()) < 5)
            setTimeout(function() {
                window.location.reload();
            }, 60000); // Refresh every minute if scheduled time is within 5 minutes
        @elseif($schedule->status == 'processing')
            setTimeout(function() {
                window.location.reload();
            }, 30000); // Refresh every 30 seconds if processing
        @endif
    });
</script>
@endpush
