<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\system\info.blade.php -->
@extends('layouts.admin')

@section('title', 'System Information')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">System Information</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.system.settings') }}" class="neo-btn btn-secondary">
                <i class="fas fa-cog me-2"></i> System Settings
            </a>
            <a href="{{ route('admin.system.logs') }}" class="neo-btn btn-secondary">
                <i class="fas fa-file-alt me-2"></i> View Logs
            </a>
            <a href="{{ route('admin.dashboard') }}" class="neo-btn btn-secondary">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #ff9a9e, #fad0c4);">
                    <i class="fas fa-server fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ $phpInfo['version'] ?? 'Unknown' }}</h3>
                    <p class="mb-0">PHP Version</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #a8c0ff, #3f2b96);">
                    <i class="fab fa-laravel fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ $laravelInfo['version'] ?? 'Unknown' }}</h3>
                    <p class="mb-0">Laravel Version</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #84fab0, #8fd3f4);">
                    <i class="fas fa-database fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ isset($tables) ? array_sum($tables) : 0 }}</h3>
                    <p class="mb-0">Total Records</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #f6d365, #fda085);">
                    <i class="fas fa-memory fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ $phpInfo['memory_limit'] ?? 'Unknown' }}</h3>
                    <p class="mb-0">Memory Limit</p>
                </div>
            </div>
        </div>
    </div>

    <!-- PHP Information -->
    <div class="neo-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fab fa-php me-2"></i> PHP Information
            </h5>
            <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 14px;">
                {{ $phpInfo['version'] ?? 'Unknown Version' }}
            </span>
        </div>
        <div class="card-body" style="padding: 24px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Operating System</span>
                            <span class="info-value">{{ $phpInfo['os'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Memory Limit</span>
                            <span class="info-value">{{ $phpInfo['memory_limit'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Max Execution Time</span>
                            <span class="info-value">{{ $phpInfo['max_execution_time'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Max Upload Size</span>
                            <span class="info-value">{{ $phpInfo['max_upload'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Post Max Size</span>
                            <span class="info-value">{{ $phpInfo['post_max_size'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">PHP Extensions</span>
                            <span class="info-value">
                                <button type="button" class="neo-btn btn-sm" data-bs-toggle="modal" data-bs-target="#phpExtensionsModal" style="color: white;">
                                    View Extensions
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Laravel Information -->
    <div class="neo-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fab fa-laravel me-2"></i> Laravel Information
            </h5>
            <span class="badge bg-danger" style="border: 2px solid #121212; font-size: 14px;">
                {{ $laravelInfo['version'] ?? 'Unknown Version' }}
            </span>
        </div>
        <div class="card-body" style="padding: 24px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Environment</span>
                            <span class="info-value">
                                <span class="badge {{ $laravelInfo['environment'] == 'production' ? 'bg-success' : 'bg-warning' }}" style="border: 1px solid #212529;">
                                    {{ $laravelInfo['environment'] ?? 'Unknown' }}
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Debug Mode</span>
                            <span class="info-value">
                                <span class="badge {{ $laravelInfo['debug'] == 'Enabled' ? 'bg-warning' : 'bg-success' }}" style="border: 1px solid #212529;">
                                    {{ $laravelInfo['debug'] ?? 'Unknown' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Cache Driver</span>
                            <span class="info-value">{{ $laravelInfo['cache_driver'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Session Driver</span>
                            <span class="info-value">{{ $laravelInfo['session_driver'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Queue Connection</span>
                            <span class="info-value">{{ $laravelInfo['queue_connection'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Server Information -->
        <div class="col-md-6">
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-server me-2"></i> Server Information
                    </h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Server Software</span>
                            <span class="info-value">{{ $serverInfo['server_software'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Server Name</span>
                            <span class="info-value">{{ $serverInfo['server_name'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Server Protocol</span>
                            <span class="info-value">{{ $serverInfo['server_protocol'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Document Root</span>
                            <span class="info-value">
                                <code class="p-1" style="background-color: #f8f9fa; border-radius: 3px;">
                                    {{ $serverInfo['document_root'] ?? 'Unknown' }}
                                </code>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="neo-info-item">
                            <span class="info-label">Server Time</span>
                            <span class="info-value">{{ $serverInfo['request_time'] ?? 'Unknown' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Information -->
        <div class="col-md-6">
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-database me-2"></i> Database Information
                    </h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    @if(isset($databaseInfo['error']))
                        <div class="alert alert-danger" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                            <i class="fas fa-exclamation-triangle me-2"></i> Database Error: {{ $databaseInfo['error'] }}
                        </div>
                    @else
                        <div class="mb-3">
                            <div class="neo-info-item">
                                <span class="info-label">Driver</span>
                                <span class="info-value">{{ $databaseInfo['driver'] ?? 'Unknown' }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="neo-info-item">
                                <span class="info-label">Database Name</span>
                                <span class="info-value">{{ $databaseInfo['database'] ?? 'Unknown' }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="neo-info-item">
                                <span class="info-label">Database Version</span>
                                <span class="info-value">{{ $databaseInfo['version'] ?? 'Unknown' }}</span>
                            </div>
                        </div>

                        @if(isset($tables) && count($tables) > 0)
                            <h6 class="mt-4 mb-3">Table Records</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0" style="border: 2px solid #212529;">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #f8f9fa;">Table</th>
                                            <th style="background-color: #f8f9fa; text-align: right;">Records</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tables as $table => $count)
                                            <tr>
                                                <td>{{ $table }}</td>
                                                <td class="text-end">{{ number_format($count) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PHP Extensions Modal -->
<div class="modal fade" id="phpExtensionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title">PHP Extensions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Extension</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $extensions = get_loaded_extensions();
                                        sort($extensions);
                                    @endphp

                                    @foreach($extensions as $extension)
                                        <tr>
                                            <td>{{ $extension }}</td>
                                            <td>
                                                <span class="badge bg-success" style="border: 1px solid #212529;">
                                                    Loaded
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #212529;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    .neo-info-item {
        display: flex;
        align-items: center;
        padding: 0.5rem;
        border: 2px solid #212529;
        border-radius: 8px;
        background-color: #f8f9fa;
    }

    .neo-info-item .info-label {
        flex: 0 0 40%;
        font-weight: 600;
    }

    .neo-info-item .info-value {
        flex: 0 0 60%;
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
</style>
@endpush
