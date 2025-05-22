<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\dashboard.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Admin Dashboard</h2>
    <div class="row">
        <!-- Total Users -->
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text display-6">{{ $stats['total_users'] ?? 0 }}</p>
                    <a href="{{ route('admin.users') }}" class="btn btn-light btn-sm">View Users</a>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Active Users</h5>
                    <p class="card-text display-6">{{ $stats['active_users'] ?? 0 }}</p>
                    <a href="{{ route('admin.users') }}" class="btn btn-light btn-sm">Manage Users</a>
                </div>
            </div>
        </div>

        <!-- Pending Downloads -->
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pending Downloads</h5>
                    <p class="card-text display-6">{{ $stats['pending_downloads'] ?? 0 }}</p>
                    <a href="{{ route('admin.downloads') }}" class="btn btn-light btn-sm">View Downloads</a>
                </div>
            </div>
        </div>

        <!-- Scheduled Tasks -->
        <div class="col-md-3">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Scheduled Tasks</h5>
                    <p class="card-text display-6">{{ $stats['scheduled_tasks'] ?? 0 }}</p>
                    <a href="{{ route('admin.schedules') }}" class="btn btn-light btn-sm">View Schedules</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Token Management -->
        <div class="col-md-6">
            <div class="card text-bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Token Management</h5>
                    <p class="card-text">Manage user tokens and track token transactions.</p>
                    <a href="{{ route('admin.tokens') }}" class="btn btn-light btn-sm">Manage Tokens</a>
                </div>
            </div>
        </div>

        <!-- Activity Logs -->
        <div class="col-md-6">
            <div class="card text-bg-dark mb-3">
                <div class="card-body">
                    <h5 class="card-title">Activity Logs</h5>
                    <p class="card-text">View recent activities performed by users and admins.</p>
                    <a href="{{ route('admin.activity-logs') }}" class="btn btn-light btn-sm">View Logs</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
