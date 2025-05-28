@extends('layouts.admin')

@section('title', 'Schedule Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <h1 class="mb-0 me-3">Schedule Details</h1>
        <span class="badge bg-primary fs-6 px-3 py-2" style="border: 3px solid var(--secondary);">
            ID: {{ $schedule->id }}
        </span>
    </div>
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
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i> Schedule Information
                </h5>
                <span class="status-badge status-{{ $schedule->status }}">
                    @switch($schedule->status)
                        @case('scheduled')
                            <i class="fas fa-calendar-check me-1"></i>
                            @break
                        @case('processing')
                            <i class="fas fa-spinner fa-spin me-1"></i>
                            @break
                        @case('completed')
                            <i class="fas fa-check-circle me-1"></i>
                            @break
                        @case('failed')
                            <i class="fas fa-times-circle me-1"></i>
                            @break
                        @case('cancelled')
                            <i class="fas fa-ban me-1"></i>
                            @break
                    @endswitch
                    {{ ucfirst($schedule->status) }}
                </span>
            </div>
            <div class="card-body">
                <!-- Countdown or Status Display -->
                @if($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture())
                    <div class="countdown-container mb-4 p-4 text-center" style="border: 3px solid var(--secondary); border-radius: 8px; background: linear-gradient(45deg, var(--primary-gradient-start), var(--primary-gradient-end)); color: white; box-shadow: 6px 6px 0 var(--shadow-color);">
                        <h5 class="mb-3">
                            <i class="fas fa-clock me-2"></i> Scheduled to run in:
                        </h5>
                        <div class="countdown-timer" data-time="{{ $schedule->scheduled_for->timestamp }}">
                            <div class="d-flex justify-content-center">
                                <div class="time-unit px-3">
                                    <span class="time-value days fw-bold" style="font-size: 1.8rem;">--</span>
                                    <span class="time-label d-block">Days</span>
                                </div>
                                <div class="time-unit px-3">
                                    <span class="time-value hours fw-bold" style="font-size: 1.8rem;">--</span>
                                    <span class="time-label d-block">Hours</span>
                                </div>
                                <div class="time-unit px-3">
                                    <span class="time-value minutes fw-bold" style="font-size: 1.8rem;">--</span>
                                    <span class="time-label d-block">Minutes</span>
                                </div>
                                <div class="time-unit px-3">
                                    <span class="time-value seconds fw-bold" style="font-size: 1.8rem;">--</span>
                                    <span class="time-label d-block">Seconds</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($schedule->status == 'scheduled' && $schedule->scheduled_for->isPast())
                    <div class="status-alert overdue mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-3x me-3"></i>
                            <div>
                                <h5 class="alert-heading">Schedule Overdue</h5>
                                <p class="mb-0">This download was scheduled for {{ $schedule->scheduled_for->format('M d, Y H:i:s') }} but hasn't started processing yet.</p>
                            </div>
                        </div>
                    </div>
                @elseif($schedule->status == 'processing')
                    <div class="status-alert processing mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-spinner fa-spin fa-3x me-3"></i>
                            <div>
                                <h5 class="alert-heading">Currently Processing</h5>
                                <p class="mb-0">This scheduled download is currently being processed. The download details will be available once complete.</p>
                            </div>
                        </div>
                    </div>
                @elseif($schedule->status == 'completed')
                    <div class="status-alert completed mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-3x me-3"></i>
                            <div>
                                <h5 class="alert-heading">Schedule Completed</h5>
                                <p class="mb-0">This scheduled download was successfully processed on {{ $schedule->completed_at->format('M d, Y H:i:s') }}.</p>
                            </div>
                        </div>
                    </div>
                @elseif($schedule->status == 'failed')
                    <div class="status-alert failed mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-3x me-3"></i>
                            <div>
                                <h5 class="alert-heading">Schedule Failed</h5>
                                <p class="mb-0">{{ $schedule->error_message ?? 'This scheduled download failed to process.' }}</p>
                            </div>
                        </div>
                    </div>
                @elseif($schedule->status == 'cancelled')
                    <div class="status-alert cancelled mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-ban fa-3x me-3"></i>
                            <div>
                                <h5 class="alert-heading">Schedule Cancelled</h5>
                                <p class="mb-0">This scheduled download was cancelled and will not be processed.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Schedule Details Table -->
                <div class="table-responsive">
                    <table class="table table-bordered admin-details-table">
                        <tbody>
                            <tr>
                                <th class="table-label">Title</th>
                                <td>{{ $schedule->title ?? 'Untitled' }}</td>
                            </tr>
                            <tr>
                                <th class="table-label">URL</th>
                                <td>
                                    <a href="{{ $schedule->url }}" target="_blank" class="text-break text-decoration-none">
                                        {{ $schedule->url }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-label">Platform</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($schedule->platform == 'youtube')
                                            <span class="platform-badge youtube">
                                                <i class="fab fa-youtube"></i>
                                            </span>
                                            <span class="fw-bold">YouTube</span>
                                        @elseif($schedule->platform == 'tiktok')
                                            <span class="platform-badge tiktok">
                                                <i class="fab fa-tiktok"></i>
                                            </span>
                                            <span class="fw-bold">TikTok</span>
                                        @elseif($schedule->platform == 'instagram')
                                            <span class="platform-badge instagram">
                                                <i class="fab fa-instagram"></i>
                                            </span>
                                            <span class="fw-bold">Instagram</span>
                                        @else
                                            <span class="platform-badge other">
                                                <i class="fas fa-link"></i>
                                            </span>
                                            <span class="fw-bold">{{ ucfirst($schedule->platform) }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-label">Format</th>
                                <td>
                                    @if(strpos($schedule->format, 'mp4') !== false)
                                        <span class="badge bg-primary format-badge">
                                            <i class="fas fa-video me-1"></i> MP4
                                        </span>
                                        @if($schedule->quality)
                                            <small class="text-muted ms-2">{{ $schedule->quality }}</small>
                                        @endif
                                    @elseif(strpos($schedule->format, 'mp3') !== false)
                                        <span class="badge bg-success format-badge">
                                            <i class="fas fa-music me-1"></i> MP3
                                        </span>
                                    @else
                                        <span class="badge bg-secondary format-badge">
                                            <i class="fas fa-file me-1"></i> {{ strtoupper($schedule->format) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="table-label">Scheduled For</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @php
                                            $scheduledFor = $schedule->scheduled_for;
                                            $isPast = $scheduledFor->isPast();
                                            $isComing = $scheduledFor->isFuture() && $scheduledFor->diffInHours() < 24;
                                        @endphp

                                        @if($schedule->status == 'scheduled' && $isComing)
                                            <i class="fas fa-clock text-warning me-2"></i>
                                        @elseif($schedule->status == 'scheduled' && $isPast)
                                            <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                        @else
                                            <i class="far fa-calendar-alt text-primary me-2"></i>
                                        @endif

                                        <div>
                                            <div class="fw-bold">{{ $scheduledFor->format('F d, Y H:i:s') }}</div>
                                            @if($schedule->status == 'scheduled')
                                                <small class="text-muted">
                                                    @if($isPast)
                                                        <span class="text-danger">Overdue by {{ $scheduledFor->diffForHumans() }}</span>
                                                    @else
                                                        {{ $scheduledFor->diffForHumans() }}
                                                    @endif
                                                </small>
                                            @endif
                                        </div>

                                        @if($schedule->status == 'scheduled')
                                            @if($isPast)
                                                <span class="badge bg-danger ms-2">Overdue</span>
                                            @elseif($isComing)
                                                <span class="badge bg-warning text-dark ms-2">Coming Soon</span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-label">Token Cost</th>
                                <td>
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2" style="border: 2px solid var(--secondary);">
                                        <i class="fas fa-coins me-1"></i> {{ $schedule->token_cost ?? '0' }} tokens
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-label">Created</th>
                                <td>{{ $schedule->created_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th class="table-label">Updated</th>
                                <td>{{ $schedule->updated_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                            @if($schedule->completed_at)
                            <tr>
                                <th class="table-label">Completed</th>
                                <td>{{ $schedule->completed_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                            @endif
                            @if($schedule->status == 'failed' && $schedule->error_message)
                            <tr>
                                <th class="table-label">Error Message</th>
                                <td class="text-danger fw-bold">{{ $schedule->error_message }}</td>
                            </tr>
                            @endif
                            @if($schedule->job_id)
                            <tr>
                                <th class="table-label">Job ID</th>
                                <td><code class="bg-light p-2 rounded">{{ $schedule->job_id }}</code></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- System Details Card (for admins) -->
                <div class="system-details mt-4">
                    <h5 class="mb-3">
                        <i class="fas fa-server me-2"></i> System Details
                    </h5>
                    <div class="p-3" style="border: 3px solid var(--secondary); border-radius: 8px; background-color: var(--light); box-shadow: 4px 4px 0 var(--shadow-color);">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="detail-item mb-2">
                                    <span class="fw-bold text-muted">IP Address:</span>
                                    <div>{{ $schedule->ip_address ?? 'Not recorded' }}</div>
                                </div>
                                <div class="detail-item">
                                    <span class="fw-bold text-muted">User Agent:</span>
                                    <div class="text-truncate" style="max-width: 100%;">
                                        {{ $schedule->user_agent ?? 'Not recorded' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item mb-2">
                                    <span class="fw-bold text-muted">Queue Name:</span>
                                    <div>{{ $schedule->queue ?? 'scheduled' }}</div>
                                </div>
                                <div class="detail-item">
                                    <span class="fw-bold text-muted">Scheduling Method:</span>
                                    <div>{{ $schedule->method ?? 'Laravel Scheduler' }}</div>
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
                    <h5 class="mb-0">
                        <i class="fas fa-download me-2"></i> Related Download
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">{{ $download->title ?? 'Downloaded Media' }}</h6>
                        <span class="status-badge status-{{ $download->status }}">
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
                                <span class="badge bg-warning text-dark" style="border: 1px solid var(--secondary);">
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
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i> Activity Log
                </h5>
            </div>
            <div class="card-body p-0">
                @if(isset($activities) && count($activities) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
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
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i> User Information
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($schedule->user->name) }}&size=64&background=2B7EC1&color=fff"
                        class="rounded-circle me-3" alt="{{ $schedule->user->name }}"
                        style="border: 3px solid var(--secondary); width: 64px; height: 64px;">
                    <div>
                        <h5 class="mb-1">{{ $schedule->user->name }}</h5>
                        <p class="mb-0 text-muted">{{ $schedule->user->email }}</p>
                    </div>
                </div>

                <div class="user-stats mb-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="stats-mini">
                                <div class="stat-value">{{ $userStats['total_downloads'] ?? '0' }}</div>
                                <div class="stat-label">Total Downloads</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-mini">
                                <div class="stat-value">{{ $userStats['scheduled_downloads'] ?? '0' }}</div>
                                <div class="stat-label">Total Schedules</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.show', $schedule->user_id) }}" class="neo-btn">
                        <i class="fas fa-user me-2"></i> View User Profile
                    </a>
                    <a href="{{ route('admin.users.downloads', $schedule->user_id) }}" class="neo-btn btn-secondary">
                        <i class="fas fa-download me-2"></i> User's Downloads
                    </a>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="neo-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i> Admin Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    @if($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture())
                        <a href="{{ route('schedules.edit', $schedule) }}" class="neo-btn">
                            <i class="fas fa-edit me-2"></i> Edit Schedule
                        </a>

                        <button type="button" class="neo-btn btn-warning" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="fas fa-ban me-2"></i> Cancel Schedule
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
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i> User's Other Schedules
                </h5>
            </div>
            <div class="card-body p-0">
                @if(isset($otherSchedules) && count($otherSchedules) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($otherSchedules as $otherSchedule)
                            @if($otherSchedule->id != $schedule->id)
                                <a href="{{ route('admin.schedules.show', $otherSchedule) }}" class="list-group-item list-group-item-action border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($otherSchedule->platform == 'youtube')
                                                <i class="fab fa-youtube text-danger"></i>
                                            @elseif($otherSchedule->platform == 'tiktok')
                                                <i class="fab fa-tiktok"></i>
                                            @elseif($otherSchedule->platform == 'instagram')
                                                <i class="fab fa-instagram" style="color: #e4405f;"></i>
                                            @else
                                                <i class="fas fa-link text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <div class="text-truncate" style="max-width: 180px;">
                                                    {{ $otherSchedule->title ?? 'Untitled' }}
                                                </div>
                                                <small class="text-muted">{{ $otherSchedule->scheduled_for->format('M d') }}</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <small class="text-muted">{{ $otherSchedule->scheduled_for->format('H:i') }}</small>
                                                <span class="status-badge-mini status-{{ $otherSchedule->status }}">
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

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 8px 8px 0 var(--shadow-color);">
            <div class="modal-header bg-warning text-dark" style="border-bottom: 3px solid var(--secondary);">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="fas fa-ban me-2"></i> Cancel Schedule
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this scheduled download?</p>
                <div class="alert alert-warning" style="border: 2px solid var(--warning); border-radius: 8px; box-shadow: 3px 3px 0 rgba(255, 202, 40, 0.2);">
                    <i class="fas fa-exclamation-triangle me-2"></i> This will prevent the scheduled download from being processed at the scheduled time.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 3px solid var(--secondary);">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Keep Schedule</button>
                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="neo-btn btn-warning">Cancel Schedule</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 8px 8px 0 var(--shadow-color);">
            <div class="modal-header bg-danger text-white" style="border-bottom: 3px solid var(--secondary);">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-trash me-2"></i> Delete Schedule
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete this scheduled download?</p>
                <div class="alert alert-danger" style="border: 2px solid var(--danger); border-radius: 8px; box-shadow: 3px 3px 0 rgba(255, 44, 85, 0.2);">
                    <i class="fas fa-exclamation-triangle me-2"></i> This action cannot be undone. The schedule will be permanently removed from the system.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 3px solid var(--secondary);">
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
/* Platform Badges */
.platform-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: 2px solid var(--secondary);
    box-shadow: 2px 2px 0 rgba(0, 0, 0, 0.1);
    color: white;
    font-size: 14px;
    margin-right: 8px;
}

.platform-badge.youtube { background: #ff0000; }
.platform-badge.tiktok { background: #000000; }
.platform-badge.instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
.platform-badge.other { background: #6c757d; }

/* Format Badges */
.format-badge {
    border: 2px solid var(--secondary) !important;
    font-weight: 600;
    padding: 4px 8px;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    border: 2px solid var(--secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-scheduled { background: var(--primary); color: white; }
.status-processing { background: var(--warning); color: var(--secondary); }
.status-completed { background: var(--success); color: white; }
.status-failed { background: var(--danger); color: white; }
.status-cancelled { background: #6c757d; color: white; }

/* Mini Status Badge */
.status-badge-mini {
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    border: 1px solid var(--secondary);
    text-transform: uppercase;
    font-weight: 600;
}

/* Status Alerts */
.status-alert {
    border: 3px solid var(--secondary);
    border-radius: 8px;
    padding: 20px;
    box-shadow: 6px 6px 0 var(--shadow-color);
}

.status-alert.overdue {
    background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
    color: white;
}

.status-alert.processing {
    background: linear-gradient(45deg, var(--info), #7dd3fc);
    color: white;
}

.status-alert.completed {
    background: linear-gradient(45deg, var(--success), #4ade80);
    color: white;
}

.status-alert.failed {
    background: linear-gradient(45deg, var(--danger), #f87171);
    color: white;
}

.status-alert.cancelled {
    background: linear-gradient(45deg, #6b7280, #9ca3af);
    color: white;
}

/* Table Styles */
.admin-details-table {
    border: 3px solid var(--secondary);
    border-radius: 8px;
    overflow: hidden;
}

.admin-details-table .table-label {
    width: 150px;
    background-color: var(--light-gray);
    font-weight: 700;
    border-right: 2px solid var(--secondary);
}

.admin-details-table td {
    padding: 12px 15px;
}

.admin-details-table tr {
    border-bottom: 1px solid #dee2e6;
}

/* Stats Mini */
.stats-mini {
    padding: 15px;
    text-align: center;
    border: 2px solid var(--secondary);
    border-radius: 8px;
    background: white;
    box-shadow: 3px 3px 0 var(--shadow-color);
}

.stats-mini .stat-value {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary);
}

.stats-mini .stat-label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
}

/* Countdown Timer */
.time-unit {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    border: 2px solid white;
    margin: 0 5px;
}

/* Custom Button Variants */
.neo-btn.btn-warning {
    background: var(--warning);
    color: var(--secondary);
}

.neo-btn.btn-danger {
    background: var(--danger);
    color: white;
}

.neo-btn.btn-success {
    background: var(--success);
    color: white;
}

/* List Group Items */
.list-group-item-action:hover {
    background-color: var(--light-gray);
    transform: translateX(5px);
    transition: all 0.2s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-details-table .table-label {
        width: 120px;
        font-size: 12px;
    }

    .stats-mini {
        padding: 10px;
    }

    .stats-mini .stat-value {
        font-size: 16px;
    }

    .time-unit {
        margin: 0 2px;
        padding: 0 10px;
    }

    .time-value {
        font-size: 1.2rem !important;
    }
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

    // Auto-refresh based on schedule status
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
