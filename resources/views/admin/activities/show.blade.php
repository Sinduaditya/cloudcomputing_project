<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\activities\show.blade.php -->
@extends('layouts.admin')

@section('title', 'Activity Log Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Activity Details <span class="badge bg-primary" style="font-size: 16px; border: 2px solid #212529;">ID: {{ $activity->id }}</span></h1>
        <div>
            <a href="{{ route('admin.activities.index') }}" class="neo-btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Activity Logs
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Details -->
        <div class="col-lg-8">
            <div class="neo-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Activity Information</h5>
                    <span class="timestamp">
                        <i class="far fa-clock me-1"></i> {{ $activity->created_at->format('M d, Y H:i:s') }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        @php
                            $actionClass = match(true) {
                                str_contains($activity->action, 'login') => 'success',
                                str_contains($activity->action, 'register') => 'primary',
                                str_contains($activity->action, 'download') => 'info',
                                str_contains($activity->action, 'token') => 'warning',
                                str_contains($activity->action, 'admin') => 'purple',
                                str_contains($activity->action, 'fail') || str_contains($activity->action, 'error') => 'danger',
                                default => 'secondary'
                            }
                        @endphp
                        <div class="d-flex align-items-center">
                            <div class="p-3 me-3 rounded"
                                style="background-color: var(--bs-{{ $actionClass }});
                                border: 2px solid #212529;
                                box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                                <i class="fas fa-{{ match(true) {
                                    str_contains($activity->action, 'login') => 'sign-in-alt',
                                    str_contains($activity->action, 'register') => 'user-plus',
                                    str_contains($activity->action, 'download') => 'download',
                                    str_contains($activity->action, 'token') => 'coins',
                                    str_contains($activity->action, 'admin') => 'user-shield',
                                    str_contains($activity->action, 'fail') || str_contains($activity->action, 'error') => 'exclamation-triangle',
                                    default => 'history'
                                } }} fa-2x text-white"></i>
                            </div>
                            <div>
                                <h3 class="mb-1">{{ str_replace('_', ' ', ucwords($activity->action)) }}</h3>
                                <p class="text-muted mb-0">Action recorded at {{ $activity->created_at->format('H:i:s') }} on {{ $activity->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Details Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border: 2px solid #212529;">
                            <tbody>
                                <tr>
                                    <th style="width: 150px; background-color: #f8f9fa;">Action Type</th>
                                    <td>{{ $activity->action }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">User</th>
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
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Resource Type</th>
                                    <td>
                                        @if($activity->resource_type)
                                            <span class="badge rounded-pill
                                                @if($activity->resource_type == 'User') bg-primary
                                                @elseif($activity->resource_type == 'Download') bg-info
                                                @elseif($activity->resource_type == 'Schedule') bg-warning
                                                @elseif($activity->resource_type == 'TokenTransaction') bg-success
                                                @else bg-secondary @endif"
                                                style="border: 1px solid #212529;">
                                                {{ $activity->resource_type }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Resource ID</th>
                                    <td>
                                        @if($activity->resource_id && $activity->resource_type)
                                            @if($activity->resource_type == 'User')
                                                <a href="{{ route('admin.users.show', $activity->resource_id) }}">
                                                    {{ $activity->resource_id }}
                                                </a>
                                            @elseif($activity->resource_type == 'Download')
                                                <a href="{{ route('admin.downloads.show', $activity->resource_id) }}">
                                                    {{ $activity->resource_id }}
                                                </a>
                                            @elseif($activity->resource_type == 'Schedule')
                                                <a href="{{ route('admin.schedules.show', $activity->resource_id) }}">
                                                    {{ $activity->resource_id }}
                                                </a>
                                            @else
                                                {{ $activity->resource_id }}
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">IP Address</th>
                                    <td>
                                        <code>{{ $activity->ip_address ?? 'Not recorded' }}</code>
                                        @if($activity->ip_address)
                                            <a href="https://whatismyipaddress.com/ip/{{ $activity->ip_address }}" target="_blank" class="ms-2 badge bg-light text-dark" style="border: 1px solid #212529; text-decoration: none;">
                                                <i class="fas fa-external-link-alt"></i> Lookup
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @if(isset($activity->user_agent) && $activity->user_agent)
                                <tr>
                                    <th style="background-color: #f8f9fa;">User Agent</th>
                                    <td>
                                        <div style="max-height: 80px; overflow-y: auto;">
                                            <code>{{ $activity->user_agent }}</code>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th style="background-color: #f8f9fa;">Date & Time</th>
                                    <td>{{ $activity->created_at->format('F d, Y H:i:s') }} ({{ $activity->created_at->diffForHumans() }})</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Details Card -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Activity Details</h5>
                </div>
                <div class="card-body">
                    @if($activity->details)
                        @php
                            $details = json_decode($activity->details, true);
                        @endphp

                        @if(is_array($details))
                            @if(isset($details['changes']) && is_array($details['changes']))
                                <!-- Changes visualization -->
                                <h6 class="mb-3">Changes Made:</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="border: 2px solid #212529;">
                                        <thead>
                                            <tr>
                                                <th style="background-color: #f8f9fa;">Field</th>
                                                <th style="background-color: #f8f9fa;">Old Value</th>
                                                <th style="background-color: #f8f9fa;">New Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($details['changes'] as $field => $change)
                                                <tr>
                                                    <td class="fw-bold">{{ ucfirst($field) }}</td>
                                                    <td>
                                                        @if(is_array($change) && isset($change['from']))
                                                            @if($field === 'is_active' || $field === 'is_admin')
                                                                <span class="badge {{ $change['from'] ? 'bg-success' : 'bg-danger' }}" style="border: 1px solid #212529;">
                                                                    {{ $change['from'] ? 'Yes' : 'No' }}
                                                                </span>
                                                            @elseif($field === 'password')
                                                                <span class="text-muted">[hidden]</span>
                                                            @else
                                                                {{ $change['from'] }}
                                                            @endif
                                                        @else
                                                            {{ is_array($change) ? json_encode($change) : $change }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(is_array($change) && isset($change['to']))
                                                            @if($field === 'is_active' || $field === 'is_admin')
                                                                <span class="badge {{ $change['to'] ? 'bg-success' : 'bg-danger' }}" style="border: 1px solid #212529;">
                                                                    {{ $change['to'] ? 'Yes' : 'No' }}
                                                                </span>
                                                            @elseif($field === 'password')
                                                                <span class="text-muted">[hidden]</span>
                                                            @else
                                                                {{ $change['to'] }}
                                                            @endif
                                                        @else
                                                            {{ is_array($change) ? json_encode($change) : $change }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <!-- Generic data visualization -->
                                <div class="p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                                    <pre style="margin-bottom: 0;"><code>{{ json_encode($details, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            @endif
                        @else
                            <div class="p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                                <pre style="margin-bottom: 0;"><code>{{ $activity->details }}</code></pre>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info mb-0" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                            <i class="fas fa-info-circle me-2"></i> No detailed information available for this activity.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- User Card (if activity has user) -->
            @if($activity->user)
                <div class="neo-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">User Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($activity->user->name) }}&size=64&background=ff4b2b&color=fff"
                                class="rounded-circle me-3" alt="{{ $activity->user->name }}"
                                style="border: 3px solid #212529; width: 64px; height: 64px;">
                            <div>
                                <h5 class="mb-1">{{ $activity->user->name }}</h5>
                                <p class="mb-0">{{ $activity->user->email }}</p>
                            </div>
                        </div>

                        <div class="user-stats mb-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="p-2 text-center" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                                        <h6 class="mb-0">{{ $activity->user->activityLogs()->count() }}</h6>
                                        <small>Activities</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 text-center" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                                        <h6 class="mb-0">{{ $activity->user->token_balance }}</h6>
                                        <small>Token Balance</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users.show', $activity->user_id) }}" class="neo-btn">
                                <i class="fas fa-user me-2"></i> View User Profile
                            </a>
                            <a href="{{ route('admin.users.activities', $activity->user_id) }}" class="neo-btn btn-secondary">
                                <i class="fas fa-history me-2"></i> View User's Activities
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Related Resource Card (if activity has resource) -->
            @if($activity->resource_type && $activity->resource_id)
                <div class="neo-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Related {{ $activity->resource_type }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">
                            <strong>Resource ID:</strong> {{ $activity->resource_id }}
                        </p>

                        <div class="d-grid">
                            @if($activity->resource_type == 'User')
                                <a href="{{ route('admin.users.show', $activity->resource_id) }}" class="neo-btn">
                                    <i class="fas fa-user me-2"></i> View User
                                </a>
                            @elseif($activity->resource_type == 'Download')
                                <a href="{{ route('admin.downloads.show', $activity->resource_id) }}" class="neo-btn">
                                    <i class="fas fa-download me-2"></i> View Download
                                </a>
                            @elseif($activity->resource_type == 'Schedule')
                                <a href="{{ route('admin.schedules.show', $activity->resource_id) }}" class="neo-btn">
                                    <i class="fas fa-calendar-alt me-2"></i> View Schedule
                                </a>
                            @elseif($activity->resource_type == 'TokenTransaction')
                                <a href="{{ route('admin.tokens.transactions') }}?search={{ $activity->resource_id }}" class="neo-btn">
                                    <i class="fas fa-coins me-2"></i> View Transaction
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Similar Activities Card -->
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">Similar Activities</h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($similarActivities) && count($similarActivities) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($similarActivities as $similarActivity)
                                @if($similarActivity->id != $activity->id)
                                    <a href="{{ route('admin.activities.show', $similarActivity) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-history"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <div>{{ str_replace('_', ' ', ucwords($similarActivity->action)) }}</div>
                                                    <small class="text-muted">{{ $similarActivity->created_at->format('M d') }}</small>
                                                </div>
                                                <div>
                                                    <small>
                                                        @if($similarActivity->user)
                                                            {{ $similarActivity->user->name }}
                                                        @else
                                                            System
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <i class="fas fa-history fa-2x mb-3 text-muted"></i>
                            <p class="mb-0">No similar activities found</p>
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

    pre {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        white-space: pre-wrap;
        word-break: break-word;
    }
</style>
@endpush
