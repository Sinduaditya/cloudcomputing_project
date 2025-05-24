<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\users\activities.blade.php -->
@extends('layouts.admin')

@section('title', $user->name . ' - Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">
            Activity Logs: <span class="text-primary">{{ $user->name }}</span>
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.show', $user->id) }}" class="neo-btn btn-secondary">
                <i class="fas fa-user me-2"></i> User Profile
            </a>
            <a href="{{ route('admin.activities.index') }}" class="neo-btn btn-secondary">
                <i class="fas fa-history me-2"></i> All Activities
            </a>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="neo-card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=60&background=ff4b2b&color=fff"
                    class="rounded-circle me-3" alt="{{ $user->name }}"
                    style="border: 3px solid #212529; width: 60px; height: 60px;">
                <div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="mb-0 text-muted">{{ $user->email }}</p>
                </div>
                <div class="ms-auto text-end">
                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}"
                        style="border: 2px solid #212529; font-size: 14px;">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($user->is_admin)
                        <span class="badge bg-dark" style="border: 2px solid #212529; font-size: 14px;">
                            Administrator
                        </span>
                    @endif
                    <div class="mt-2">
                        <span class="text-muted">Member since: {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #ff9a9e, #fad0c4);">
                    <i class="fas fa-history fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($activities->total()) }}</h3>
                    <p class="mb-0">Total Activities</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #a8c0ff, #3f2b96);">
                    <i class="fas fa-calendar-day fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($user->activityLogs()->whereDate('created_at', today())->count()) }}</h3>
                    <p class="mb-0">Today's Activities</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #84fab0, #8fd3f4);">
                    <i class="fas fa-download fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($user->activityLogs()->where('action', 'like', '%download%')->count()) }}</h3>
                    <p class="mb-0">Download Activities</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #f6d365, #fda085);">
                    <i class="fas fa-sign-in-alt fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($user->activityLogs()->where('action', 'like', '%login%')->count()) }}</h3>
                    <p class="mb-0">Login Activities</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="neo-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Activities</h5>
            <button class="neo-btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
                <i class="fas fa-filter me-1"></i> Toggle Filters
            </button>
        </div>
        <div class="collapse show" id="filtersCollapse">
            <div class="card-body">
                <form action="{{ route('admin.users.activities', $user->id) }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Action Type</label>
                        <select name="action" class="neo-form-control">
                            <option value="">All Actions</option>
                            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                            <option value="download" {{ request('action') == 'download' ? 'selected' : '' }}>Download</option>
                            <option value="schedule" {{ request('action') == 'schedule' ? 'selected' : '' }}>Schedule</option>
                            <option value="token" {{ request('action') == 'token' ? 'selected' : '' }}>Token</option>
                            <option value="failed" {{ request('action') == 'failed' ? 'selected' : '' }}>Failed Actions</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Resource Type</label>
                        <select name="resource_type" class="neo-form-control">
                            <option value="">All Types</option>
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
                            <a href="{{ route('admin.users.activities', $user->id) }}" class="neo-btn btn-secondary">
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
            <h5 class="mb-0">
                User Activity Logs
                @if(request()->has('action') || request()->has('resource_type') || request()->has('resource_id') || request()->has('ip_address') || request()->has('from_date') || request()->has('to_date'))
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
                    message="No activity logs found for this user matching your filter criteria."
                    icon="fas fa-history"
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

    .neo-stat-card {
        display: flex;
        align-items: center;
        padding: 1rem;
        border: 3px solid #212529;
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
    }

    .neo-stat-card .icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 8px;
        border: 2px solid #212529;
        margin-right: 1rem;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .neo-stat-card .stat-content h3 {
        font-weight: 700;
        font-size: 1.5rem;
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
    });
</script>
@endpush
