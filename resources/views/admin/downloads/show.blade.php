@extends('layouts.admin')

@section('title', 'Download Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <h1 class="mb-0 me-3">Download Details</h1>
        <span class="badge bg-primary fs-6 px-3 py-2" style="border: 3px solid var(--secondary);">
            ID: {{ $download->id }}
        </span>
    </div>
    <div>
        <a href="{{ route('admin.downloads.index') }}" class="neo-btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Downloads
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Details -->
    <div class="col-lg-8">
        <div class="neo-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i> Media Information
                </h5>
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
            </div>
            <div class="card-body">
                <!-- Media Preview -->
                @if($download->status == 'completed')
                    <div class="media-preview mb-4">
                        @if(in_array($download->format, ['mp4']))
                            <div class="video-container mb-3" style="border: 3px solid var(--secondary); border-radius: 8px; overflow: hidden; position: relative; padding-bottom: 56.25%; height: 0; box-shadow: 6px 6px 0 var(--shadow-color);">
                                <video width="100%" height="auto" controls style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                    <source src="{{ $download->storage_url }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @elseif(in_array($download->format, ['mp3']))
                            <div class="audio-container mb-3 p-4 bg-light d-flex align-items-center justify-content-center" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 6px 6px 0 var(--shadow-color);">
                                <div class="text-center w-100">
                                    <i class="fas fa-music fa-3x mb-3 text-primary"></i>
                                    <audio controls class="w-100">
                                        <source src="{{ $download->storage_url }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                            </div>
                        @else
                            <div class="unknown-media-container mb-3 p-4 bg-light text-center" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 6px 6px 0 var(--shadow-color);">
                                <i class="fas fa-file-download fa-3x mb-3 text-primary"></i>
                                <p class="mb-0">Preview not available for this format. Click download to access the file.</p>
                            </div>
                        @endif
                    </div>
                @elseif(in_array($download->status, ['pending', 'processing', 'downloading', 'uploading']))
                    <div class="processing-container mb-4 p-4 bg-light text-center" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 6px 6px 0 var(--shadow-color);">
                        <i class="fas fa-spinner fa-spin fa-3x mb-3 text-primary"></i>
                        <h5>Download is processing</h5>
                        <p class="mb-0">This download is currently being processed.</p>
                    </div>
                @elseif($download->status == 'failed')
                    <div class="failed-container mb-4 p-4 bg-danger bg-opacity-10 text-center" style="border: 3px solid var(--danger); border-radius: 8px; box-shadow: 6px 6px 0 rgba(255, 44, 85, 0.2);">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3 text-danger"></i>
                        <h5 class="text-danger">Download Failed</h5>
                        <p class="mb-0">{{ $download->error_message ?? 'Unknown error occurred during processing.' }}</p>
                    </div>
                @elseif($download->status == 'cancelled')
                    <div class="cancelled-container mb-4 p-4 bg-secondary bg-opacity-10 text-center" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 6px 6px 0 var(--shadow-color);">
                        <i class="fas fa-ban fa-3x mb-3 text-secondary"></i>
                        <h5>Download Cancelled</h5>
                        <p class="mb-0">This download was cancelled by the user.</p>
                    </div>
                @endif

                <!-- Download Details Table -->
                <div class="table-responsive">
                    <table class="table table-bordered admin-details-table">
                        <tbody>
                            <tr>
                                <th class="table-label">Title</th>
                                <td>{{ $download->title ?? 'Untitled' }}</td>
                            </tr>
                            <tr>
                                <th class="table-label">URL</th>
                                <td>
                                    <a href="{{ $download->url }}" target="_blank" class="text-break text-decoration-none">
                                        {{ $download->url }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-label">Platform</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($download->platform == 'youtube')
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
                            </tr>
                            <tr>
                                <th class="table-label">Format</th>
                                <td>
                                    @if(strpos($download->format, 'mp4') !== false)
                                        <span class="badge bg-primary format-badge">
                                            <i class="fas fa-video me-1"></i> MP4
                                        </span>
                                        @if($download->quality)
                                            <small class="text-muted ms-2">{{ $download->quality }}</small>
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
                                </td>
                            </tr>
                            <tr>
                                <th class="table-label">File Size</th>
                                <td>
                                    @if($download->file_size)
                                        <span class="fw-bold">{{ round($download->file_size / (1024 * 1024), 2) }} MB</span>
                                    @else
                                        <span class="text-muted">Not available</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="table-label">Duration</th>
                                <td>
                                    @if($download->duration)
                                        @php
                                            $minutes = floor($download->duration / 60);
                                            $seconds = $download->duration % 60;
                                            $formatted = sprintf('%02d:%02d', $minutes, $seconds);
                                        @endphp
                                        <span class="fw-bold">{{ $formatted }}</span>
                                        <small class="text-muted ms-2">({{ $download->duration }} seconds)</small>
                                    @else
                                        <span class="text-muted">Not available</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="table-label">Token Cost</th>
                                <td>
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2" style="border: 2px solid var(--secondary);">
                                        <i class="fas fa-coins me-1"></i> {{ $download->token_cost ?? '0' }} tokens
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="table-label">Created</th>
                                <td>{{ $download->created_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th class="table-label">Updated</th>
                                <td>{{ $download->updated_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                            @if($download->completed_at)
                            <tr>
                                <th class="table-label">Completed</th>
                                <td>{{ $download->completed_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                            @endif
                            @if($download->status == 'failed' && $download->error_message)
                            <tr>
                                <th class="table-label">Error Message</th>
                                <td class="text-danger fw-bold">{{ $download->error_message }}</td>
                            </tr>
                            @endif
                            @if($download->file_path)
                            <tr>
                                <th class="table-label">Storage Path</th>
                                <td><code class="bg-light p-2 rounded">{{ $download->file_path }}</code></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- System Details Card (for admins) -->
                <div class="system-details mt-4">
                    <h5 class="mb-3">
                        <i class="fas fa-server me-2"></i> System Details
                    </h5>
                    <div class="p-3" style="border: 3px solid var(--secondary); border-radius: 8px; background-color: var(--light); box-shadow: 4px 4px 0 var(--shadow-color);">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="detail-item mb-2">
                                    <span class="fw-bold text-muted">IP Address:</span>
                                    <div>{{ $download->ip_address ?? 'Not recorded' }}</div>
                                </div>
                                <div class="detail-item">
                                    <span class="fw-bold text-muted">User Agent:</span>
                                    <div class="text-truncate" style="max-width: 100%;">
                                        {{ $download->user_agent ?? 'Not recorded' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item mb-2">
                                    <span class="fw-bold text-muted">Job ID:</span>
                                    <div>{{ $download->job_id ?? 'Not available' }}</div>
                                </div>
                                <div class="detail-item">
                                    <span class="fw-bold text-muted">API Response:</span>
                                    <div>
                                        @if($download->api_response)
                                            <button type="button" class="neo-btn btn-sm btn-secondary mt-1" data-bs-toggle="modal" data-bs-target="#apiResponseModal">
                                                <i class="fas fa-code me-1"></i> View Response
                                            </button>
                                        @else
                                            Not available
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="neo-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i> Activity Log
                </h5>
            </div>
            <div class="card-body p-0">
                @if(isset($activities) && count($activities) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Action</th>
                                    <th>Details</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                    <tr>
                                        <td>
                                            @php
                                                $actionClass = match(true) {
                                                    str_contains($activity->action, 'completed') => 'text-success',
                                                    str_contains($activity->action, 'started') => 'text-primary',
                                                    str_contains($activity->action, 'fail') || str_contains($activity->action, 'error') => 'text-danger',
                                                    str_contains($activity->action, 'cancel') => 'text-secondary',
                                                    default => ''
                                                }
                                            @endphp
                                            <span class="{{ $actionClass }} fw-bold">
                                                {{ str_replace('_', ' ', ucwords($activity->action)) }}
                                            </span>
                                        </td>
                                        <td>{{ $activity->details }}</td>
                                        <td>{{ $activity->created_at->format('M d, Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center">
                        <i class="fas fa-history fa-2x mb-3 text-muted"></i>
                        <p class="mb-0">No activity logs available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- User Card -->
        <div class="neo-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i> User Information
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($download->user->name) }}&size=64&background=2B7EC1&color=fff"
                        class="rounded-circle me-3" alt="{{ $download->user->name }}"
                        style="border: 3px solid var(--secondary); width: 64px; height: 64px;">
                    <div>
                        <h5 class="mb-1">{{ $download->user->name }}</h5>
                        <p class="mb-0 text-muted">{{ $download->user->email }}</p>
                    </div>
                </div>

                <div class="user-stats mb-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="stats-mini">
                                <div class="stat-value">{{ $userStats['total_downloads'] ?? '0' }}</div>
                                <div class="stat-label">Total Downloads</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-mini">
                                <div class="stat-value">{{ $download->user->token_balance }}</div>
                                <div class="stat-label">Token Balance</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.show', $download->user_id) }}" class="neo-btn">
                        <i class="fas fa-user me-2"></i> View User Profile
                    </a>
                    <a href="{{ route('admin.users.downloads', $download->user_id) }}" class="neo-btn btn-secondary">
                        <i class="fas fa-download me-2"></i> User's Downloads
                    </a>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="neo-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i> Admin Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    @if($download->status == 'completed')
                        <a href="{{ route('downloads.file', $download) }}" class="neo-btn">
                            <i class="fas fa-download me-2"></i> Download File
                        </a>

                        <a href="{{ route('instance.show', $download) }}" class="neo-btn">
                            <i class="fas fa-play me-2"></i> Media Player
                        </a>
                    @endif

                    @if($download->status == 'failed')
                        <button type="button" class="neo-btn" data-bs-toggle="modal" data-bs-target="#retryModal">
                            <i class="fas fa-redo me-2"></i> Retry Download
                        </button>
                    @endif

                    @if(in_array($download->status, ['pending', 'processing']))
                        <button type="button" class="neo-btn btn-warning" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="fas fa-stop-circle me-2"></i> Cancel Processing
                        </button>
                    @endif

                    <form action="{{ route('admin.downloads.delete', $download) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="neo-btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i> Delete Download
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Other Downloads Card -->
        <div class="neo-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i> User's Recent Downloads
                </h5>
            </div>
            <div class="card-body p-0">
                @if(isset($recentDownloads) && count($recentDownloads) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentDownloads as $recentDownload)
                            @if($recentDownload->id != $download->id)
                                <a href="{{ route('admin.downloads.show', $recentDownload) }}" class="list-group-item list-group-item-action border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($recentDownload->platform == 'youtube')
                                                <i class="fab fa-youtube text-danger"></i>
                                            @elseif($recentDownload->platform == 'tiktok')
                                                <i class="fab fa-tiktok"></i>
                                            @elseif($recentDownload->platform == 'instagram')
                                                <i class="fab fa-instagram" style="color: #e4405f;"></i>
                                            @else
                                                <i class="fas fa-link text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <div class="text-truncate" style="max-width: 180px;">
                                                    {{ $recentDownload->title ?? 'Untitled' }}
                                                </div>
                                                <small class="text-muted">{{ $recentDownload->created_at->format('M d') }}</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <small class="text-muted">{{ strtoupper($recentDownload->format) }}</small>
                                                <span class="status-badge-mini status-{{ $recentDownload->status }}">
                                                    {{ ucfirst($recentDownload->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="p-4 text-center">
                        <i class="fas fa-download fa-2x mb-3 text-muted"></i>
                        <p class="mb-0">No other downloads found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- API Response Modal -->
<div class="modal fade" id="apiResponseModal" tabindex="-1" aria-labelledby="apiResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 8px 8px 0 var(--shadow-color);">
            <div class="modal-header bg-primary-gradient text-white" style="border-bottom: 3px solid var(--secondary);">
                <h5 class="modal-title" id="apiResponseModalLabel">
                    <i class="fas fa-code me-2"></i> API Response
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre class="bg-light p-3 rounded" style="border: 2px solid var(--secondary); max-height: 400px; overflow-y: auto;"><code>{{ json_encode(json_decode($download->api_response), JSON_PRETTY_PRINT) }}</code></pre>
            </div>
            <div class="modal-footer" style="border-top: 3px solid var(--secondary);">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Retry Modal -->
<div class="modal fade" id="retryModal" tabindex="-1" aria-labelledby="retryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 8px 8px 0 var(--shadow-color);">
            <div class="modal-header bg-primary-gradient text-white" style="border-bottom: 3px solid var(--secondary);">
                <h5 class="modal-title" id="retryModalLabel">
                    <i class="fas fa-redo me-2"></i> Retry Download
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to retry this download?</p>
                <div class="alert alert-info" style="border: 2px solid var(--info); border-radius: 8px; box-shadow: 3px 3px 0 rgba(86, 197, 237, 0.2);">
                    <i class="fas fa-info-circle me-2"></i> This will create a new download job and attempt to process it again.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 3px solid var(--secondary);">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('downloads.retry', $download) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="neo-btn">Retry Download</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 8px 8px 0 var(--shadow-color);">
            <div class="modal-header bg-warning text-dark" style="border-bottom: 3px solid var(--secondary);">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="fas fa-stop-circle me-2"></i> Cancel Processing
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this download?</p>
                <div class="alert alert-warning" style="border: 2px solid var(--warning); border-radius: 8px; box-shadow: 3px 3px 0 rgba(255, 202, 40, 0.2);">
                    <i class="fas fa-exclamation-triangle me-2"></i> This will attempt to cancel any ongoing processing and mark the download as cancelled.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 3px solid var(--secondary);">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">No, Keep Processing</button>
                <form action="{{ route('downloads.cancel', $download) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="neo-btn btn-warning">Yes, Cancel Download</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 8px 8px 0 var(--shadow-color);">
            <div class="modal-header bg-danger text-white" style="border-bottom: 3px solid var(--secondary);">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-trash me-2"></i> Delete Download
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete this download?</p>
                <div class="alert alert-danger" style="border: 2px solid var(--danger); border-radius: 8px; box-shadow: 3px 3px 0 rgba(255, 44, 85, 0.2);">
                    <i class="fas fa-exclamation-triangle me-2"></i> This action cannot be undone. The associated file will also be deleted from storage.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 3px solid var(--secondary);">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="neo-btn btn-danger" onclick="document.getElementById('deleteForm').submit();">
                    Delete Permanently
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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

/* Mini Status Badge */
.status-badge-mini {
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    border: 1px solid var(--secondary);
    text-transform: uppercase;
    font-weight: 600;
}

/* Table Styles */
.admin-details-table {
    border: 3px solid var(--secondary);
    border-radius: 8px;
    overflow: hidden;
}

.admin-details-table .table-label {
    width: 150px;
    background-color: var(--light-gray);
    font-weight: 700;
    border-right: 2px solid var(--secondary);
}

.admin-details-table td {
    padding: 12px 15px;
}

.admin-details-table tr {
    border-bottom: 1px solid #dee2e6;
}

/* Stats Mini */
.stats-mini {
    padding: 15px;
    text-align: center;
    border: 2px solid var(--secondary);
    border-radius: 8px;
    background: white;
    box-shadow: 3px 3px 0 var(--shadow-color);
}

.stats-mini .stat-value {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary);
}

.stats-mini .stat-label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
}

/* Custom Button Variants */
.neo-btn.btn-warning {
    background: var(--warning);
    color: var(--secondary);
}

.neo-btn.btn-danger {
    background: var(--danger);
    color: white;
}

/* List Group Items */
.list-group-item-action:hover {
    background-color: var(--light-gray);
    transform: translateX(5px);
    transition: all 0.2s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-details-table .table-label {
        width: 120px;
        font-size: 12px;
    }

    .stats-mini {
        padding: 10px;
    }

    .stats-mini .stat-value {
        font-size: 16px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh for processing downloads
    @if(in_array($download->status, ['pending', 'processing']))
        setTimeout(() => {
            location.reload();
        }, 30000); // Refresh every 30 seconds
    @endif
});
</script>
@endpush
