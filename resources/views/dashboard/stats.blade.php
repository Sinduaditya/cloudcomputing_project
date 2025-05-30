<!-- filepath: resources/views/dashboard/stats.blade.php -->
@extends('layouts.app')

@section('title', 'Statistics')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Statistics & Analytics</h1>
        <a href="{{ route('dashboard') }}" class="neo-btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>

    <!-- Overview Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <x-stats-card
                value="{{ auth()->user()->downloads()->count() }}"
                label="Total Downloads"
                icon="fas fa-download"
                color="primary"
            />
        </div>
        <div class="col-md-3">
            <x-stats-card
                value="{{ auth()->user()->downloads()->where('status', 'completed')->count() }}"
                label="Completed Downloads"
                icon="fas fa-check-circle"
                color="success"
            />
        </div>
        <div class="col-md-3">
            <x-stats-card
                value="{{ auth()->user()->token_balance }}"
                label="Current Token Balance"
                icon="fas fa-coins"
                color="warning"
            />
        </div>
        <div class="col-md-3">
            <x-stats-card
                value="{{ round(auth()->user()->downloads()->where('status', 'completed')->sum('file_size') / (1024 * 1024), 2) }} MB"
                label="Total Downloaded"
                icon="fas fa-cloud-download-alt"
                color="info"
            />
        </div>
    </div>

    <div class="row">
        <!-- Downloads by Month Chart -->
        <div class="col-lg-8 mb-4">
            <x-card>
                <x-slot name="header">
                    <h5 class="text-center w-100" style="padding: 12px;">Downloads by Month (Last 6 Months)</h5>
                </x-slot>

                @if($downloadsByMonth->count() > 0)
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="downloadsChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line text-muted fa-3x mb-3"></i>
                        <h5>No Download Data</h5>
                        <p class="text-muted">Start downloading content to see your statistics here.</p>
                    </div>
                @endif
            </x-card>
        </div>

        <!-- Downloads by Platform -->
        <div class="col-lg-4 mb-4">
            <x-card>
                <x-slot name="header">
                    <h5 class="text-center w-100" style="padding: 12px;">Downloads by Platform</h5>
                </x-slot>

                @if($downloadsByPlatform->count() > 0)
                    <div class="platform-stats">
                        @foreach($downloadsByPlatform as $platform)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3" 
                                 style="border: 2px solid #eee; border-radius: 8px; background: #f8f9fa;">
                                <div class="d-flex align-items-center">
                                    @if($platform->platform == 'youtube')
                                        <i class="fab fa-youtube text-danger me-3 fa-2x"></i>
                                    @elseif($platform->platform == 'tiktok')
                                        <i class="fab fa-tiktok me-3 fa-2x"></i>
                                    @elseif($platform->platform == 'instagram')
                                        <i class="fab fa-instagram text-purple me-3 fa-2x"></i>
                                    @else
                                        <i class="fas fa-link me-3 fa-2x"></i>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ ucfirst($platform->platform) }}</div>
                                        <small class="text-muted">{{ $platform->count }} downloads</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary fs-6" style="border: 2px solid #121212;">
                                        {{ $platform->count }}
                                    </span>
                                    <div class="small text-muted">
                                        {{ round(($platform->count / $downloadsByPlatform->sum('count')) * 100, 1) }}%
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Platform Chart -->
                    <div class="mt-4">
                        <canvas id="platformChart" style="max-height: 250px;"></canvas>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-pie text-muted fa-3x mb-3"></i>
                        <h5>No Platform Data</h5>
                        <p class="text-muted">Your platform usage will appear here.</p>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    <div class="row">
        <!-- Token Usage Analysis -->
        <div class="col-lg-6 mb-4">
            <x-card>
                <x-slot name="header">
                    <h5 class="text-center w-100" style="padding: 12px;">Token Usage by Type</h5>
                </x-slot>

                @if($tokenUsageByType->count() > 0)
                    <div class="token-usage-stats">
                        @foreach($tokenUsageByType as $usage)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-3"
                                 style="border: 2px solid #eee; border-radius: 8px;">
                                <div class="d-flex align-items-center">
                                    @if($usage->type == 'purchase')
                                        <i class="fas fa-plus-circle text-success me-3 fa-2x"></i>
                                    @elseif($usage->type == 'usage')
                                        <i class="fas fa-minus-circle text-danger me-3 fa-2x"></i>
                                    @elseif($usage->type == 'bonus')
                                        <i class="fas fa-gift text-warning me-3 fa-2x"></i>
                                    @else
                                        <i class="fas fa-exchange-alt text-info me-3 fa-2x"></i>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ ucfirst($usage->type) }}</div>
                                        <small class="text-muted">Total: {{ $usage->total }} tokens</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold fs-5 {{ $usage->total > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $usage->total > 0 ? '+' : '' }}{{ $usage->total }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Token Usage Chart -->
                    <div class="mt-4">
                        <canvas id="tokenChart" style="max-height: 250px;"></canvas>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-coins text-muted fa-3x mb-3"></i>
                        <h5>No Token Data</h5>
                        <p class="text-muted">Your token usage history will appear here.</p>
                    </div>
                @endif
            </x-card>
        </div>

        <!-- Recent Activity Summary -->
        <div class="col-lg-6 mb-4">
            <x-card>
                <x-slot name="header">
                    <h5 class="text-center w-100" style="padding: 12px;">Activity Summary</h5>
                </x-slot>

                <div class="activity-summary">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3" style="border: 2px solid #eee; border-radius: 8px;">
                                <i class="fas fa-calendar-day text-primary fa-2x mb-2"></i>
                                <div class="fw-bold">{{ auth()->user()->downloads()->whereDate('created_at', today())->count() }}</div>
                                <small class="text-muted">Downloads Today</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3" style="border: 2px solid #eee; border-radius: 8px;">
                                <i class="fas fa-calendar-week text-info fa-2x mb-2"></i>
                                <div class="fw-bold">{{ auth()->user()->downloads()->where('created_at', '>=', now()->subWeek())->count() }}</div>
                                <small class="text-muted">This Week</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3" style="border: 2px solid #eee; border-radius: 8px;">
                                <i class="fas fa-calendar-alt text-success fa-2x mb-2"></i>
                                <div class="fw-bold">{{ auth()->user()->downloads()->where('created_at', '>=', now()->subMonth())->count() }}</div>
                                <small class="text-muted">This Month</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3" style="border: 2px solid #eee; border-radius: 8px;">
                                <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                                <div class="fw-bold">{{ auth()->user()->scheduledTasks()->where('status', 'scheduled')->count() }}</div>
                                <small class="text-muted">Scheduled Tasks</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center mb-4 p-4">
                    <a href="{{ route('dashboard.activity') }}" class="neo-btn">
                        <i class="fas fa-history me-2"></i> View Full Activity Log
                    </a>
                </div>
            </x-card>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .chart-container {
        position: relative;
        padding: 20px;
    }

    .text-purple {
        color: #6f42c1 !important;
    }

    .activity-summary .col-6 > div:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    .platform-stats > div:hover,
    .token-usage-stats > div:hover {
        background-color: #e9ecef !important;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Downloads by Month Chart
    @if($downloadsByMonth->count() > 0)
    const downloadsCtx = document.getElementById('downloadsChart');
    if (downloadsCtx) {
        new Chart(downloadsCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($downloadsByMonth as $month)
                        '{{ date("M Y", mktime(0, 0, 0, $month->month, 1, $month->year)) }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Downloads',
                    data: [
                        @foreach($downloadsByMonth as $month)
                            {{ $month->count }},
                        @endforeach
                    ],
                    borderColor: '#ff4b2b',
                    backgroundColor: 'rgba(255, 75, 43, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
    @endif

    // Platform Pie Chart
    @if($downloadsByPlatform->count() > 0)
    const platformCtx = document.getElementById('platformChart');
    if (platformCtx) {
        new Chart(platformCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($downloadsByPlatform as $platform)
                        '{{ ucfirst($platform->platform) }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($downloadsByPlatform as $platform)
                            {{ $platform->count }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#ff4b2b',
                        '#ff6b6b',
                        '#4ecdc4',
                        '#45b7d1',
                        '#96ceb4',
                        '#feca57'
                    ],
                    borderWidth: 2,
                    borderColor: '#212529'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
    @endif

    // Token Usage Chart
    @if($tokenUsageByType->count() > 0)
    const tokenCtx = document.getElementById('tokenChart');
    if (tokenCtx) {
        new Chart(tokenCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($tokenUsageByType as $usage)
                        '{{ ucfirst($usage->type) }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Tokens',
                    data: [
                        @foreach($tokenUsageByType as $usage)
                            {{ $usage->total }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($tokenUsageByType as $usage)
                            @if($usage->type == 'purchase')
                                '#28a745',
                            @elseif($usage->type == 'usage')
                                '#dc3545',
                            @elseif($usage->type == 'bonus')
                                '#ffc107',
                            @else
                                '#17a2b8',
                            @endif
                        @endforeach
                    ],
                    borderColor: '#212529',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endpush