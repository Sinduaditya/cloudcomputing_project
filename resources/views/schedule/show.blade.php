<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\schedule\show.blade.php -->
@extends('layouts.app')

@section('title', $schedule->title ?? 'Scheduled Download')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Scheduled Download</h1>
        <div>
            <a href="{{ route('schedules.index') }}" class="neo-btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Schedules
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Content Card -->
            <div class="neo-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $schedule->title ?? 'Schedule Details' }}</h5>
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
                    <!-- Schedule Info -->
                    <div class="schedule-info mb-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="platform-icon me-3">
                                @if($schedule->platform == 'youtube')
                                    <span class="platform-badge youtube">
                                        <i class="fab fa-youtube fa-lg"></i>
                                    </span>
                                @elseif($schedule->platform == 'tiktok')
                                    <span class="platform-badge tiktok">
                                        <i class="fab fa-tiktok fa-lg"></i>
                                    </span>
                                @elseif($schedule->platform == 'instagram')
                                    <span class="platform-badge instagram">
                                        <i class="fab fa-instagram fa-lg"></i>
                                    </span>
                                @else
                                    <span class="platform-badge other">
                                        <i class="fas fa-link fa-lg"></i>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">{{ ucfirst($schedule->platform) }} Download</h5>
                                <div class="text-muted">
                                    <a href="{{ $schedule->url }}" target="_blank" class="text-decoration-none">
                                        {{ $schedule->url }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="schedule-details p-3 mb-4" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <h6 class="fw-bold mb-1">Format</h6>
                                        <div>
                                            @if($schedule->format == 'mp4')
                                                <span class="badge bg-primary" style="border: 1px solid #212529;">MP4</span>
                                                @if($schedule->quality)
                                                    <small class="ms-1">{{ $schedule->quality }}</small>
                                                @endif
                                            @elseif($schedule->format == 'mp3')
                                                <span class="badge bg-success" style="border: 1px solid #212529;">MP3</span>
                                            @else
                                                <span class="badge bg-secondary" style="border: 1px solid #212529;">{{ strtoupper($schedule->format) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <h6 class="fw-bold mb-1">Created</h6>
                                        <div>{{ $schedule->created_at->format('d M Y, h:i A') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <h6 class="fw-bold mb-1">Scheduled For</h6>
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-calendar-alt me-2 text-primary"></i>
                                            <span class="@if($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture()) fw-bold text-primary @endif">
                                                {{ $schedule->scheduled_for->format('d M Y, h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-item">
                                        <h6 class="fw-bold mb-1">Time Remaining</h6>
                                        <div>
                                            @if($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture())
                                                <div class="countdown-badge" style="display: inline-block; background: linear-gradient(45deg, #ff9a9e, #fad0c4); border: 2px solid #212529; padding: 4px 12px; border-radius: 20px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                                                    <i class="far fa-clock me-2"></i>
                                                    <span class="countdown" data-time="{{ $schedule->scheduled_for->timestamp }}">
                                                        {{ $schedule->scheduled_for->diffForHumans() }}
                                                    </span>
                                                </div>
                                            @elseif($schedule->status == 'completed')
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i> Completed
                                                </span>
                                            @elseif($schedule->status == 'cancelled')
                                                <span class="text-secondary">
                                                    <i class="fas fa-ban me-1"></i> Cancelled
                                                </span>
                                            @elseif($schedule->status == 'failed')
                                                <span class="text-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i> Failed
                                                </span>
                                            @elseif($schedule->status == 'processing')
                                                <span class="text-warning">
                                                    <i class="fas fa-spinner fa-spin me-1"></i> Processing
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-hourglass-end me-1"></i> Time has passed
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Message -->
                        @if($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture())
                            <div class="alert alert-primary" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);" role="alert">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Waiting for Scheduled Time</h5>
                                        <p class="mb-0">Your download will automatically start at the scheduled time. You'll receive a notification when it's complete.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($schedule->status == 'scheduled' && $schedule->scheduled_for->isPast())
                            <div class="alert alert-warning" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);" role="alert">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Download Will Begin Shortly</h5>
                                        <p class="mb-0">The scheduled time has passed. Your download should begin processing very soon.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($schedule->status == 'processing')
                            <div class="alert alert-warning" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);" role="alert">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Download In Progress</h5>
                                        <p class="mb-0">Your scheduled download is currently being processed. You'll receive a notification when it's complete.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($schedule->status == 'completed')
                            <div class="alert alert-success" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);" role="alert">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Download Complete</h5>
                                        <p class="mb-0">Your scheduled download has been successfully completed. You can access it now.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($schedule->status == 'failed')
                            <div class="alert alert-danger" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);" role="alert">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Download Failed</h5>
                                        <p class="mb-0">There was a problem with your scheduled download. Please try again or contact support if the issue persists.</p>
                                        @if($schedule->error_message)
                                            <hr>
                                            <p class="mb-0"><strong>Error:</strong> {{ $schedule->error_message }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif($schedule->status == 'cancelled')
                            <div class="alert alert-secondary" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);" role="alert">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-ban fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Download Cancelled</h5>
                                        <p class="mb-0">This scheduled download was cancelled. No tokens were deducted from your account.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Related Download (if exists) -->
                    @if($download)
                        <div class="related-download mt-4">
                            <h5 class="mb-3">Download Details</h5>
                            <div class="p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">{{ $download->title ?? 'Downloaded Media' }}</h6>
                                    <x-status-badge :status="$download->status" />
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

                                <div class="download-actions">
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('downloads.show', $download) }}" class="neo-btn btn-sm btn-secondary">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>

                                        @if($download->status == 'completed')
                                            <a href="{{ route('downloads.file', $download) }}" class="neo-btn btn-sm">
                                                <i class="fas fa-download me-1"></i> Download
                                            </a>

                                            <a href="{{ route('instance.show', $download) }}" class="neo-btn btn-sm btn-success">
                                                <i class="fas fa-play me-1"></i> Play Media
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        @if($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture())
                            <a href="{{ route('schedules.edit', $schedule) }}" class="neo-btn">
                                <i class="fas fa-calendar-alt me-2"></i> Reschedule
                            </a>

                            <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this scheduled download?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="neo-btn btn-danger w-100">
                                    <i class="fas fa-ban me-2"></i> Cancel Schedule
                                </button>
                            </form>
                        @elseif($schedule->status == 'failed')
                            <form action="{{ route('schedules.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="url" value="{{ $schedule->url }}">
                                <input type="hidden" name="format" value="{{ $schedule->format }}">
                                <input type="hidden" name="quality" value="{{ $schedule->quality }}">
                                <input type="hidden" name="scheduled_for" value="{{ now()->addMinutes(5)->format('Y-m-d\TH:i') }}">
                                <button type="submit" class="neo-btn w-100">
                                    <i class="fas fa-redo me-2"></i> Try Again
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('schedules.create') }}" class="neo-btn btn-secondary">
                            <i class="fas fa-plus-circle me-2"></i> New Schedule
                        </a>

                        <a href="{{ route('downloads.create') }}" class="neo-btn btn-secondary">
                            <i class="fas fa-download me-2"></i> Instant Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- Other Schedules Card -->
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">Other Scheduled Downloads</h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($otherSchedules) && $otherSchedules->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($otherSchedules as $otherSchedule)
                                <a href="{{ route('schedules.show', $otherSchedule) }}" class="list-group-item list-group-item-action">
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
                                                    {{ $otherSchedule->title ?? 'Scheduled Download' }}
                                                </div>
                                                <small class="text-muted">{{ $otherSchedule->scheduled_for->format('M d') }}</small>
                                            </div>
                                            <div>
                                                <small class="text-muted">{{ $otherSchedule->scheduled_for->format('h:i A') }}</small>
                                                <x-status-badge :status="$otherSchedule->status" class="badge-sm" />
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <i class="fas fa-calendar-alt fa-2x mb-3 text-muted"></i>
                            <p class="mb-0">No other scheduled downloads found</p>
                        </div>
                    @endif
                </div>
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
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid #121212;
        box-shadow: 3px 3px 0 rgba(0,0,0,0.2);
        color: white;
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

    .badge-sm {
        font-size: 10px;
        padding: 3px 6px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Countdown functionality
    function updateCountdowns() {
        document.querySelectorAll('.countdown').forEach(function(countdown) {
            const targetTime = parseInt(countdown.dataset.time) * 1000;
            const now = new Date().getTime();
            const difference = targetTime - now;

            if (difference < 0) {
                countdown.innerHTML = 'Time has passed';
                return;
            }

            // Calculate time components
            const days = Math.floor(difference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((difference % (1000 * 60)) / 1000);

            // Format output based on remaining time
            if (days > 0) {
                countdown.innerHTML = `${days}d ${hours}h ${minutes}m`;
            } else if (hours > 0) {
                countdown.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
            } else {
                countdown.innerHTML = `${minutes}m ${seconds}s`;
            }
        });
    }

    // Initial update and set interval
    document.addEventListener('DOMContentLoaded', function() {
        updateCountdowns();
        setInterval(updateCountdowns, 1000);

        // Auto-refresh if scheduled time is close or processing
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
