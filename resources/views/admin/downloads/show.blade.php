
<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\downloads\show.blade.php -->
@extends('layouts.admin')

@section('title', 'Download Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Download Details <span class="badge bg-primary" style="font-size: 16px; border: 2px solid #212529;">ID: {{ $download->id }}</span></h1>
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
                    <h5 class="mb-0">Media Information</h5>
                    <x-status-badge :status="$download->status" />
                </div>
                <div class="card-body">
                    <!-- Media Preview -->
                    @if($download->status == 'completed')
                        <div class="media-preview mb-4">
                            @if(in_array($download->format, ['mp4']))
                                <div class="video-container mb-3" style="border: 3px solid #212529; border-radius: 8px; overflow: hidden; position: relative; padding-bottom: 56.25%; height: 0;">
                                    <video width="100%" height="auto" controls style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                        <source src="{{ $download->storage_url }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @elseif(in_array($download->format, ['mp3']))
                                <div class="audio-container mb-3 p-4 bg-light d-flex align-items-center justify-content-center" style="border: 3px solid #212529; border-radius: 8px;">
                                    <div class="text-center w-100">
                                        <i class="fas fa-music fa-3x mb-3 text-primary"></i>
                                        <audio controls class="w-100">
                                            <source src="{{ $download->storage_url }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                            @else
                                <div class="unknown-media-container mb-3 p-4 bg-light text-center" style="border: 3px solid #212529; border-radius: 8px;">
                                    <i class="fas fa-file-download fa-3x mb-3 text-primary"></i>
                                    <p>Preview not available for this format. Click download to access the file.</p>
                                </div>
                            @endif
                        </div>
                    @elseif(in_array($download->status, ['pending', 'processing', 'downloading', 'uploading']))
                        <div class="processing-container mb-4 p-4 bg-light text-center" style="border: 3px solid #212529; border-radius: 8px;">
                            <i class="fas fa-spinner fa-spin fa-3x mb-3 text-primary"></i>
                            <h5>Download is processing</h5>
                            <p class="mb-0">This download is currently being processed.</p>
                        </div>
                    @elseif($download->status == 'failed')
                        <div class="failed-container mb-4 p-4 bg-light text-center" style="border: 3px solid #212529; border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3 text-danger"></i>
                            <h5>Download Failed</h5>
                            <p class="mb-0">{{ $download->error_message ?? 'Unknown error occurred during processing.' }}</p>
                        </div>
                    @elseif($download->status == 'cancelled')
                        <div class="cancelled-container mb-4 p-4 bg-light text-center" style="border: 3px solid #212529; border-radius: 8px;">
                            <i class="fas fa-ban fa-3x mb-3 text-secondary"></i>
                            <h5>Download Cancelled</h5>
                            <p class="mb-0">This download was cancelled by the user.</p>
                        </div>
                    @endif

                    <!-- Download Details Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border: 2px solid #212529;">
                            <tbody>
                                <tr>
                                    <th style="width: 150px; background-color: #f8f9fa;">Title</th>
                                    <td>{{ $download->title ?? 'Untitled' }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">URL</th>
                                    <td>
                                        <a href="{{ $download->url }}" target="_blank" class="text-break">
                                            {{ $download->url }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Platform</th>
                                    <td>
                                        @if($download->platform == 'youtube')
                                            <span class="platform-badge youtube">
                                                <i class="fab fa-youtube"></i>
                                            </span>
                                            YouTube
                                        @elseif($download->platform == 'tiktok')
                                            <span class="platform-badge tiktok">
                                                <i class="fab fa-tiktok"></i>
                                            </span>
                                            TikTok
                                        @elseif($download->platform == 'instagram')
                                            <span class="platform-badge instagram">
                                                <i class="fab fa-instagram"></i>
                                            </span>
                                            Instagram
                                        @else
                                            <span class="platform-badge other">
                                                <i class="fas fa-link"></i>
                                            </span>
                                            {{ ucfirst($download->platform) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Format</th>
                                    <td>
                                        @if(strpos($download->format, 'mp4') !== false)
                                            <span class="badge bg-primary" style="border: 1px solid #212529;">MP4</span>
                                            <small>{{ $download->quality }}</small>
                                        @elseif(strpos($download->format, 'mp3') !== false)
                                            <span class="badge bg-success" style="border: 1px solid #212529;">MP3</span>
                                        @else
                                            <span class="badge bg-secondary" style="border: 1px solid #212529;">{{ strtoupper($download->format) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">File Size</th>
                                    <td>
                                        @if($download->file_size)
                                            {{ round($download->file_size / (1024 * 1024), 2) }} MB
                                        @else
                                            Not available
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Duration</th>
                                    <td>
                                        @if($download->duration)
                                            @php
                                                $minutes = floor($download->duration / 60);
                                                $seconds = $download->duration % 60;
                                                $formatted = sprintf('%02d:%02d', $minutes, $seconds);
                                            @endphp
                                            {{ $formatted }} ({{ $download->duration }} seconds)
                                        @else
                                            Not available
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Token Cost</th>
                                    <td>
                                        <span class="badge bg-warning" style="border: 1px solid #212529; font-size: 14px;">
                                            {{ $download->token_cost ?? '0' }} tokens
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Created</th>
                                    <td>{{ $download->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f8f9fa;">Updated</th>
                                    <td>{{ $download->updated_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                @if($download->completed_at)
                                <tr>
                                    <th style="background-color: #f8f9fa;">Completed</th>
                                    <td>{{ $download->completed_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                @endif
                                @if($download->status == 'failed' && $download->error_message)
                                <tr>
                                    <th style="background-color: #f8f9fa;">Error Message</th>
                                    <td class="text-danger">{{ $download->error_message }}</td>
                                </tr>
                                @endif
                                @if($download->file_path)
                                <tr>
                                    <th style="background-color: #f8f9fa;">Storage Path</th>
                                    <td><code>{{ $download->file_path }}</code></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- System Details Card (for admins) -->
                    <div class="system-details mt-4">
                        <h5 class="mb-3">System Details</h5>
                        <div class="p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="detail-item mb-2">
                                        <span class="fw-bold">IP Address:</span>
                                        {{ $download->ip_address ?? 'Not recorded' }}
                                    </div>
                                    <div class="detail-item">
                                        <span class="fw-bold">User Agent:</span>
                                        <span class="text-truncate d-block" style="max-width: 100%;">
                                            {{ $download->user_agent ?? 'Not recorded' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-item mb-2">
                                        <span class="fw-bold">Job ID:</span>
                                        {{ $download->job_id ?? 'Not available' }}
                                    </div>
                                    <div class="detail-item">
                                        <span class="fw-bold">API Response:</span>
                                        @if($download->api_response)
                                            <button type="button" class="neo-btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#apiResponseModal">
                                                View Response
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

            <!-- Activity Log -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Activity Log</h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($activities) && count($activities) > 0)
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
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
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($download->user->name) }}&size=64&background=ff4b2b&color=fff"
                            class="rounded-circle me-3" alt="{{ $download->user->name }}"
                            style="border: 3px solid #212529; width: 64px; height: 64px;">
                        <div>
                            <h5 class="mb-1">{{ $download->user->name }}</h5>
                            <p class="mb-0">{{ $download->user->email }}</p>
                        </div>
                    </div>

                    <div class="user-stats mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="p-2 text-center" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                                    <h6 class="mb-0">{{ $userStats['total_downloads'] ?? '0' }}</h6>
                                    <small>Total Downloads</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 text-center" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                                    <h6 class="mb-0">{{ $download->user->token_balance }}</h6>
                                    <small>Token Balance</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.show', $download->user_id) }}" class="neo-btn">
                            <i class="fas fa-user me-2"></i> View User Profile
                        </a>
                        <a href="{{ route('admin.users.downloads', $download->user_id) }}" class="neo-btn btn-secondary">
                            <i class="fas fa-download me-2"></i> View User's Downloads
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Admin Actions</h5>
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
                    <h5 class="mb-0">User's Recent Downloads</h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($recentDownloads) && count($recentDownloads) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentDownloads as $recentDownload)
                                @if($recentDownload->id != $download->id)
                                    <a href="{{ route('admin.downloads.show', $recentDownload) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($recentDownload->platform == 'youtube')
                                                    <i class="fab fa-youtube text-danger"></i>
                                                @elseif($recentDownload->platform == 'tiktok')
                                                    <i class="fab fa-tiktok"></i>
                                                @elseif($recentDownload->platform == 'instagram')
                                                    <i class="fab fa-instagram text-purple"></i>
                                                @else
                                                    <i class="fas fa-link"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <div class="text-truncate" style="max-width: 180px;">
                                                        {{ $recentDownload->title ?? 'Untitled' }}
                                                    </div>
                                                    <small class="text-muted">{{ $recentDownload->created_at->format('M d') }}</small>
                                                </div>
                                                <div>
                                                    <small>{{ strtoupper($recentDownload->format) }}</small>
                                                    <x-status-badge :status="$recentDownload->status" class="badge-sm" />
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
</div>

<!-- API Response Modal -->
<div class="modal fade" id="apiResponseModal" tabindex="-1" aria-labelledby="apiResponseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title" id="apiResponseModalLabel">API Response</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre style="background-color: #f8f9fa; padding: 1rem; border: 2px solid #212529; border-radius: 8px; max-height: 400px; overflow-y: auto;"><code>{{ json_encode(json_decode($download->api_response), JSON_PRETTY_PRINT) }}</code></pre>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #212529;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Retry Modal -->
<div class="modal fade" id="retryModal" tabindex="-1" aria-labelledby="retryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title" id="retryModalLabel">Retry Download</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to retry this download?</p>
                <div class="alert alert-info" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                    <i class="fas fa-info-circle me-2"></i> This will create a new download job and attempt to process it again.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #212529;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('downloads.retry', $download) }}" method="POST">
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
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Processing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this download?</p>
                <div class="alert alert-warning" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                    <i class="fas fa-exclamation-triangle me-2"></i> This will attempt to cancel any ongoing processing and mark the download as cancelled.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #212529;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">No, Keep Processing</button>
                <form action="{{ route('downloads.cancel', $download) }}" method="POST">
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
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 8px;">
            <div class="modal-header" style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%); border-bottom: 2px solid #212529;">
                <h5 class="modal-title" id="deleteModalLabel">Delete Download</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete this download?</p>
                <div class="alert alert-danger" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                    <i class="fas fa-exclamation-triangle me-2"></i> This action cannot be undone. The associated file will also be deleted from storage.
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #212529;">
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
    .platform-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 4px;
        border: 1px solid #121212;
        box-shadow: 2px 2px 0 rgba(0,0,0,0.1);
        color: white;
        font-size: 12px;
        margin-right: 6px;
    }

    .platform-badge.youtube {
        background-color: #ff0000;
    }

    .platform-badge.tiktok {
        background-color: #000000;
    }

    .platform-badge.instagram {
        background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
    }

    .platform-badge.other {
        background-color: #6c757d;
    }

    .badge-sm {
        font-size: 10px;
        padding: 3px 6px;
    }

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

    .neo-btn.btn-secondary {
        background: #f8f9fa;
    }

    .neo-btn.btn-warning {
        background: linear-gradient(90deg, #f6d365 0%, #fda085 100%);
    }

    .neo-btn.btn-danger {
        background: linear-gradient(90deg, #ff6b6b 0%, #ff8e8e 100%);
    }
</style>
@endpush
