<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\users\show.blade.php -->
@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">User Profile</h1>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn neo-btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i> Back to Users
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn neo-btn">
                <i class="fas fa-edit me-2"></i> Edit User
            </a>
        </div>
    </div>

    <div class="row">
        <!-- User Info -->
        <div class="col-md-4 mb-4">
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120&background=ff4b2b&color=fff" class="rounded-circle mb-3" alt="{{ $user->name }}">
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                        <div class="mt-2">
                            @if($user->is_admin)
                                <span class="badge bg-dark" style="border: 2px solid #121212; padding: 5px 10px;">
                                    <i class="fas fa-user-shield me-1"></i> Administrator
                                </span>
                            @else
                                <span class="badge bg-primary" style="border: 2px solid #121212; padding: 5px 10px;">
                                    <i class="fas fa-user me-1"></i> User
                                </span>
                            @endif

                            <x-status-badge :status="$user->is_active ? 'active' : 'inactive'" />
                        </div>
                    </div>

                    <div class="user-details">
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-6 fw-bold">User ID</div>
                            <div class="col-6">{{ $user->id }}</div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-6 fw-bold">Registered On</div>
                            <div class="col-6">{{ $user->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-6 fw-bold">Last Login</div>
                            <div class="col-6">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-6 fw-bold">Token Balance</div>
                            <div class="col-6">
                                <span class="badge bg-warning" style="border: 2px solid #121212;">
                                    <i class="fas fa-coins me-1"></i> {{ $user->token_balance }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3 border-bottom pb-2">
                            <div class="col-6 fw-bold">OAuth Provider</div>
                            <div class="col-6">
                                @if($user->oauth_provider)
                                    @if($user->oauth_provider == 'google')
                                        <i class="fab fa-google text-danger me-1"></i>
                                    @elseif($user->oauth_provider == 'facebook')
                                        <i class="fab fa-facebook text-primary me-1"></i>
                                    @endif
                                    {{ ucfirst($user->oauth_provider) }}
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        @if($user->is_active)
                            <form action="{{ route('admin.users.deactivate', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="neo-btn btn-secondary w-100">
                                    <i class="fas fa-user-times me-2"></i> Deactivate User
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.activate', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="neo-btn w-100">
                                    <i class="fas fa-user-check me-2"></i> Activate User
                                </button>
                            </form>
                        @endif
                        <button type="button" class="neo-btn btn-secondary" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                            <i class="fas fa-trash me-2"></i> Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- User Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <x-stats-card
                        value="{{ $stats['total_downloads'] }}"
                        label="Total Downloads"
                        icon="fas fa-download"
                        color="primary"
                        link="{{ route('admin.users.downloads', $user) }}"
                    />
                </div>
                <div class="col-md-4">
                    <x-stats-card
                        value="{{ $stats['total_activities'] }}"
                        label="Activity Logs"
                        icon="fas fa-history"
                        color="info"
                        link="{{ route('admin.users.activities', $user) }}"
                    />
                </div>
                <div class="col-md-4">
                    <x-stats-card
                        value="{{ $stats['total_tokens_spent'] }}"
                        label="Tokens Spent"
                        icon="fas fa-coins"
                        color="warning"
                    />
                </div>
            </div>

            <!-- Recent Downloads -->
            <div class="neo-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Downloads</h5>
                    <a href="{{ route('admin.users.downloads', $user) }}" class="btn btn-sm neo-btn">View All</a>
                </div>
                <div class="card-body p-0">
                    @if(count($recentDownloads) > 0)
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Platform</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentDownloads as $download)
                                        <tr>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;">
                                                    {{ $download->title ?? 'Untitled' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($download->platform == 'youtube')
                                                    <i class="fab fa-youtube text-danger me-1"></i>
                                                @elseif($download->platform == 'tiktok')
                                                    <i class="fab fa-tiktok me-1"></i>
                                                @elseif($download->platform == 'instagram')
                                                    <i class="fab fa-instagram me-1"></i>
                                                @else
                                                    <i class="fas fa-link me-1"></i>
                                                @endif
                                                {{ ucfirst($download->platform) }}
                                            </td>
                                            <td>
                                                <x-status-badge :status="$download->status" />
                                            </td>
                                            <td>{{ $download->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.downloads.show', $download) }}" class="btn btn-sm btn-outline-dark">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <i class="fas fa-download text-muted fa-2x mb-2"></i>
                            <p class="mb-0">No downloads found for this user</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="neo-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Activity</h5>
                    <a href="{{ route('admin.users.activities', $user) }}" class="btn btn-sm neo-btn">View All</a>
                </div>
                <div class="card-body p-0">
                    @if(count($recentActivities) > 0)
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>IP Address</th>
                                        <th>User Agent</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivities as $activity)
                                        <tr>
                                            <td>
                                                <span class="{{ str_contains($activity->action, 'login') ? 'text-success' : (str_contains($activity->action, 'fail') ? 'text-danger' : '') }}">
                                                    {{ str_replace('_', ' ', ucwords($activity->action)) }}
                                                </span>
                                            </td>
                                            <td>{{ $activity->ip_address }}</td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                                    {{ $activity->user_agent }}
                                                </span>
                                            </td>
                                            <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <i class="fas fa-history text-muted fa-2x mb-2"></i>
                            <p class="mb-0">No activity records found for this user</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<x-modal id="deleteUserModal" title="Delete User">
    <p>Are you sure you want to delete user <strong>{{ $user->name }}</strong>?</p>
    <p>This action cannot be undone and will delete all associated downloads and activity logs.</p>

    <x-slot name="footer">
        <button type="button" class="btn neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn neo-btn">Delete User</button>
        </form>
    </x-slot>
</x-modal>
@endsection
