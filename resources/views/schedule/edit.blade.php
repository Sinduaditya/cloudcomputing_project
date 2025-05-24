<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\schedule\edit.blade.php -->
@extends('layouts.app')

@section('title', 'Reschedule Download')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Reschedule Download</h1>
        <div>
            <a href="{{ route('schedules.index') }}" class="neo-btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Schedules
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $schedule->title ?? 'Scheduled Download' }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
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
                                <h6 class="fw-bold mb-0">{{ ucfirst($schedule->platform) }} Download</h6>
                                <div class="small text-muted">{{ $schedule->url }}</div>
                            </div>
                        </div>

                        <div class="current-details p-3 mb-3" style="border: 2px dashed #ccc; border-radius: 8px; background-color: #f8f9fa;">
                            <h6 class="mb-2">Current Details:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <span class="fw-bold">Format:</span>
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
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <span class="fw-bold">Currently Scheduled For:</span>
                                        <span class="text-danger">{{ $schedule->scheduled_for->format('d M Y, h:i A') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('schedules.update', $schedule) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold">Reschedule To:</label>

                            <div class="neo-form-control p-3 mb-3" style="border: 2px solid #212529; border-radius: 8px;">
                                <input type="datetime-local" name="scheduled_for" class="form-control mb-3"
                                    value="{{ old('scheduled_for', $schedule->scheduled_for->format('Y-m-d\TH:i')) }}"
                                    min="{{ now()->addMinutes(10)->format('Y-m-d\TH:i') }}" required>

                                <div class="suggested-times mt-3">
                                    <p class="fw-bold mb-2">Or choose a suggested time:</p>
                                    <div class="row g-2">
                                        @foreach($suggestedTimes as $index => $time)
                                            @php
                                                $formattedTime = \Carbon\Carbon::parse($time);
                                                $timeLabel = $formattedTime->format('h:i A');
                                                $dayLabel = $formattedTime->isToday() ? 'Today' : ($formattedTime->isTomorrow() ? 'Tomorrow' : $formattedTime->format('D, M d'));
                                            @endphp
                                            <div class="col-md-3 col-6">
                                                <button type="button" class="btn w-100 time-suggestion mb-2" style="border: 2px solid #212529; border-radius: 6px; height: 70px;"
                                                    data-time="{{ $formattedTime->format('Y-m-d\TH:i') }}">
                                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                                        <span class="fw-bold">{{ $timeLabel }}</span>
                                                        <small>{{ $dayLabel }}</small>
                                                    </div>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> You can only reschedule downloads that haven't started processing yet.
                        </div>

                        <div class="form-actions d-flex justify-content-between mt-4">
                            <a href="{{ route('schedules.index') }}" class="neo-btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <button type="submit" class="neo-btn">
                                <i class="fas fa-calendar-alt me-2"></i> Update Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('schedules.show', $schedule) }}" class="neo-btn btn-secondary">
                            <i class="fas fa-eye me-2"></i> View Details
                        </a>

                        <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this scheduled download?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="neo-btn btn-danger w-100">
                                <i class="fas fa-ban me-2"></i> Cancel Schedule
                            </button>
                        </form>

                        <a href="{{ route('schedules.create') }}" class="neo-btn">
                            <i class="fas fa-plus-circle me-2"></i> New Schedule
                        </a>
                    </div>
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

    .time-suggestion {
        background: white;
        transition: all 0.2s;
    }

    .time-suggestion:hover, .time-suggestion.selected {
        background: #f8f9fa;
        box-shadow: 3px 3px 0 rgba(0,0,0,0.2);
        transform: translate(-2px, -2px);
    }
</style>
@endpush

@push('scripts')
<script>
    // Script to handle clicking on time suggestion buttons
    document.addEventListener('DOMContentLoaded', function() {
        const timeSuggestions = document.querySelectorAll('.time-suggestion');
        const scheduledForInput = document.querySelector('input[name="scheduled_for"]');

        timeSuggestions.forEach(button => {
            button.addEventListener('click', function() {
                // Remove selected class from all buttons
                timeSuggestions.forEach(btn => btn.classList.remove('selected'));

                // Add selected class to clicked button
                this.classList.add('selected');

                // Update the datetime input with the selected value
                scheduledForInput.value = this.dataset.time;
            });
        });
    });
</script>
@endpush
