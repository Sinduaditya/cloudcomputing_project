<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\download\index.blade.php -->
@extends('layouts.app')

@section('title', 'My Downloads')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">My Downloads</h1>
        <div>
            <a href="{{ route('downloads.create') }}" class="neo-btn">
                <i class="fas fa-plus-circle me-2"></i> New Download
            </a>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="neo-card mb-4">
        <div class="card-body">
            <form action="{{ route('downloads.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or URL" class="neo-form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Platform</label>
                    <select name="platform" class="neo-form-control">
                        <option value="">All Platforms</option>
                        <option value="youtube" {{ request('platform') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                        <option value="tiktok" {{ request('platform') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                        <option value="instagram" {{ request('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="other" {{ request('platform') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="neo-form-control">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="neo-btn w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Downloads Table Card -->
    <div class="neo-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Download History</h5>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" title="List View" id="listViewBtn">
                    <i class="fas fa-list"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" title="Grid View" id="gridViewBtn">
                    <i class="fas fa-th"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(isset($downloads) && $downloads->count() > 0)
                <!-- List View -->
                <div class="table-responsive" id="listView">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Platform</th>
                                <th>Format</th>
                                <th>Size</th>
                                <th>Date</th>
                                <th>Status</th>
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
                                            <i class="fab fa-instagram text-purple me-1"></i>
                                        @else
                                            <i class="fas fa-link me-1"></i>
                                        @endif
                                        {{ ucfirst($download->platform) }}
                                    </td>
                                    <td>{{ strtoupper($download->format) }}</td>
                                    <td>
                                        @if($download->file_size)
                                            {{ round($download->file_size / (1024 * 1024), 2) }} MB
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $download->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <x-status-badge :status="$download->status" />
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('downloads.show', $download) }}" class="btn btn-sm btn-outline-dark" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($download->status == 'completed')
                                                <a href="{{ route('downloads.file', $download) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('instance.show', $download) }}" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Play">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                            @endif

                                            @if($download->status == 'failed')
                                                <form action="{{ route('downloads.retry', $download) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Retry">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(in_array($download->status, ['pending', 'processing', 'downloading']))
                                                <form action="{{ route('downloads.cancel', $download) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Cancel">
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

                <!-- Grid View -->
                <div class="row g-3" id="gridView" style="display:none;">
                    @foreach($downloads as $download)
                        <div class="col-md-4 col-sm-6">
                            <div class="download-card" style="border: 3px solid #212529; border-radius: 8px; overflow: hidden; height: 100%;">
                                <div class="download-header p-3 text-white"
                                    style="
                                        background: linear-gradient(45deg, var(--primary-gradient-start), var(--primary-gradient-end));
                                        border-bottom: 3px solid #212529;
                                    ">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="platform-icon">
                                            @if($download->platform == 'youtube')
                                                <i class="fab fa-youtube"></i>
                                            @elseif($download->platform == 'tiktok')
                                                <i class="fab fa-tiktok"></i>
                                            @elseif($download->platform == 'instagram')
                                                <i class="fab fa-instagram"></i>
                                            @else
                                                <i class="fas fa-link"></i>
                                            @endif
                                        </div>
                                        <x-status-badge :status="$download->status" />
                                    </div>
                                </div>
                                <div class="download-body p-3 bg-white">
                                    <h6 class="fw-bold text-truncate">{{ $download->title ?? 'Untitled' }}</h6>
                                    <div class="small text-muted mb-2">{{ $download->created_at->format('M d, Y') }}</div>
                                    <div class="d-flex justify-content-between align-items-center text-muted small mb-3">
                                        <span>{{ strtoupper($download->format) }}</span>
                                        <span>
                                            @if($download->file_size)
                                                {{ round($download->file_size / (1024 * 1024), 2) }} MB
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                    <div class="download-actions d-flex gap-2 justify-content-center mt-2">
                                        <a href="{{ route('downloads.show', $download) }}" class="btn btn-sm btn-outline-dark">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($download->status == 'completed')
                                            <a href="{{ route('downloads.file', $download) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('instance.show', $download) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        @endif

                                        @if($download->status == 'failed')
                                            <form action="{{ route('downloads.retry', $download) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if(in_array($download->status, ['pending', 'processing', 'downloading']))
                                            <form action="{{ route('downloads.cancel', $download) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $downloads->appends(request()->query())->links() }}
                </div>
            @else
                <x-empty-state
                    title="No Downloads Found"
                    message="You haven't made any downloads yet, or none match your filter criteria."
                    icon="fas fa-download"
                    action="true"
                    actionLink="{{ route('downloads.create') }}"
                    actionText="New Download"
                />
            @endif
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

        // View switcher
        $('#listViewBtn').click(function() {
            $('#gridView').hide();
            $('#listView').show();
            $(this).addClass('active');
            $('#gridViewBtn').removeClass('active');
            localStorage.setItem('downloadView', 'list');
        });

        $('#gridViewBtn').click(function() {
            $('#listView').hide();
            $('#gridView').show();
            $(this).addClass('active');
            $('#listViewBtn').removeClass('active');
            localStorage.setItem('downloadView', 'grid');
        });

        // Load preferred view from localStorage
        const savedView = localStorage.getItem('downloadView');
        if (savedView === 'grid') {
            $('#gridViewBtn').click();
        } else {
            $('#listViewBtn').click();
        }
    });
</script>
@endpush
