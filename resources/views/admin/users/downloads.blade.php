<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\users\downloads.blade.php -->
@extends('layouts.admin')

@section('title', 'User Downloads')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Downloads for {{ $user->name }}</h1>
        <a href="{{ route('admin.users.show', $user) }}" class="neo-btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to User Profile
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="neo-card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.downloads', $user) }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <input type="text" name="search" class="neo-form-control" placeholder="Search by title or URL" value="{{ request('search') }}">
                        <button class="neo-btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-3">
                    <x-form-select
                        name="platform"
                        label="Platform"
                        :options="[
                            '' => 'All Platforms',
                            'youtube' => 'YouTube',
                            'tiktok' => 'TikTok',
                            'instagram' => 'Instagram',
                            'other' => 'Other'
                        ]"
                        selected="{{ request('platform') }}"
                    />
                </div>

                <div class="col-md-3">
                    <x-form-select
                        name="status"
                        label="Status"
                        :options="[
                            '' => 'All Statuses',
                            'completed' => 'Completed',
                            'processing' => 'Processing',
                            'failed' => 'Failed',
                            'pending' => 'Pending'
                        ]"
                        selected="{{ request('status') }}"
                    />
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="neo-btn w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Downloads List -->
    <div class="neo-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Downloads</h5>
            <span class="badge bg-primary" style="border: 2px solid #121212; padding: 8px 15px;">
                {{ $downloads->total() }} Downloads Found
            </span>
        </div>
        <div class="card-body p-0">
            @if($downloads->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Platform</th>
                                <th>Format</th>
                                <th>Size</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($downloads as $download)
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
                                    <td>{{ strtoupper($download->format)<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\users\downloads.blade.php -->
@extends('layouts.admin')

@section('title', 'User Downloads')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Downloads for {{ $user->name }}</h1>
        <a href="{{ route('admin.users.show', $user) }}" class="neo-btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to User Profile
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="neo-card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.downloads', $user) }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Search</label>
                    <div class="input-group">
                        <input type="text" name="search" class="neo-form-control" placeholder="Search by title or URL" value="{{ request('search') }}">
                        <button class="neo-btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-3">
                    <x-form-select
                        name="platform"
                        label="Platform"
                        :options="[
                            '' => 'All Platforms',
                            'youtube' => 'YouTube',
                            'tiktok' => 'TikTok',
                            'instagram' => 'Instagram',
                            'other' => 'Other'
                        ]"
                        selected="{{ request('platform') }}"
                    />
                </div>

                <div class="col-md-3">
                    <x-form-select
                        name="status"
                        label="Status"
                        :options="[
                            '' => 'All Statuses',
                            'completed' => 'Completed',
                            'processing' => 'Processing',
                            'failed' => 'Failed',
                            'pending' => 'Pending'
                        ]"
                        selected="{{ request('status') }}"
                    />
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="neo-btn w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Downloads List -->
    <div class="neo-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Downloads</h5>
            <span class="badge bg-primary" style="border: 2px solid #121212; padding: 8px 15px;">
                {{ $downloads->total() }} Downloads Found
            </span>
        </div>
        <div class="card-body p-0">
            @if($downloads->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Platform</th>
                                <th>Format</th>
                                <th>Size</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($downloads as $download)
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
                                    <td>{{ strtoupper($download->format)
                                    }}</td>
                                    <td>
                                        @if($download->file_size)
                                            {{ round($download->file_size / (1024 * 1024), 2) }} MB
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <x-status-badge :status="$download->status" />
                                    </td>
                                    <td>{{ $download->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.downloads.show', $download) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($download->status == 'completed')
                                                <a href="{{ route('admin.downloads.file', $download) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif

                                            <div class="dropdown d-inline">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('admin.downloads.logs', $download) }}" class="dropdown-item">
                                                            <i class="fas fa-list-alt me-2"></i> View Logs
                                                        </a>
                                                    </li>
                                                    @if(in_array($download->status, ['pending', 'processing']))
                                                        <li>
                                                            <form action="{{ route('admin.downloads.cancel', $download) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-times-circle me-2"></i> Cancel Download
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if($download->status == 'failed')
                                                        <li>
                                                            <form action="{{ route('admin.downloads.retry', $download) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-warning">
                                                                    <i class="fas fa-redo me-2"></i> Retry Download
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.downloads.destroy', $download) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this download?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash me-2"></i> Delete Download
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center py-3">
                    {{ $downloads->appends(request()->query())->links() }}
                </div>
            @else
                <div class="p-4">
                    <x-empty-state
                        title="No Downloads Found"
                        message="No downloads match your search criteria or this user hasn't made any downloads yet."
                        icon="fas fa-download"
                    />
                </div>
            @endif
        </div>
    </div>

    <!-- Download Stats -->
    <div class="row mt-4">
        <div class="col-md-6">
            <x-card header="Download Statistics">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded" style="border: 2px solid #212529; box-shadow: 4px 4px 0 rgba(0,0,0,0.2);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted">Total Downloads</div>
                                    <div class="fs-4 fw-bold">{{ $stats['total_count'] }}</div>
                                </div>
                                <i class="fas fa-download fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded" style="border: 2px solid #212529; box-shadow: 4px 4px 0 rgba(0,0,0,0.2);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted">Total Size</div>
                                    <div class="fs-4 fw-bold">{{ $stats['total_size'] ?? '0' }} MB</div>
                                </div>
                                <i class="fas fa-database fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded" style="border: 2px solid #212529; box-shadow: 4px 4px 0 rgba(0,0,0,0.2);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted">Tokens Used</div>
                                    <div class="fs-4 fw-bold">{{ $stats['total_tokens'] ?? '0' }}</div>
                                </div>
                                <i class="fas fa-coins fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded" style="border: 2px solid #212529; box-shadow: 4px 4px 0 rgba(0,0,0,0.2);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-muted">Success Rate</div>
                                    <div class="fs-4 fw-bold">{{ $stats['success_rate'] ?? '0' }}%</div>
                                </div>
                                <i class="fas fa-chart-line fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col-md-6">
            <x-card header="Platform Distribution">
                @if(count($platformStats ?? []) > 0)
                    <div class="platform-stats">
                        @foreach($platformStats as $platform => $count)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div>
                                        @if($platform == 'youtube')
                                            <i class="fab fa-youtube text-danger me-2"></i>
                                        @elseif($platform == 'tiktok')
                                            <i class="fab fa-tiktok me-2"></i>
                                        @elseif($platform == 'instagram')
                                            <i class="fab fa-instagram me-2"></i>
                                        @else
                                            <i class="fas fa-link me-2"></i>
                                        @endif
                                        {{ ucfirst($platform) }}
                                    </div>
                                    <span class="badge bg-primary" style="border: 2px solid #121212;">
                                        {{ $count }} ({{ round(($count / $stats['total_count']) * 100) }}%)
                                    </span>
                                </div>
                                <div class="progress" style="height: 10px; border: 2px solid #121212; box-shadow: 2px 2px 0 rgba(0,0,0,0.2);">
                                    <div class="progress-bar
                                        @if($platform == 'youtube') bg-danger
                                        @elseif($platform == 'tiktok') bg-info
                                        @elseif($platform == 'instagram') bg-purple
                                        @else bg-secondary @endif"
                                        style="width: {{ ($count / $stats['total_count']) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-chart-pie text-muted fa-3x mb-3"></i>
                        <p class="text-muted mb-0">No platform data available</p>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
