<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\download\history.blade.php -->
@extends('layouts.app')

@section('title', 'Download History')

@section('content')
    <div class="container">
       <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Download History</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('downloads.create') }}" class="neo-btn">
                    <i class="fas fa-plus-circle me-2"></i> New Download
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="neo-card mb-3">
            <div class="card-body p-3">
                <form action="{{ route('downloads.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text" style="border: 2px solid #212529; border-right: none;"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title" class="form-control" style="border: 2px solid #212529; box-shadow: 3px 3px 0 rgba(0,0,0,0.1);">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select" style="border: 2px solid #212529; box-shadow: 3px 3px 0 rgba(0,0,0,0.1);">
                            <option value="">All Statuses</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="platform" class="form-select" style="border: 2px solid #212529; box-shadow: 3px 3px 0 rgba(0,0,0,0.1);">
                            <option value="">All Platforms</option>
                            @foreach ($platforms as $platform)
                                <option value="{{ $platform->platform }}" {{ request('platform') == $platform->platform ? 'selected' : '' }}>
                                    @if ($platform->platform == 'youtube')
                                        YouTube ({{ $platform->count }})
                                    @elseif($platform->platform == 'tiktok')
                                        TikTok ({{ $platform->count }})
                                    @elseif($platform->platform == 'instagram')
                                        Instagram ({{ $platform->count }})
                                    @else
                                        {{ ucfirst($platform->platform) }} ({{ $platform->count }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-1">
                        <button type="submit" class="neo-btn w-100" style="border: 2px solid #212529; box-shadow: 3px 3px 0 rgba(0,0,0,0.2); padding: 0.375rem 0.75rem;">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Downloads List -->
        <div class="neo-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="padding: 12px;">Your Downloads</h5>
                <span class="badge bg-primary" style="border: 2px solid #212529; font-size: 14px;">
                    {{ $downloads->total() }} Downloads
                </span>
            </div>
            <div class="card-body p-0">
                @if ($downloads->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Title</th>
                                    <th>Platform</th>
                                    <th>Format</th>
                                    <th>Size</th>
                                    <th>Downloaded</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($downloads as $download)
                                    <tr>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" data-bs-toggle="tooltip"
                                                title="{{ $download->title ?? 'Untitled' }}">
                                                {{ $download->title ?? 'Untitled' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($download->platform == 'youtube')
                                                <i class="fab fa-youtube text-danger me-1"></i>
                                            @elseif($download->platform == 'tiktok')
                                                <i class="fab fa-tiktok me-1"></i>
                                            @elseif($download->platform == 'instagram')
                                                <i class="fab fa-instagram text-purple me-1"></i>
                                            @else
                                                <i class="fas fa-link me-1"></i>
                                            @endif
                                            {{ ucfirst($download->platform) }}
                                        </td>
                                        <td>
                                            @if (strpos($download->format, 'mp4') !== false)
                                                <span class="badge bg-primary" style="border: 1px solid #212529;">MP4</span>
                                                <small>{{ $download->quality }}</small>
                                            @elseif(strpos($download->format, 'mp3') !== false)
                                                <span class="badge bg-success" style="border: 1px solid #212529;">MP3</span>
                                            @else
                                                <span class="badge bg-secondary"
                                                    style="border: 1px solid #212529;">{{ strtoupper($download->format) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($download->file_size)
                                                {{ round($download->file_size / (1024 * 1024), 2) }} MB
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($download->completed_at)
                                                <span data-bs-toggle="tooltip"
                                                    title="{{ $download->completed_at->format('M d, Y H:i:s') }}">
                                                    {{ $download->completed_at->diffForHumans() }}
                                                </span>
                                            @elseif($download->created_at)
                                                <span data-bs-toggle="tooltip"
                                                    title="Created {{ $download->created_at->format('M d, Y H:i:s') }}">
                                                    {{ $download->created_at->diffForHumans() }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <x-status-badge :status="$download->status" />
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('downloads.show', $download) }}"
                                                    class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip"
                                                    title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if ($download->status == 'completed')
                                                    <a href="{{ route('downloads.file', $download) }}"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <a href="{{ route('downloads.index', $download->id) }}"
                                                        class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                                        title="Direct Download">
                                                        <i class="fas fa-cloud-download-alt"></i>
                                                    </a>
                                                    <a href="{{ route('instance.show', $download) }}"
                                                        class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip"
                                                        title="Play">
                                                        <i class="fas fa-play"></i>
                                                    </a>
                                                @endif

                                                @if ($download->status == 'failed')
                                                    <form action="{{ route('downloads.retry', $download) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-warning"
                                                            data-bs-toggle="tooltip" title="Retry">
                                                            <i class="fas fa-redo"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if (in_array($download->status, ['pending', 'processing', 'downloading', 'uploading']))
                                                    <form action="{{ route('downloads.cancel', $download) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="tooltip" title="Cancel">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4 mb-3">
                        {{ $downloads->appends(request()->query())->links() }}
                    </div>
                @else
                    <x-empty-state title="No Downloads Found"
                        message="You haven't made any downloads yet, or none match your filter criteria."
                        icon="fas fa-download" action="true" actionLink="{{ route('downloads.create') }}"
                        actionText="New Download" />
                @endif
            </div>
        </div>

        <!-- Stats Cards -->
        @if(isset($downloads) && $downloads->count() > 0)
            <div class="row mb-4">
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="neo-card h-100 hover-shadow">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Completed
                            </h5>
                            <h2 class="text-success mb-0">{{ $downloads->where('status', 'completed')->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="neo-card h-100 hover-shadow">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-spinner text-warning me-2"></i>
                                In Progress
                            </h5>
                            <h2 class="text-warning mb-0">{{ $downloads->whereIn('status', ['pending', 'processing', 'downloading', 'uploading'])->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="neo-card h-100 hover-shadow">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                Failed
                            </h5>
                            <h2 class="text-danger mb-0">{{ $downloads->where('status', 'failed')->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="neo-card h-100 hover-shadow">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-cloud-download-alt text-info me-2"></i>
                                Total
                            </h5>
                            <h2 class="text-info mb-0">{{ $downloads->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endpush
