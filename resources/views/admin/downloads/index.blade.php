@extends('layouts.admin')

@section('title', 'Downloads Management')

@section('content')
<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stats-card">
            <div class="stat-icon">
                <i class="fas fa-download"></i>
            </div>
            <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
            <div class="stat-label">Total Downloads</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['completed'] ?? 0 }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card">
            <div class="stat-icon">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="stat-value">{{ $stats['processing'] ?? 0 }}</div>
            <div class="stat-label">Processing</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stats-card">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value">{{ $stats['failed'] ?? 0 }}</div>
            <div class="stat-label">Failed</div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="neo-card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i> Filter Downloads
        </h5>
    </div>
    <div class="card-body" style="padding: 24px;">
        <form action="{{ route('admin.downloads.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Status</label>
                <select name="status" class="neo-form-control">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
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
                <label class="form-label fw-bold">User</label>
                <input type="text" name="user" class="neo-form-control" placeholder="Email or username" value="{{ request('user') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">Format</label>
                <select name="format" class="neo-form-control">
                    <option value="">All Formats</option>
                    <option value="mp4" {{ request('format') == 'mp4' ? 'selected' : '' }}>MP4 (Video)</option>
                    <option value="mp3" {{ request('format') == 'mp3' ? 'selected' : '' }}>MP3 (Audio)</option>
                    <option value="other" {{ request('format') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">Date From</label>
                <input type="date" name="from_date" class="neo-form-control" value="{{ request('from_date') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">Date To</label>
                <input type="date" name="to_date" class="neo-form-control" value="{{ request('to_date') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">Sort By</label>
                <select name="sort" class="neo-form-control">
                    <option value="created_at_desc" {{ request('sort', 'created_at_desc') == 'created_at_desc' ? 'selected' : '' }}>Newest First</option>
                    <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Oldest First</option>
                    <option value="file_size_desc" {{ request('sort') == 'file_size_desc' ? 'selected' : '' }}>Largest Size</option>
                    <option value="file_size_asc" {{ request('sort') == 'file_size_asc' ? 'selected' : '' }}>Smallest Size</option>
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <div class="d-flex gap-2 w-100">
                    <button type="submit" class="neo-btn flex-fill">
                        <i class="fas fa-search me-2"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.downloads.index') }}" class="neo-btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Downloads List -->
<div class="neo-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <i class="fas fa-download me-2"></i> Downloads
                @if(request()->has('status') || request()->has('platform') || request()->has('user') || request()->has('format') || request()->has('from_date') || request()->has('to_date'))
                    <span class="badge bg-warning text-dark ms-2" style="border: 2px solid var(--secondary); font-size: 12px;">
                        <i class="fas fa-filter me-1"></i> Filtered
                    </span>
                @endif
            </h5>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-info" style="border: 2px solid var(--secondary); font-size: 14px;">
                <i class="fas fa-database me-1"></i> {{ $downloads->total() }} Results
            </span>
            <button class="neo-btn btn-sm" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($downloads->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 admin-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th style="width: 25%;">Download Details</th>
                            <th>User</th>
                            <th>Platform</th>
                            <th>Format</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($downloads as $download)
                            <tr class="download-row">
                                <td class="fw-bold text-primary">#{{ $download->id }}</td>
                                <td>
                                    <div class="download-details">
                                        <div class="text-truncate fw-bold" style="max-width: 300px;" data-bs-toggle="tooltip" title="{{ $download->title ?? 'Untitled' }}">
                                            <a href="{{ $download->url }}" target="_blank" class="text-decoration-none text-dark">
                                                {{ $download->title ?? 'Untitled' }}
                                            </a>
                                        </div>
                                        <div class="small text-muted text-truncate" style="max-width: 300px;">
                                            <i class="fas fa-link me-1"></i>
                                            {{ $download->url }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $download->user_id) }}" class="d-flex align-items-center text-decoration-none user-link">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($download->user->name) }}&size=32&background=2B7EC1&color=fff"
                                             class="rounded-circle me-2 user-avatar" alt="{{ $download->user->name }}">
                                        <div>
                                            <div class="fw-bold">{{ $download->user->name }}</div>
                                            <div class="small text-muted">{{ $download->user->email }}</div>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($download->platform == 'youtube')
                                            <span class="platform-badge youtube">
                                                <i class="fab fa-youtube"></i>
                                            </span>
                                            <span class="fw-bold">YouTube</span>
                                        @elseif($download->platform == 'tiktok')
                                            <span class="platform-badge tiktok">
                                                <i class="fab fa-tiktok"></i>
                                            </span>
                                            <span class="fw-bold">TikTok</span>
                                        @elseif($download->platform == 'instagram')
                                            <span class="platform-badge instagram">
                                                <i class="fab fa-instagram"></i>
                                            </span>
                                            <span class="fw-bold">Instagram</span>
                                        @else
                                            <span class="platform-badge other">
                                                <i class="fas fa-link"></i>
                                            </span>
                                            <span class="fw-bold">{{ ucfirst($download->platform) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="format-info">
                                        @if (strpos($download->format, 'mp4') !== false)
                                            <span class="badge bg-primary format-badge">
                                                <i class="fas fa-video me-1"></i> MP4
                                            </span>
                                            @if($download->quality)
                                                <div class="small text-muted mt-1">{{ $download->quality }}</div>
                                            @endif
                                        @elseif(strpos($download->format, 'mp3') !== false)
                                            <span class="badge bg-success format-badge">
                                                <i class="fas fa-music me-1"></i> MP3
                                            </span>
                                        @else
                                            <span class="badge bg-secondary format-badge">
                                                <i class="fas fa-file me-1"></i> {{ strtoupper($download->format) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($download->file_size)
                                        <span class="fw-bold">{{ round($download->file_size / (1024 * 1024), 2) }} MB</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $download->status }}">
                                        @switch($download->status)
                                            @case('pending')
                                                <i class="fas fa-clock me-1"></i>
                                                @break
                                            @case('processing')
                                                <i class="fas fa-spinner fa-spin me-1"></i>
                                                @break
                                            @case('completed')
                                                <i class="fas fa-check-circle me-1"></i>
                                                @break
                                            @case('failed')
                                                <i class="fas fa-times-circle me-1"></i>
                                                @break
                                            @case('cancelled')
                                                <i class="fas fa-ban me-1"></i>
                                                @break
                                        @endswitch
                                        {{ ucfirst($download->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="created-time">
                                        <div class="fw-bold">{{ $download->created_at->format('M d, Y') }}</div>
                                        <div class="small text-muted">{{ $download->created_at->format('H:i:s') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.downloads.show', $download) }}"
                                               class="btn btn-sm btn-outline-primary action-btn"
                                               data-bs-toggle="tooltip" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($download->status == 'completed')
                                                <a href="{{ route('downloads.file', $download) }}"
                                                   class="btn btn-sm btn-outline-success action-btn"
                                                   data-bs-toggle="tooltip" title="Download File">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger action-btn"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Download"
                                                    onclick="deleteDownload({{ $download->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center py-4">
                {{ $downloads->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state p-5 text-center">
                <div class="empty-icon mb-3">
                    <i class="fas fa-download fa-4x text-muted"></i>
                </div>
                <h4 class="mb-2">No Downloads Found</h4>
                <p class="text-muted mb-0">No downloads found matching your filter criteria.</p>
                @if(request()->hasAny(['status', 'platform', 'user', 'format', 'from_date', 'to_date']))
                    <a href="{{ route('admin.downloads.index') }}" class="neo-btn mt-3">
                        <i class="fas fa-times me-2"></i> Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enhanced Admin Table Styles */
.admin-table {
    border-collapse: separate;
    border-spacing: 0;
}

.admin-table th {
    background: var(--light-gray);
    border-bottom: 3px solid var(--secondary);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 15px 12px;
    font-size: 12px;
}

.admin-table td {
    border-bottom: 1px solid #dee2e6;
    padding: 15px 12px;
    vertical-align: middle;
}

.download-row:hover {
    background-color: #f8f9fa;
}

/* Platform Badges */
.platform-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: 2px solid var(--secondary);
    box-shadow: 2px 2px 0 rgba(0, 0, 0, 0.1);
    color: white;
    font-size: 14px;
    margin-right: 8px;
}

.platform-badge.youtube { background: #ff0000; }
.platform-badge.tiktok { background: #000000; }
.platform-badge.instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
.platform-badge.other { background: #6c757d; }

/* Format Badges */
.format-badge {
    border: 2px solid var(--secondary) !important;
    font-weight: 600;
    padding: 4px 8px;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    border: 2px solid var(--secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending { background: var(--warning); color: var(--secondary); }
.status-processing { background: var(--info); color: white; }
.status-completed { background: var(--success); color: white; }
.status-failed { background: var(--danger); color: white; }
.status-cancelled { background: #6c757d; color: white; }

/* User Avatar */
.user-avatar {
    border: 2px solid var(--secondary);
    width: 32px;
    height: 32px;
}

.user-link:hover .user-avatar {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

/* Action Buttons */
.action-btn {
    border: 2px solid currentColor !important;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 0 currentColor;
}

/* Empty State */
.empty-state {
    border: 3px dashed #dee2e6;
    border-radius: 12px;
    margin: 20px;
}

.empty-icon {
    opacity: 0.3;
}

/* Download Details */
.download-details a:hover {
    color: var(--primary) !important;
}

/* Time Display */
.created-time {
    min-width: 100px;
}

/* Responsive Improvements */
@media (max-width: 768px) {
    .admin-table {
        font-size: 12px;
    }

    .admin-table th,
    .admin-table td {
        padding: 8px 6px;
    }

    .platform-badge {
        width: 24px;
        height: 24px;
        font-size: 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function deleteDownload(downloadId) {
    if (confirm('Are you sure you want to delete this download?')) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/downloads/${downloadId}`;

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfToken);

        // Add method override
        const methodOverride = document.createElement('input');
        methodOverride.type = 'hidden';
        methodOverride.name = '_method';
        methodOverride.value = 'DELETE';
        form.appendChild(methodOverride);

        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-refresh every 30 seconds for processing downloads
setInterval(function() {
    if (document.querySelector('.status-processing')) {
        location.reload();
    }
}, 30000);
</script>
@endpush
