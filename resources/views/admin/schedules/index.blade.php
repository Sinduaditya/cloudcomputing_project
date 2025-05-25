<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\schedules\index.blade.php -->
@extends('layouts.admin')

@section('title', 'Manage Scheduled Downloads')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Scheduled Downloads</h1>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="neo-btn btn-secondary">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $stats['total'] ?? 0 }}"
                label="Total Schedules"
                icon="fas fa-calendar-alt"
                color="primary"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $stats['pending'] ?? 0 }}"
                label="Upcoming"
                icon="fas fa-clock"
                color="info"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $stats['processing'] ?? 0 }}"
                label="Processing"
                icon="fas fa-spinner"
                color="warning"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $stats['completed'] ?? 0 }}"
                label="Completed"
                icon="fas fa-check-circle"
                color="success"
            />
        </div>
    </div>

    <!-- Filter Card -->
    <div class="neo-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Schedules</h5>
            <button class="neo-btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
                <i class="fas fa-filter me-1"></i> Toggle Filters
            </button>
        </div>
        <div class="collapse show" id="filtersCollapse">
            <div class="card-body">
                <form action="{{ route('admin.schedules.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="neo-form-control">
                            <option value="">All Statuses</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Platform</label>
                        <select name="platform" class="neo-form-control">
                            <option value="">All Platforms</option>
                            <option value="youtube" {{ request('platform') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                            <option value="tiktok" {{ request('platform') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                            <option value="instagram" {{ request('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                            <option value="other" {{ request('platform') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">User</label>
                        <input type="text" name="user" class="neo-form-control" placeholder="Email or username" value="{{ request('user') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Format</label>
                        <select name="format" class="neo-form-control">
                            <option value="">All Formats</option>
                            <option value="mp4" {{ request('format') == 'mp4' ? 'selected' : '' }}>MP4 (Video)</option>
                            <option value="mp3" {{ request('format') == 'mp3' ? 'selected' : '' }}>MP3 (Audio)</option>
                            <option value="other" {{ request('format') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Scheduled From</label>
                        <input type="datetime-local" name="from_date" class="neo-form-control" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Scheduled To</label>
                        <input type="datetime-local" name="to_date" class="neo-form-control" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sort By</label>
                        <select name="sort" class="neo-form-control">
                            <option value="scheduled_for_asc" {{ request('sort', 'scheduled_for_asc') == 'scheduled_for_asc' ? 'selected' : '' }}>Earliest First</option>
                            <option value="scheduled_for_desc" {{ request('sort') == 'scheduled_for_desc' ? 'selected' : '' }}>Latest First</option>
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Newest Created</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Oldest Created</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="neo-btn w-100">
                                <i class="fas fa-search me-2"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.schedules.index') }}" class="neo-btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Schedules List -->
    <div class="neo-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Scheduled Downloads
                @if(request()->has('status') || request()->has('platform') || request()->has('user') || request()->has('format') || request()->has('from_date') || request()->has('to_date'))
                    <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 12px;">Filtered</span>
                @endif
            </h5>
            <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 14px;">
                {{ $schedules->total() }} Results
            </span>
        </div>
        <div class="card-body p-0">
            @if($schedules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th style="width: 25%;">Schedule Details</th>
                                <th>User</th>
                                <th>Platform</th>
                                <th>Format</th>
                                <th>Scheduled For</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->id }}</td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;" data-bs-toggle="tooltip" title="{{ $schedule->title ?? 'Untitled' }}">
                                            <a href="{{ $schedule->url }}" target="_blank" class="text-decoration-none">
                                                {{ $schedule->title ?? 'Untitled' }}
                                            </a>
                                        </div>
                                        <div class="small text-muted text-truncate" style="max-width: 300px;">
                                            {{ $schedule->url }}
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $schedule->user_id) }}" class="d-flex align-items-center text-decoration-none">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($schedule->user->name) }}&size=32&background=ff4b2b&color=fff"
                                                class="rounded-circle me-2" alt="{{ $schedule->user->name }}"
                                                style="border: 2px solid #212529; width: 32px; height: 32px;">
                                            <div>
                                                <div class="fw-bold">{{ $schedule->user->name }}</div>
                                                <div class="small text-muted">{{ $schedule->user->email }}</div>
                                            </div>
                                        </a>
                                    </td>
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
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $scheduledFor = \Carbon\Carbon::parse($schedule->scheduled_for);
                                                $isPast = $scheduledFor->isPast();
                                                $isComing = $scheduledFor->isFuture() && $scheduledFor->diffInHours() < 24;
                                            @endphp

                                            @if($schedule->status == 'scheduled' && $isComing)
                                                <i class="fas fa-clock text-warning me-2"></i>
                                            @elseif($schedule->status == 'scheduled' && $isPast)
                                                <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                            @elseif($schedule->status == 'scheduled')
                                                <i class="far fa-calendar-alt text-primary me-2"></i>
                                            @endif

                                            <div>
                                                <div data-bs-toggle="tooltip" title="{{ $scheduledFor->format('F d, Y H:i:s') }}">
                                                    {{ $scheduledFor->format('M d, Y') }}<br>
                                                    <small class="text-muted">{{ $scheduledFor->format('H:i:s') }}</small>
                                                </div>

                                                @if($schedule->status == 'scheduled')
                                                    <small class="{{ $isPast ? 'text-danger' : ($isComing ? 'text-warning' : 'text-muted') }}">
                                                        {{ $isPast ? 'Overdue' : $scheduledFor->diffForHumans() }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill
                                            @if($schedule->status == 'scheduled') bg-primary
                                            @elseif($schedule->status == 'processing') bg-warning
                                            @elseif($schedule->status == 'completed') bg-success
                                            @elseif($schedule->status == 'cancelled') bg-secondary
                                            @elseif($schedule->status == 'failed') bg-danger
                                            @else bg-info @endif"
                                            style="border: 2px solid #212529;">
                                            {{ ucfirst($schedule->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div data-bs-toggle="tooltip" title="{{ $schedule->created_at->format('M d, Y H:i:s') }}">
                                            {{ $schedule->created_at->format('M d, Y') }}<br>
                                            <small class="text-muted">{{ $schedule->created_at->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.schedules.show', $schedule) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture())
                                                <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            <form action="{{ route('admin.schedules.delete', $schedule) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this scheduled download?');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4 mb-3">
                    {{ $schedules->appends(request()->query())->links() }}
                </div>
            @else
                <x-empty-state
                    title="No Scheduled Downloads Found"
                    message="No scheduled downloads found matching your filter criteria."
                    icon="fas fa-calendar-alt"
                />
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .neo-form-control {
        border: 2px solid #212529;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .neo-form-control:focus {
        border-color: #ff4b2b;
        box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2);
        outline: none;
    }

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

    .neo-btn.btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .neo-btn.btn-secondary {
        background: #f8f9fa;
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
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
