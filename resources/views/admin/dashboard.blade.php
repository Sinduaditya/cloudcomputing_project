<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Key Metrics -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $metrics['total_users'] }}"
                label="Total Users"
                icon="fas fa-users"
                color="primary"
                link="{{ route('admin.users.index') }}"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $metrics['total_downloads'] }}"
                label="Total Downloads"
                icon="fas fa-download"
                color="success"
                link="{{ route('admin.downloads.index') }}"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $metrics['total_tokens'] }}"
                label="Tokens Issued"
                icon="fas fa-coins"
                color="warning"
                link="{{ route('admin.tokens.index') }}"
            />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stats-card
                value="{{ $metrics['active_now'] }}"
                label="Active Users Now"
                icon="fas fa-signal"
                color="info"
            />
        </div>
    </div>

    <div class="row">
        <!-- Monthly Stats -->
        <div class="col-lg-8 mb-4">
            <div class="neo-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Monthly Performance</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chartOptions" data-bs-toggle="dropdown" aria-expanded="false"
                            style="border: 2px solid #212529; border-radius: 6px;">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chartOptions"
                            style="border: 2px solid #212529; border-radius: 6px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                            <li><a class="dropdown-item" href="#" data-chart-period="30">Last 30 Days</a></li>
                            <li><a class="dropdown-item" href="#" data-chart-period="90">Last 3 Months</a></li>
                            <li><a class="dropdown-item" href="#" data-chart-period="180">Last 6 Months</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4 mb-4">
            <div class="neo-card h-100">
                <div class="card-header">
                    <h5 class="mb-0">System Status</h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Storage Usage</span>
                            <span class="fw-bold">{{ number_format($health['storage']['usage_percent'], 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 12px; border: 2px solid #121212; box-shadow: 3px 3px 0 rgba(0,0,0,0.25);">
                            <div class="progress-bar bg-{{ $health['storage']['usage_percent'] > 80 ? 'danger' : ($health['storage']['usage_percent'] > 60 ? 'warning' : 'success') }}"
                                style="width: {{ $health['storage']['usage_percent'] }}%">
                            </div>
                        </div>
                        <div class="small text-muted mt-1">
                            {{ number_format($health['storage']['free_space'], 1) }} GB free of {{ number_format($health['storage']['total_space'], 1) }} GB
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Queue Status</span>
                            <span class="fw-bold">{{ $health['queue']['default_queue_size'] + $health['queue']['scheduled_queue_size'] }} jobs</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="me-3">Default: {{ $health['queue']['default_queue_size'] }}</span>
                            <span>Scheduled: {{ $health['queue']['scheduled_queue_size'] }}</span>
                        </div>
                        <div class="small text-muted mt-1">
                            <span class="{{ $health['queue']['failed_jobs'] > 0 ? 'text-danger' : 'text-success' }}">
                                <i class="fas {{ $health['queue']['failed_jobs'] > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle' }} me-1"></i>
                                {{ $health['queue']['failed_jobs'] }} failed jobs
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Database</span>
                            <span class="fw-bold">{{ $health['database']['users_count'] }} users</span>
                        </div>
                        <div>
                            <span class="me-3">{{ $health['database']['downloads_count'] }} downloads</span>
                            <span>{{ $health['database']['activity_logs_count'] }} logs</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.system.info') }}" class="neo-btn btn-secondary w-100">
                            <i class="fas fa-server me-2"></i> System Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Activity -->
        <div class="col-lg-6 mb-4">
            <div class="neo-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Activity</h5>
                    <a href="{{ route('admin.activities.index') }}" class="neo-btn btn-sm" style="color: #ffffff;">View All</a>
                </div>
                <div class="card-body p-0">
                    @if(count($recentActivity) > 0)
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Time</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivity as $activity)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.users.show', $activity->user_id) }}" class="d-flex align-items-center text-decoration-none">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($activity->user->name) }}&size=32&background=ff4b2b&color=fff"
                                                         class="rounded-circle me-2" alt="{{ $activity->user->name }}"
                                                         style="border: 2px solid #212529;">
                                                    <span>{{ $activity->user->name }}</span>
                                                </a>
                                            </td>
                                            <td>
                                                @php
                                                    $actionClass = match(true) {
                                                        str_contains($activity->action, 'login') => 'text-success',
                                                        str_contains($activity->action, 'download') => 'text-primary',
                                                        str_contains($activity->action, 'token') => 'text-warning',
                                                        str_contains($activity->action, 'fail') || str_contains($activity->action, 'error') => 'text-danger',
                                                        default => ''
                                                    }
                                                @endphp
                                                <span class="{{ $actionClass }} fw-bold">
                                                    {{ str_replace('_', ' ', ucwords($activity->action)) }}
                                                </span>
                                            </td>
                                            <td>{{ $activity->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('admin.activities.show', $activity->id) }}" class="neo-btn btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history text-muted fa-2x mb-2"></i>
                            <p class="mb-0">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Latest Users -->
        <div class="col-lg-6 mb-4">
            <div class="neo-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">New Users</h5>
                    <a href="{{ route('admin.users.index') }}" class="neo-btn btn-sm" style="color: #ffffff;">View All</a>
                </div>
                <div class="card-body p-0">
                    @if(count($newUsers) > 0)
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Registered</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($newUsers as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=32&background=ff4b2b&color=fff"
                                                         class="rounded-circle me-2" alt="{{ $user->name }}"
                                                         style="border: 2px solid #212529;">
                                                    <span>{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $user->id) }}" class="neo-btn btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users text-muted fa-2x mb-2"></i>
                            <p class="mb-0">No new users</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Platform Stats -->
        <div class="col-lg-4 mb-4">
            <div class="neo-card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Downloads by Platform</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:230px;">
                        <canvas id="platformChart"></canvas>
                    </div>

                    <div class="platform-stats mt-4">
                        <div class="row" style="padding: 24px;">
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div style="width:14px; height:14px; background-color:#ff4b2b; border-radius:4px; margin-right:8px; border: 1px solid #212529;"></div>
                                    <span>YouTube</span>
                                    <span class="ms-auto fw-bold">{{ $platformStats['youtube'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div style="width:14px; height:14px; background-color:#25d366; border-radius:4px; margin-right:8px; border: 1px solid #212529;"></div>
                                    <span>TikTok</span>
                                    <span class="ms-auto fw-bold">{{ $platformStats['tiktok'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div style="width:14px; height:14px; background-color:#c13584; border-radius:4px; margin-right:8px; border: 1px solid #212529;"></div>
                                    <span>Instagram</span>
                                    <span class="ms-auto fw-bold">{{ $platformStats['instagram'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div style="width:14px; height:14px; background-color:#666; border-radius:4px; margin-right:8px; border: 1px solid #212529;"></div>
                                    <span>Other</span>
                                    <span class="ms-auto fw-bold">{{ $platformStats['other'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Platform Health -->
        <div class="col-lg-4 mb-4">
            <div class="neo-card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Platform Status</h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="platform-status mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>YouTube API</span>
                            @if($services['youtube'])
                                <span class="badge bg-success" style="border:2px solid #121212;">Operational</span>
                            @else
                                <span class="badge bg-danger" style="border:2px solid #121212;">Degraded</span>
                            @endif
                        </div>
                        <div class="progress" style="height: 8px; border: 2px solid #121212; box-shadow: 2px 2px 0 rgba(0,0,0,0.25);">
                            <div class="progress-bar bg-{{ $services['youtube'] ? 'success' : 'danger' }}" style="width: {{ $services['youtube'] ? '100' : '30' }}%"></div>
                        </div>
                    </div>

                    <div class="platform-status mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>TikTok API</span>
                            @if($services['tiktok'])
                                <span class="badge bg-success" style="border:2px solid #121212;">Operational</span>
                            @else
                                <span class="badge bg-danger" style="border:2px solid #121212;">Degraded</span>
                            @endif
                        </div>
                        <div class="progress" style="height: 8px; border: 2px solid #121212; box-shadow: 2px 2px 0 rgba(0,0,0,0.25);">
                            <div class="progress-bar bg-{{ $services['tiktok'] ? 'success' : 'danger' }}" style="width: {{ $services['tiktok'] ? '100' : '30' }}%"></div>
                        </div>
                    </div>

                    <div class="platform-status mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Instagram API</span>
                            @if($services['instagram'])
                                <span class="badge bg-success" style="border:2px solid #121212;">Operational</span>
                            @else
                                <span class="badge bg-danger" style="border:2px solid #121212;">Degraded</span>
                            @endif
                        </div>
                        <div class="progress" style="height: 8px; border: 2px solid #121212; box-shadow: 2px 2px 0 rgba(0,0,0,0.25);">
                            <div class="progress-bar bg-{{ $services['instagram'] ? 'success' : 'danger' }}" style="width: {{ $services['instagram'] ? '100' : '30' }}%"></div>
                        </div>
                    </div>

                    <div class="platform-status mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Cloudinary</span>
                            @if($services['cloudinary'])
                                <span class="badge bg-success" style="border:2px solid #121212;">Operational</span>
                            @else
                                <span class="badge bg-danger" style="border:2px solid #121212;">Degraded</span>
                            @endif
                        </div>
                        <div class="progress" style="height: 8px; border: 2px solid #121212; box-shadow: 2px 2px 0 rgba(0,0,0,0.25);">
                            <div class="progress-bar bg-{{ $services['cloudinary'] ? 'success' : 'danger' }}" style="width: {{ $services['cloudinary'] ? '100' : '30' }}%"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.system.maintenance') }}" class="neo-btn btn-secondary w-100">
                            <i class="fas fa-wrench me-2"></i> Maintenance Tools
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Failed Downloads -->
        <div class="col-lg-4 mb-4">
            <div class="neo-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Failed Downloads</h5>
                    <span class="badge rounded-pill bg-danger" style="border:2px solid #121212; font-size: 14px;">
                        {{ $metrics['failed_downloads'] }} Total
                    </span>
                </div>
                <div class="card-body p-0">
                    @if(isset($failedDownloads) && count($failedDownloads) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($failedDownloads as $download)
                                <div class="list-group-item py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="text-truncate me-3">
                                            <span class="d-block fw-bold">{{ $download->title ?? $download->url }}</span>
                                            <small class="text-muted">{{ $download->user->name }}</small>
                                        </div>
                                        <a href="{{ route('admin.downloads.show', $download) }}" class="neo-btn btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="small text-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ $download->error_message ?? 'Unknown error' }}
                                    </div>
                                    <div class="small text-muted mt-1">
                                        Failed {{ $download->updated_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-3 text-center">
                            <a href="{{ route('admin.downloads.index') }}?status=failed" class="neo-btn btn-sm">
                                View All Failed Downloads
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                            <p class="mb-0">No failed downloads</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Performance Chart
    const performanceChartCtx = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(performanceChartCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [
                {
                    label: 'Downloads',
                    data: {!! json_encode($counts) !!},
                    backgroundColor: 'rgba(255, 75, 43, 0.2)',
                    borderColor: '#ff4b2b',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#ff4b2b',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: '#e9ecef'
                    },
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false
                    }
                }
            }
        }
    });

    // Platform Chart
    const platformChartCtx = document.getElementById('platformChart').getContext('2d');
    const platformData = {
        youtube: {{ $platformStats['youtube'] ?? 0 }},
        tiktok: {{ $platformStats['tiktok'] ?? 0 }},
        instagram: {{ $platformStats['instagram'] ?? 0 }},
        other: {{ $platformStats['other'] ?? 0 }}
    };

    const platformChart = new Chart(platformChartCtx, {
        type: 'doughnut',
        data: {
            labels: ['YouTube', 'TikTok', 'Instagram', 'Other'],
            datasets: [{
                data: [platformData.youtube, platformData.tiktok, platformData.instagram, platformData.other],
                backgroundColor: [
                    '#ff4b2b', // YouTube
                    '#25d366', // TikTok
                    '#c13584', // Instagram
                    '#666666'  // Other
                ],
                borderColor: [
                    '#212529',
                    '#212529',
                    '#212529',
                    '#212529'
                ],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Chart period selector
    const chartPeriodLinks = document.querySelectorAll('[data-chart-period]');
    chartPeriodLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const period = this.dataset.chartPeriod;

            // In a real application, you would fetch data for the selected period
            // and update the chart. This is just a placeholder.
            alert(`You selected ${period} days period. In a real app, this would update the chart.`);
        });
    });
</script>
@endpush

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

    .progress {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .badge {
        padding: 0.35em 0.65em;
        border-radius: 0.5rem;
        font-weight: 600;
    }
</style>
@endpush
