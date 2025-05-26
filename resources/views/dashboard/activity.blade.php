<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\activities\index.blade.php -->
@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Activity Logs</h1>
        <div class="d-flex gap-2">
            <button type="button" class="neo-btn btn-danger" data-bs-toggle="modal" data-bs-target="#clearLogsModal">
                <i class="fas fa-trash-alt me-2"></i> Clear Logs
            </button>
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
                label="Total Logs"
                icon="fas fa-history"
                color="primary"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $stats['today'] ?? 0 }}"
                label="Today's Activity"
                icon="fas fa-calendar-day"
                color="info"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $stats['users'] ?? 0 }}"
                label="Active Users"
                icon="fas fa-users"
                color="success"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $stats['admin_actions'] ?? 0 }}"
                label="Admin Actions"
                icon="fas fa-user-shield"
                color="warning"
            />
        </div>
    </div>

    <!-- Filter Card -->
    <div class="neo-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="padding: 12px;">Filter Activity Logs</h5>
            <button class="neo-btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
                <i class="fas fa-filter me-1"></i> Toggle Filters
            </button>
        </div>
        <div class="collapse show" id="filtersCollapse">
            <div class="card-body" style="padding: 12px;">
                <form action="{{ route('admin.activities.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Action Type</label>
                        <select name="action" class="neo-form-control">
                            <option value="">All Actions</option>
                            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                            <option value="download" {{ request('action') == 'download' ? 'selected' : '' }}>Download</option>
                            <option value="schedule" {{ request('action') == 'schedule' ? 'selected' : '' }}>Schedule</option>
                            <option value="token" {{ request('action') == 'token' ? 'selected' : '' }}>Token</option>
                            <option value="admin" {{ request('action') == 'admin' ? 'selected' : '' }}>Admin Actions</option>
                            <option value="failed" {{ request('action') == 'failed' ? 'selected' : '' }}>Failed Actions</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">User</label>
                        <input type="text" name="user" class="neo-form-control" placeholder="Email or username" value="{{ request('user') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Resource Type</label>
                        <select name="resource_type" class="neo-form-control">
                            <option value="">All Types</option>
                            <option value="User" {{ request('resource_type') == 'User' ? 'selected' : '' }}>User</option>
                            <option value="Download" {{ request('resource_type') == 'Download' ? 'selected' : '' }}>Download</option>
                            <option value="Schedule" {{ request('resource_type') == 'Schedule' ? 'selected' : '' }}>Schedule</option>
                            <option value="TokenTransaction" {{ request('resource_type') == 'TokenTransaction' ? 'selected' : '' }}>Token Transaction</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Resource ID</label>
                        <input type="text" name="resource_id" class="neo-form-control" placeholder="Resource ID" value="{{ request('resource_id') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">IP Address</label>
                        <input type="text" name="ip_address" class="neo-form-control" placeholder="IP Address" value="{{ request('ip_address') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Date From</label>
                        <input type="date" name="from_date" class="neo-form-control" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Date To</label>
                        <input type="date" name="to_date" class="neo-form-control" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sort By</label>
                        <select name="sort" class="neo-form-control">
                            <option value="created_at_desc" {{ request('sort', 'created_at_desc') == 'created_at_desc' ? 'selected' : '' }}>Newest First</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="neo-btn w-100">
                                <i class="fas fa-search me-2"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.activities.index') }}" class="neo-btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Activity Logs List -->
    <div class="neo-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="padding: 12px;">
                Activity Logs
                @if(request()->has('action') || request()->has('user') || request()->has('resource_type') || request()->has('resource_id') || request()->has('ip_address') || request()->has('from_date') || request()->has('to_date'))
                    <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 12px;">Filtered</span>
                @endif
            </h5>
            <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 14px;">
                {{ $activities->total() }} Results
            </span>
        </div>
        <div class="card-body p-0">
            @if($activities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Resource</th>
                                <th>IP Address</th>
                                <th>Date & Time</th>
                                <th style="width: 80px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                                <tr>
                                    <td>{{ $activity->id }}</td>
                                    <td>
                                        @if($activity->user)
                                            <a href="{{ route('admin.users.show', $activity->user_id) }}" class="d-flex align-items-center text-decoration-none">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($activity->user->name) }}&size=32&background=ff4b2b&color=fff"
                                                    class="rounded-circle me-2" alt="{{ $activity->user->name }}"
                                                    style="border: 2px solid #212529; width: 32px; height: 32px;">
                                                <div>
                                                    <div class="fw-bold">{{ $activity->user->name }}</div>
                                                    <div class="small text-muted">{{ $activity->user->email }}</div>
                                                </div>
                                            </a>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $actionClass = match(true) {
                                                str_contains($activity->action, 'login') => 'text-success',
                                                str_contains($activity->action, 'register') => 'text-primary',
                                                str_contains($activity->action, 'download') => 'text-info',
                                                str_contains($activity->action, 'token') => 'text-warning',
                                                str_contains($activity->action, 'admin') => 'text-purple',
                                                str_contains($activity->action, 'fail') || str_contains($activity->action, 'error') => 'text-danger',
                                                default => ''
                                            }
                                        @endphp
                                        <span class="{{ $actionClass }} fw-bold">
                                            {{ str_replace('_', ' ', ucwords($activity->action)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($activity->resource_type && $activity->resource_id)
                                            <span class="badge rounded-pill
                                                @if($activity->resource_type == 'User') bg-primary
                                                @elseif($activity->resource_type == 'Download') bg-info
                                                @elseif($activity->resource_type == 'Schedule') bg-warning
                                                @elseif($activity->resource_type == 'TokenTransaction') bg-success
                                                @else bg-secondary @endif"
                                                style="border: 1px solid #212529;">
                                                {{ $activity->resource_type }}
                                            </span>
                                            <small>#{{ $activity->resource_id }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $activity->ip_address ?? 'N/A' }}</code>
                                    </td>
                                    <td>
                                        <div data-bs-toggle="tooltip" title="{{ $activity->created_at->format('M d, Y H:i:s') }}">
                                            {{ $activity->created_at->format('M d, Y') }}<br>
                                            <small class="text-muted">{{ $activity->created_at->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.activities.show', $activity) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4 mb-3">
                    {{ $activities->appends(request()->query())->links() }}
                </div>
            @else
                <x-empty-state
                    title="No Activity Logs Found"
                    message="No activity logs found matching your filter criteria."
                    icon="fas fa-history"
                />
            @endif
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" aria-labelledby="clearLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #2B7EC1 0%, #58A7E6 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title" id="clearLogsModalLabel" style="color: #ffffff;">Clear Activity Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to clear activity logs?</p>
                <div class="mb-3">
                    <label class="form-label fw-bold">Retention Period</label>
                    <select class="neo-form-control" id="retentionPeriod">
                        <option value="all">Clear All Logs</option>
                        <option value="30" selected>Keep Last 30 Days</option>
                        <option value="90">Keep Last 90 Days</option>
                        <option value="180">Keep Last 180 Days</option>
                        <option value="365">Keep Last Year</option>
                    </select>
                </div>
                <div class="alert alert-danger" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                    <i class="fas fa-exclamation-triangle me-2"></i> This action cannot be undone! Activity logs are important for auditing and troubleshooting.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #212529;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.activities.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="retention_days" id="retentionDays" value="30">
                    <button type="submit" class="neo-btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Clear Logs
                    </button>
                </form>
            </div>
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

    .neo-card {
        border: 3px solid #212529;
        border-radius: 8px;
        box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
        background: #ffffff;
        overflow: hidden;
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
        background: linear-gradient(90deg, #2B7EC1   0%, #2B7EC1 100%);
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.2);
        transition: transform 0.1s, box-shadow 0.1s;
        cursor: pointer;
        text-decoration: none;
        color:rgb(255, 255, 255);
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

    .neo-btn.btn-primary {
        background: linear-gradient(90deg, #2B7EC1 0%, #58A7E6 100%);
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

    .text-purple {
        color: #6f42c1 !important;
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

        // Handle retention period selection
        document.getElementById('retentionPeriod').addEventListener('change', function() {
            document.getElementById('retentionDays').value = this.value;
        });
    });
</script>
@endpush
