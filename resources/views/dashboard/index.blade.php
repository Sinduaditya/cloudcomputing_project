@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">My Dashboard</h1>
        <div>
            <a href="{{ route('downloads.create') }}" class="neo-btn">
                <i class="fas fa-plus-circle me-2"></i> New Download
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <x-stats-card
                value="{{ $stats['downloads_count'] }}"
                label="Total Downloads"
                icon="fas fa-download"
                color="primary"
                link="{{ route('downloads.index') }}"
            />
        </div>
        <div class="col-md-3">
            <x-stats-card
                value="{{ $stats['token_balance'] }}"
                label="Token Balance"
                icon="fas fa-coins"
                color="warning"
                link="{{ route('tokens.balance') }}"
            />
        </div>
        <div class="col-md-3">
            <x-stats-card
                value="{{ $stats['scheduled_tasks'] }}"
                label="Scheduled Tasks"
                icon="fas fa-calendar-alt"
                color="info"
                link="{{ route('schedules.index') }}"
            />
        </div>
        <div class="col-md-3">
            <x-stats-card
                value="{{ $stats['total_downloaded_mb'] }} MB"
                label="Total Downloaded"
                icon="fas fa-cloud-download-alt"
                color="success"
                link="{{ route('dashboard.stats') }}"
            />
        </div>
    </div>

    <div class="row">
        <!-- Recent Downloads -->
        <div class="col-lg-6 mb-4">
            <div class="card neo-card">
                <div class="card-header neo-card-header d-flex justify-content-between align-items-center">
                    <h5 class="text-center w-100 mb-0">Recent Downloads</h5>
                    <a href="{{ route('downloads.index') }}" class="btn btn-sm neo-btn">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentDownloads->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Platform</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentDownloads as $download)
                                        <tr>
                                            <td>
                                                <div class="text-truncate" style="max-width: 150px;">
                                                    {{ $download->title ?? 'Untitled' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($download->platform == 'youtube')
                                                    <i class="fab fa-youtube text-danger"></i>
                                                @elseif($download->platform == 'tiktok')
                                                    <i class="fab fa-tiktok"></i>
                                                @elseif($download->platform == 'instagram')
                                                    <i class="fab fa-instagram text-purple"></i>
                                                @else
                                                    <i class="fas fa-link"></i>
                                                @endif
                                                {{ ucfirst($download->platform) }}
                                            </td>
                                            <td>
                                                <x-status-badge :status="$download->status" />
                                            </td>
                                            <td>
                                                <a href="{{ route('downloads.show', $download) }}" class="btn btn-sm neo-btn-outline">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <x-empty-state
                            title="No Downloads Yet"
                            message="You haven't made any downloads yet. Start downloading content now!"
                            icon="fas fa-download"
                            action="true"
                            actionLink="{{ route('downloads.create') }}"
                            actionText="New Download"
                        />
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Tasks -->
        <div class="col-lg-6 mb-4">
            <div class="card neo-card">
                <div class="card-header neo-card-header d-flex justify-content-between align-items-center">
                    <h5 class="text-center w-100 mb-0">Upcoming Scheduled Tasks</h5>
                    <a href="{{ route('schedules.index') }}" class="btn btn-sm neo-btn">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($upcomingTasks->count() > 0)
                        <ul class="list-group neo-list-group">
                            @foreach($upcomingTasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center neo-list-item">
                                    <div>
                                        <div class="fw-bold">{{ $task->title ?? 'Scheduled Download' }}</div>
                                        <div class="text-truncate" style="max-width: 250px;">{{ $task->url }}</div>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $task->scheduled_for->format('M d, Y h:i A') }}
                                        </small>
                                    </div>
                                    <a href="{{ route('schedules.show', $task) }}" class="btn btn-sm neo-btn-outline">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4 neo-empty-state">
                            <i class="fas fa-calendar-alt text-muted fa-3x mb-3"></i>
                            <h5>No Upcoming Tasks</h5>
                            <p class="text-muted">You don't have any scheduled downloads.</p>
                            <a href="{{ route('schedules.create') }}" class="neo-btn mt-2">
                                <i class="fas fa-plus-circle me-2"></i> Schedule Download
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Activity -->
        <div class="col-lg-8 mb-4">
            <div class="card neo-card">
                <div class="card-header neo-card-header d-flex justify-content-between align-items-center">
                    <h5 class="text-center w-100 mb-0">Recent Activity</h5>
                    <a href="{{ route('dashboard.activity') }}" class="btn btn-sm neo-btn">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentActivities->count() > 0)
                        <div class="activity-timeline">
                            @foreach($recentActivities as $activity)
                                <div class="activity-item d-flex mb-3">
                                    <div class="activity-icon me-3">
                                        @php
                                            $action = $activity->action ?? '';
                                        @endphp

                                        @if(strpos($action, 'download') !== false)
                                            <div class="activity-badge bg-primary">
                                                <i class="fas fa-download"></i>
                                            </div>
                                        @elseif(strpos($action, 'token') !== false)
                                            <div class="activity-badge bg-warning">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        @elseif(strpos($action, 'login') !== false)
                                            <div class="activity-badge bg-success">
                                                <i class="fas fa-sign-in-alt"></i>
                                            </div>
                                        @elseif(strpos($action, 'schedule') !== false)
                                            <div class="activity-badge bg-info">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        @else
                                            <div class="activity-badge bg-secondary">
                                                <i class="fas fa-history"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title fw-bold">
                                            {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                        </div>
                                        <div class="activity-subtitle text-muted small">
                                            {{ $activity->created_at->format('M d, Y h:i A') }} ·
                                            IP: {{ $activity->ip_address }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 neo-empty-state">
                            <i class="fas fa-history text-muted fa-3x mb-3"></i>
                            <h5>No Recent Activity</h5>
                            <p class="text-muted">Your recent activity will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Platform Stats and Token Transactions -->
        <div class="col-lg-4">
            <!-- Platform Stats -->
            <div class="card neo-card mb-4">
                <div class="card-header neo-card-header">
                    <h5 class="text-center w-100 mb-0">Downloads by Platform</h5>
                </div>
                <div class="card-body">
                    @if(count($platformStats) > 0)
                        <div class="platform-stats">
                            @foreach($platformStats as $platform => $count)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="platform-icon ps-2">
                                        @if($platform == 'youtube')
                                            <i class="fab fa-youtube text-danger me-2"></i>
                                        @elseif($platform == 'tiktok')
                                            <i class="fab fa-tiktok me-2"></i>
                                        @elseif($platform == 'instagram')
                                            <i class="fab fa-instagram text-purple me-2"></i>
                                        @else
                                            <i class="fas fa-link me-2"></i>
                                        @endif
                                        {{ ucfirst($platform) }}
                                    </div>
                                    <span class="badge neo-badge">
                                        {{ $count }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 neo-empty-state">
                            <i class="fas fa-chart-pie text-muted fa-2x mb-2"></i>
                            <p class="text-muted mb-0">No download data yet</p>
                        </div>
                    @endif

                    <div class="text-center mt-3">
                        <a href="{{ route('dashboard.stats') }}" class="neo-link">
                            <i class="fas fa-chart-line me-1"></i> View Detailed Stats
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Token Transactions -->
            <div class="card neo-card">
                <div class="card-header neo-card-header">
                    <h5 class="text-center w-100 mb-0">Recent Tokens Activity</h5>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        <div class="token-transactions">
                            @foreach($recentTransactions as $transaction)
                                <div class="token-transaction d-flex justify-content-between align-items-center mb-2 neo-transaction">
                                    <div>
                                        <div class="fw-bold">
                                            @if($transaction->type == 'purchase')
                                                <i class="fas fa-plus-circle text-success me-1"></i> Purchase
                                            @elseif($transaction->type == 'usage')
                                                <i class="fas fa-minus-circle text-danger me-1"></i> Usage
                                            @elseif($transaction->type == 'bonus')
                                                <i class="fas fa-gift text-warning me-1"></i> Bonus
                                            @else
                                                <i class="fas fa-exchange-alt text-info me-1"></i> {{ ucfirst($transaction->type) }}
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            {{ $transaction->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <div class="token-amount fw-bold {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 neo-empty-state">
                            <i class="fas fa-coins text-muted fa-2x mb-2"></i>
                            <p class="text-muted mb-0">No token activity yet</p>
                        </div>
                    @endif

                    <div class="text-center mt-3">
                        <a href="{{ route('tokens.balance') }}" class="neo-link">
                            <i class="fas fa-coins me-1"></i> Manage Tokens
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
    /* Neo-brutalism style */
    .neo-card {
        border: 3px solid #000;
        border-radius: 12px;
        box-shadow: 5px 5px 0px #000;
        margin-bottom: 20px;
        background-color: #fff;
        overflow: hidden;
    }

    .neo-card-header {
        background-color: #ffde59;
        border-bottom: 3px solid #000;
        padding: 15px;
        font-weight: bold;
    }



    .neo-btn:hover {
        transform: translate(-3px, -3px);
        box-shadow: 6px 6px 0px #000;
        color: #000;
    }

    .neo-btn-outline {
        background-color: white;
        color: #000;
        border: 3px solid #000;
        border-radius: 8px;
        padding: 5px 10px;
        font-weight: bold;
        box-shadow: 2px 2px 0px #000;
        transition: all 0.2s ease;
    }

    .neo-btn-outline:hover {
        transform: translate(-2px, -2px);
        box-shadow: 4px 4px 0px #000;
    }

    .neo-badge {
        background-color: #4361ee;
        color: #fff;
        border: 2px solid #000;
        border-radius: 6px;
        padding: 5px 10px;
        font-weight: bold;
    }

    .neo-list-group {
        border: none;
        margin: 0;
    }

    .neo-list-item {
        border: 3px solid #000;
        border-radius: 8px;
        margin-bottom: 8px;
        background-color: #f8f9fa;
    }

    .neo-link {
        color: #4361ee;
        text-decoration: none;
        font-weight: bold;
        border-bottom: 2px solid #4361ee;
        padding-bottom: 2px;
    }

    .neo-transaction {
        border: 3px solid #000;
        border-radius: 8px;
        padding: 10px;
        background-color: #f8f9fa;
    }

    .neo-empty-state {
        border: 3px dashed #ccc;
        border-radius: 8px;
        padding: 20px;
    }

    .activity-badge {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: 3px solid #000;
        box-shadow: 3px 3px 0px #000;
    }

    .activity-title {
        font-size: 14px;
        font-weight: bold;
    }

    .activity-subtitle {
        font-size: 12px;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background-color: #e9ecef;
        border: 2px solid #000;
        font-weight: bold;
    }

    .table td {
        border: 2px solid #000;
        vertical-align: middle;
    }

    .activity-timeline {
        padding: 20px;
    }
</style>
@endpush
