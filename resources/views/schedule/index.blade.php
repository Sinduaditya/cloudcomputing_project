<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\index.blade.php -->
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
    <div class="neo-card mb-4">
        <div class="card-body">
            <form action="{{ route('schedules.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or URL" class="neo-form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="neo-form-control">
                        <option value="">All Statuses</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Type</label>
                    <select name="type" class="neo-form-control">
                        <option value="">All Types</option>
                        <option value="once" {{ request('type') == 'once' ? 'selected' : '' }}>One Time</option>
                        <option value="daily" {{ request('type') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ request('type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="w-100">
                        <button type="submit" class="neo-btn w-100">
                            <i></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Schedules Table Card -->
    <div class="neo-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="padding: 12px;">Scheduled Tasks</h5>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" id="scheduleActions" data-bs-toggle="dropdown" aria-expanded="false">
                    Actions
                </button>
                <ul class="dropdown-menu" aria-labelledby="scheduleActions">
                    <li><button class="dropdown-item" id="selectAllBtn">Select All</button></li>
                    <li><button class="dropdown-item" id="deselectAllBtn">Deselect All</button></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><button class="dropdown-item text-danger" id="deleteSelectedBtn">Delete Selected</button></li>
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
                                    <th>Title</th>
                                    <th>Schedule</th>
                                    <th>Next Run</th>
                                    <th>Type</th>
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
                                            <div class="text-truncate" style="max-width: 200px;">
                                                {{ $schedule->title ?? 'Untitled' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($schedule->schedule_type == 'once')
                                                <span class="badge bg-secondary" style="border: 2px solid #212529;">One Time</span>
                                            @elseif($schedule->schedule_type == 'daily')
                                                <span class="badge bg-primary" style="border: 2px solid #212529;">Daily</span>
                                            @elseif($schedule->schedule_type == 'weekly')
                                                <span class="badge bg-info" style="border: 2px solid #212529;">Weekly</span>
                                            @elseif($schedule->schedule_type == 'monthly')
                                                <span class="badge bg-warning" style="border: 2px solid #212529;">Monthly</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $schedule->next_run_at ? $schedule->next_run_at->format('M d, Y h:i A') : 'Not scheduled' }}
                                        </td>
                                        <td>{{ strtoupper($schedule->format) }}</td>
                                        <td>
                                            <x-status-badge :status="$schedule->status" />
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this schedule?');">
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

            if(confirm('Are you sure you want to delete the selected schedules? This action cannot be undone.')) {
                // Here we would submit the form to delete selected schedules
                $('#bulkActionForm').attr('action', '{{ route("schedules.bulk-delete") }}').submit();
                alert('Deleted ' + $('.schedule-checkbox:checked').length + ' schedules');
            }
        });

        // Initialize calendar when view is toggled
        $('#calendarView').on('shown.bs.collapse', function () {
            initializeCalendar();
        });

        function initializeCalendar() {
            const calendarEl = document.getElementById('schedulesCalendar');
            if (!calendarEl) return;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    // Mock events - this would be loaded from backend in production
                    @foreach($schedules as $schedule)
                    {
                        title: '{{ $schedule->title ?? "Scheduled Download" }}',
                        start: '{{ $schedule->next_run_at ? $schedule->next_run_at->format('Y-m-d H:i:s') : '' }}',
                        url: '{{ route("schedules.show", $schedule) }}',
                        backgroundColor: '{{ $schedule->status == "scheduled" ? "#ff4b2b" : ($schedule->status == "completed" ? "#28a745" : "#6c757d") }}',
                        borderColor: '#212529'
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // prevent browser navigation
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                }
            });

            calendar.render();
        }
    });
</script>
@endpush
