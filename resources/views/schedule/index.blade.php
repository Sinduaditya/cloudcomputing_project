<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\schedule\index.blade.php -->
@extends('layouts.app')

@section('title', 'Scheduled Downloads')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Scheduled Downloads</h1>
        <div>
            <a href="{{ route('schedules.create') }}" class="neo-btn">
                <i class="fas fa-plus-circle me-2"></i> Schedule New
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="neo-card mb-3">
        <div class="card-body p-3">
            <form action="{{ route('schedules.index') }}" method="GET" class="row g-3">
                <div class="col-md-4 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text" style="border: 2px solid #212529; border-right: none;"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by URL" class="form-control" style="border: 2px solid #212529; box-shadow: 3px 3px 0 rgba(0,0,0,0.1);">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select" style="border: 2px solid #212529; box-shadow: 3px 3px 0 rgba(0,0,0,0.1);">
                        <option value="">All Statuses</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="platform" class="form-select" style="border: 2px solid #212529; box-shadow: 3px 3px 0 rgba(0,0,0,0.1);">
                        <option value="">All Platforms</option>
                        <option value="youtube" {{ request('platform') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                        <option value="tiktok" {{ request('platform') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                        <option value="instagram" {{ request('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="facebook" {{ request('platform') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                    </select>
                </div>
                <div class="col-md-2 col-lg-1">
                    <button type="submit" class="neo-btn w-100" style="border: 2px solid #212529; box-shadow: 3px 3px 0 rgba(0,0,0,0.2); padding: 0.375rem 0.75rem;">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Table Card -->
    <div class="neo-card mb-4 ">
        <div class="card-header  d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="padding: 12px;">Scheduled Tasks</h5>
            <div class="dropdown">
                <button class="neo-btn btn-sm dropdown-toggle" type="button" id="scheduleActions" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                    <i class="fas fa-cog me-1"></i> Bulk Actions
                </button>
                <ul class="dropdown-menu shadow-sm" aria-labelledby="scheduleActions" style="font-size: 0.875rem; min-width: 8rem;">
                    <li><button class="dropdown-item py-1" id="selectAllBtn"><i class="fas fa-check-square me-2"></i> Select All</button></li>
                    <li><button class="dropdown-item py-1" id="deselectAllBtn"><i class="fas fa-square me-2"></i> Deselect All</button></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><button class="dropdown-item py-1 text-danger" id="deleteSelectedBtn"><i class="fas fa-trash-alt me-2"></i> Delete Selected</button></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            @if(isset($schedules) && $schedules->count() > 0)
                <form id="bulkActionForm" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">
                                        <input type="checkbox" class="form-check-input" id="selectAll" style="border: 2px solid #212529; width: 20px; height: 20px;">
                                    </th>
                                    <th>URL</th>
                                    <th>Platform</th>
                                    <th>Format</th>
                                    <th>Scheduled For</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input schedule-checkbox" name="schedules[]" value="{{ $schedule->id }}" style="border: 2px solid #212529; width: 20px; height: 20px;">
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" title="{{ $schedule->url }}">
                                                {{ $schedule->url }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($schedule->platform == 'youtube')
                                                <span class="badge bg-danger" style="border: 2px solid #212529;">
                                                    <i class="fab fa-youtube me-1"></i>YouTube
                                                </span>
                                            @elseif($schedule->platform == 'tiktok')
                                                <span class="badge bg-dark" style="border: 2px solid #212529;">
                                                    <i class="fab fa-tiktok me-1"></i>TikTok
                                                </span>
                                            @elseif($schedule->platform == 'instagram')
                                                <span class="badge bg-primary" style="border: 2px solid #212529;">
                                                    <i class="fab fa-instagram me-1"></i>Instagram
                                                </span>
                                            @elseif($schedule->platform == 'facebook')
                                                <span class="badge bg-info" style="border: 2px solid #212529;">
                                                    <i class="fab fa-facebook me-1"></i>Facebook
                                                </span>
                                            @else
                                                <span class="badge bg-secondary" style="border: 2px solid #212529;">
                                                    {{ ucfirst($schedule->platform) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary" style="border: 2px solid #212529;">
                                                {{ strtoupper($schedule->format) }}
                                                @if($schedule->quality)
                                                    - {{ $schedule->quality }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $schedule->scheduled_for->format('M d, Y') }}</strong><br>
                                                <small class="text-muted">{{ $schedule->scheduled_for->format('h:i A') }}</small>
                                            </div>
                                            @if($schedule->scheduled_for->isPast() && $schedule->status == 'scheduled')
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Overdue
                                                </small>
                                            @else
                                                <small class="text-muted">
                                                    {{ $schedule->time_remaining }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $schedule->status_badge }}" style="border: 2px solid #212529;">
                                                @if($schedule->status == 'scheduled')
                                                    <i class="fas fa-clock me-1"></i>
                                                @elseif($schedule->status == 'processing')
                                                    <i class="fas fa-spinner fa-spin me-1"></i>
                                                @elseif($schedule->status == 'completed')
                                                    <i class="fas fa-check me-1"></i>
                                                @elseif($schedule->status == 'failed')
                                                    <i class="fas fa-times me-1"></i>
                                                @elseif($schedule->status == 'cancelled')
                                                    <i class="fas fa-ban me-1"></i>
                                                @endif
                                                {{ ucfirst($schedule->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if($schedule->status === 'scheduled')
                                                    <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Cancel" onclick="return confirm('Are you sure you want to cancel this schedule?');">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($schedule->status === 'completed' && $schedule->download)
                                                    <a href="{{ route('downloads.show', $schedule->download) }}" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="View Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif

                                                @if($schedule->status === 'failed')
                                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Error: {{ $schedule->error_message }}">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $schedules->appends(request()->query())->links() }}
                </div>
            @else
                <x-empty-state
                    title="No Scheduled Downloads"
                    message="You don't have any scheduled downloads yet."
                    icon="fas fa-calendar-alt"
                    action="true"
                    actionLink="{{ route('schedules.create') }}"
                    actionText="Schedule New"
                />
            @endif
        </div>
    </div>

    <!-- Stats Card -->
    @if(isset($schedules) && $schedules->count() > 0)
        <div class="row mb-4">
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="neo-card h-100 hover-shadow">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-clock text-warning me-2"></i>
                            Scheduled
                        </h5>
                        <h2 class="text-warning mb-0">{{ $schedules->where('status', 'scheduled')->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="neo-card h-100 hover-shadow">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-spinner text-info me-2"></i>
                            Processing
                        </h5>
                        <h2 class="text-info mb-0">{{ $schedules->where('status', 'processing')->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="neo-card h-100 hover-shadow">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            Completed
                        </h5>
                        <h2 class="text-success mb-0">{{ $schedules->where('status', 'completed')->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="neo-card h-100 hover-shadow">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-times text-danger me-2"></i>
                            Failed
                        </h5>
                        <h2 class="text-danger mb-0">{{ $schedules->where('status', 'failed')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Calendar View Toggle Button -->
    <div class="text-center mt-4">
        <button class="neo-btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#calendarView" aria-expanded="false" aria-controls="calendarView">
            <i class="fas fa-calendar-alt me-2"></i> Toggle Calendar View
        </button>
    </div>

    <!-- Calendar View (Collapsed by Default) -->
    <div class="collapse mt-4" id="calendarView">
        <div class="neo-card">
            <div class="card-header">
                <h5 class="mb-0">Calendar View</h5>
            </div>
            <div class="card-body">
                <div id="schedulesCalendar"></div>
                <div class="text-center mt-3 text-muted">
                    <p><i class="fas fa-info-circle me-2"></i> Calendar view shows your scheduled downloads by date</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
<style>
    .fc-event {
        border: 2px solid #212529 !important;
        padding: 2px 4px !important;
        cursor: pointer;
    }
    .fc-day-today {
        background-color: rgba(255, 75, 43, 0.1) !important;
    }
    .fc-button {
        background-color: #ff4b2b !important;
        border-color: #212529 !important;
        border-width: 2px !important;
        box-shadow: 3px 3px 0 rgba(0,0,0,0.2) !important;
    }
    .fc-button:hover {
        background-color: #ff6b4b !important;
        transform: translate(1px, 1px);
        box-shadow: 2px 2px 0 rgba(0,0,0,0.2) !important;
    }
    .text-truncate {
        cursor: help;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Select all checkbox functionality
        $('#selectAll').change(function() {
            $('.schedule-checkbox').prop('checked', this.checked);
        });

        $('.schedule-checkbox').change(function() {
            if ($('.schedule-checkbox:checked').length == $('.schedule-checkbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });

        // Bulk action buttons
        $('#selectAllBtn').click(function() {
            $('.schedule-checkbox').prop('checked', true);
            $('#selectAll').prop('checked', true);
        });

        $('#deselectAllBtn').click(function() {
            $('.schedule-checkbox').prop('checked', false);
            $('#selectAll').prop('checked', false);
        });

        $('#deleteSelectedBtn').click(function() {
            if($('.schedule-checkbox:checked').length === 0) {
                alert('Please select at least one schedule to delete');
                return;
            }

            if(confirm('Are you sure you want to cancel the selected schedules? This action cannot be undone.')) {
                $('#bulkActionForm').attr('action', '{{ route("schedules.bulk-delete") }}').submit();
            }
        });

        // Initialize calendar when view is toggled
        $('#calendarView').on('shown.bs.collapse', function () {
            initializeCalendar();
        });

        function initializeCalendar() {
            const calendarEl = document.getElementById('schedulesCalendar');
            if (!calendarEl || calendarEl.hasChildNodes()) return;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach($schedules as $schedule)
                    {
                        title: '{{ addslashes(parse_url($schedule->url, PHP_URL_HOST) ?? "Scheduled Download") }}',
                        start: '{{ $schedule->scheduled_for->format('Y-m-d H:i:s') }}',
                        url: '{{ route("schedules.show", $schedule) }}',
                        backgroundColor: '{{ $schedule->status == "scheduled" ? "#ff4b2b" : ($schedule->status == "completed" ? "#28a745" : ($schedule->status == "failed" ? "#dc3545" : "#6c757d")) }}',
                        borderColor: '#212529',
                        extendedProps: {
                            status: '{{ $schedule->status }}',
                            platform: '{{ $schedule->platform }}',
                            format: '{{ $schedule->format }}'
                        }
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },
                eventDidMount: function(info) {
                    // Add tooltip to calendar events
                    info.el.setAttribute('title',
                        'Status: ' + info.event.extendedProps.status + '\n' +
                        'Platform: ' + info.event.extendedProps.platform + '\n' +
                        'Format: ' + info.event.extendedProps.format
                    );
                }
            });

            calendar.render();
        }

        // Auto-refresh page every 30 seconds if there are processing schedules
        @if($schedules->where('status', 'processing')->count() > 0)
        setTimeout(function() {
            location.reload();
        }, 30000);
        @endif
    });
</script>
@endpush
