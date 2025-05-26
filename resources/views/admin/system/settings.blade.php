
<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\system\settings.blade.php -->
@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">System Settings</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.system.logs') }}" class="neo-btn btn-secondary">
                <i class="fas fa-file-alt me-2"></i> View Logs
            </a>
            <a href="{{ route('admin.system.info') }}" class="neo-btn btn-secondary">
                <i class="fas fa-info-circle me-2"></i> System Info
            </a>
            <a href="{{ route('admin.dashboard') }}" class="neo-btn btn-secondary">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- App Settings -->
    <div class="neo-card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-cog me-2"></i> Application Settings
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ route('admin.system.update-settings') }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="app_name" class="form-label fw-bold">Application Name</label>
                            <input type="text" class="neo-form-control" id="app_name" name="app_name"
                                value="{{ $settings['app_name'] ?? config('app.name') }}">
                            <small class="text-muted">The name of your application, used throughout the site.</small>
                        </div>

                        <div class="mb-3">
                            <label for="max_storage_days" class="form-label fw-bold">Maximum Storage Days</label>
                            <input type="number" class="neo-form-control" id="max_storage_days" name="max_storage_days"
                                value="{{ $settings['max_storage_days'] ?? 7 }}" min="1" max="365">
                            <small class="text-muted">Number of days to keep completed downloads before automatic deletion.</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Environment Information</label>
                            <div class="neo-info-item mb-2">
                                <span class="info-label">Environment</span>
                                <span class="info-value">
                                    <span class="badge {{ $settings['app_environment'] == 'production' ? 'bg-success' : 'bg-warning' }}"
                                        style="border: 1px solid #212529;">
                                        {{ $settings['app_environment'] }}
                                    </span>
                                </span>
                            </div>
                            <div class="neo-info-item">
                                <span class="info-label">Debug Mode</span>
                                <span class="info-value">
                                    <span class="badge {{ $settings['app_debug'] ? 'bg-warning' : 'bg-success' }}"
                                        style="border: 1px solid #212529;">
                                        {{ $settings['app_debug'] ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Environment and debug settings can be changed in the .env file directly.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Maintenance Mode</label>
                            <div class="form-check form-switch ps-0">
                                <div class="neo-switch">
                                    <input class="form-check-input" type="checkbox"
                                        id="maintenance_mode" name="maintenance_mode"
                                        {{ app()->isDownForMaintenance() ? 'checked' : '' }}>
                                    <label class="form-check-label ms-3" for="maintenance_mode">
                                        Enable Maintenance Mode
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">When enabled, the site will display a maintenance message to all users except administrators.</small>
                        </div>
                    </div>
                </div>

                <div class="neo-card mb-4" style="box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Platform Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check form-switch ps-0">
                                    <div class="neo-switch">
                                        <input class="form-check-input" type="checkbox"
                                            id="youtube_enabled" name="youtube_enabled"
                                            {{ $services['youtube']['enabled'] ? 'checked' : '' }}>
                                        <label class="form-check-label ms-3" for="youtube_enabled">
                                            <i class="fab fa-youtube text-danger me-1"></i> Enable YouTube Downloads
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-2 ps-4">
                                    <span class="badge {{ $services['youtube']['api_key'] ? 'bg-success' : 'bg-warning' }}"
                                        style="border: 1px solid #212529;">
                                        API Key: {{ $services['youtube']['api_key'] ? 'Configured' : 'Missing' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch ps-0">
                                    <div class="neo-switch">
                                        <input class="form-check-input" type="checkbox"
                                            id="tiktok_enabled" name="tiktok_enabled"
                                            {{ $services['tiktok']['enabled'] ? 'checked' : '' }}>
                                        <label class="form-check-label ms-3" for="tiktok_enabled">
                                            <i class="fab fa-tiktok me-1"></i> Enable TikTok Downloads
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-2 ps-4">
                                    <span class="badge {{ $services['tiktok']['api_key'] ? 'bg-success' : 'bg-warning' }}"
                                        style="border: 1px solid #212529;">
                                        API Key: {{ $services['tiktok']['api_key'] ? 'Configured' : 'Missing' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch ps-0">
                                    <div class="neo-switch">
                                        <input class="form-check-input" type="checkbox"
                                            id="instagram_enabled" name="instagram_enabled"
                                            {{ $services['instagram']['enabled'] ? 'checked' : '' }}>
                                        <label class="form-check-label ms-3" for="instagram_enabled">
                                            <i class="fab fa-instagram text-purple me-1"></i> Enable Instagram Downloads
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-2 ps-4">
                                    <span class="badge {{ $services['instagram']['api_key'] ? 'bg-success' : 'bg-warning' }}"
                                        style="border: 1px solid #212529;">
                                        API Key: {{ $services['instagram']['api_key'] ? 'Configured' : 'Missing' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="neo-btn">
                        <i class="fas fa-save me-2"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Token Settings -->
    <div class="neo-card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-coins me-2"></i> Token Settings
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <div class="alert" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2); background-color: #f8f9fa;">
                <i class="fas fa-info-circle me-2"></i> Token settings are managed in the
                <a href="{{ route('admin.tokens.pricing') }}" class="fw-bold">Token Pricing</a> section.
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="neo-info-item mb-3">
                        <span class="info-label">Default Token Balance (New Users)</span>
                        <span class="info-value">{{ $settings['default_token_balance'] ?? 100 }} tokens</span>
                    </div>
                    <div class="neo-info-item mb-3">
                        <span class="info-label">Token Price</span>
                        <span class="info-value">${{ number_format($settings['token_price'] ?? 0.1, 2) }} per token</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="neo-info-item mb-3">
                        <span class="info-label">MB per Token</span>
                        <span class="info-value">{{ $settings['mb_per_token'] ?? 2 }} MB</span>
                    </div>
                    <div class="neo-info-item mb-3">
                        <span class="info-label">Minimum Tokens per Download</span>
                        <span class="info-value">{{ $settings['min_tokens_per_download'] ?? 5 }} tokens</span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('admin.tokens.pricing') }}" class="neo-btn">
                    <i class="fas fa-cog me-2"></i> Manage Token Settings
                </a>
            </div>
        </div>
    </div>

    <!-- Cloudinary Settings -->
    <div class="neo-card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-cloud me-2"></i> Cloudinary Integration
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ route('admin.system.update-cloudinary') }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="cloud_name" class="form-label fw-bold">Cloud Name</label>
                            <input type="text" class="neo-form-control" id="cloud_name" name="cloud_name"
                                value="{{ $services['cloudinary']['cloud_name'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="api_key" class="form-label fw-bold">API Key</label>
                            <input type="text" class="neo-form-control" id="api_key" name="api_key"
                                value="{{ old('api_key') }}">
                            <small class="text-muted">
                                @if($services['cloudinary']['api_key'])
                                    Current key is set. Enter new value to change.
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="api_secret" class="form-label fw-bold">API Secret</label>
                            <input type="password" class="neo-form-control" id="api_secret" name="api_secret"
                                value="{{ old('api_secret') }}">
                            <small class="text-muted">
                                @if($services['cloudinary']['api_key'])
                                    Current secret is set. Enter new value to change.
                                @endif
                            </small>
                        </div>
                    </div>
                </div>

                <div class="alert {{ $services['cloudinary']['enabled'] ? 'alert-success' : 'alert-warning' }}"
                    style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                    <i class="fas {{ $services['cloudinary']['enabled'] ? 'fa-check-circle' : 'fa-exclamation-triangle' }} me-2"></i>
                    @if($services['cloudinary']['enabled'])
                        Cloudinary is properly configured and ready to use.
                    @else
                        Cloudinary is not configured. Upload features may be limited.
                    @endif
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="neo-btn">
                        <i class="fas fa-save me-2"></i> Save Cloudinary Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Queue Settings -->
    <div class="neo-card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-tasks me-2"></i> Queue Information
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <div class="alert" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2); background-color: #f8f9fa;">
                <i class="fas fa-info-circle me-2"></i> Queue settings are defined in your application's configuration.
                Visit the <a href="{{ route('admin.system.maintenance') }}" class="fw-bold">Maintenance</a> page to manage queues.
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="neo-info-item mb-3">
                        <span class="info-label">Queue Driver</span>
                        <span class="info-value">
                            <span class="badge {{ $queue['driver'] == 'database' ? 'bg-success' : 'bg-primary' }}"
                                style="border: 1px solid #212529;">
                                {{ $queue['driver'] }}
                            </span>
                        </span>
                    </div>
                    <div class="neo-info-item mb-3">
                        <span class="info-label">Failed Jobs Table</span>
                        <span class="info-value">{{ $queue['failed_table'] }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="neo-info-item mb-3">
                        <span class="info-label">Connection Details</span>
                        <span class="info-value">
                            <button type="button" class="neo-btn btn-sm" data-bs-toggle="modal" data-bs-target="#queueDetailsModal">
                                View Details
                            </button>
                        </span>
                    </div>
                    <div class="neo-info-item mb-3">
                        <span class="info-label">Queue Maintenance</span>
                        <span class="info-value">
                            <a href="{{ route('admin.system.maintenance') }}" class="neo-btn btn-sm">
                                Manage Queues
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Queue Details Modal -->
<div class="modal fade" id="queueDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title">Queue Connection Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" style="border: 2px solid #212529;">
                        <tbody>
                            @foreach($queue['connection'] as $key => $value)
                                <tr>
                                    <th style="width: 150px; background-color: #f8f9fa;">{{ $key }}</th>
                                    <td>
                                        @if(is_array($value))
                                            <pre style="margin-bottom: 0;"><code>{{ json_encode($value, JSON_PRETTY_PRINT) }}</code></pre>
                                        @elseif(is_bool($value))
                                            {{ $value ? 'true' : 'false' }}
                                        @else
                                            {{ $value ?? 'null' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

    .neo-switch {
        position: relative;
        padding-left: 60px;
        margin-bottom: 0;
        cursor: pointer;
    }

    .neo-switch input {
        position: absolute;
        left: 0;
        top: 0;
        width: 50px;
        height: 24px;
        opacity: 0;
        cursor: pointer;
    }

    .neo-switch input + label:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 50px;
        height: 24px;
        background-color: #e9ecef;
        border: 2px solid #212529;
        border-radius: 24px;
        transition: background-color 0.15s ease-in-out;
    }

    .neo-switch input + label:after {
        content: '';
        position: absolute;
        left: 4px;
        top: 4px;
        width: 16px;
        height: 16px;
        background-color: #212529;
        border-radius: 50%;
        transition: transform 0.15s ease-in-out;
    }

    .neo-switch input:checked + label:before {
        background-color: #ff4b2b;
    }

    .neo-switch input:checked + label:after {
        transform: translateX(26px);
    }

    .neo-switch input:focus + label:before {
        box-shadow: 0 0 0 0.2rem rgba(255, 75, 43, 0.25);
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
