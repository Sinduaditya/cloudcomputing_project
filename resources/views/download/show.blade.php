<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\download\show.blade.php -->
@extends('layouts.app')

@section('title', $download->title ?? 'Download Details')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 text-truncate">{{ $download->title ?? 'Download Details' }}</h1>
        <div>
            <a href="{{ route('downloads.index') }}" class="neo-btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Downloads
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Content Card -->
            <div class="neo-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Download Details</h5>
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
                                    <p>Preview not available for this format. Click download to access your file.</p>
                                </div>
                            @endif
                        </div>
                    @elseif(in_array($download->status, ['pending', 'processing', 'downloading', 'uploading']))
                        <div class="processing-container mb-4 p-4 bg-light text-center" style="border: 3px solid #212529; border-radius: 8px;">
                            <i class="fas fa-spinner fa-spin fa-3x mb-3 text-primary"></i>
                            <h5>Your download is processing</h5>
                            <p class="mb-0">We're working on your download. This might take a few moments.</p>
                        </div>
                    @elseif($download->status == 'failed')
                        <div class="failed-container mb-4 p-4 bg-light text-center" style="border: 3px solid #212529; border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3 text-danger"></i>
                            <h5>Download Failed</h5>
                            <p>{{ $download->error_message ?? 'There was an error processing your download.' }}</p>
                            <form action="{{ route('downloads.retry', $download) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="neo-btn">
                                    <i class="fas fa-redo me-2"></i> Retry Download
                                </button>
                            </form>
                        </div>
                    @elseif($download->status == 'cancelled')
                        <div class="cancelled-container mb-4 p-4 bg-light text-center" style="border: 3px solid #212529; border-radius: 8px;">
                            <i class="fas fa-ban fa-3x mb-3 text-secondary"></i>
                            <h5>Download Cancelled</h5>
                            <p>This download was cancelled. Your tokens have been refunded.</p>
                            <form action="{{ route('downloads.retry', $download) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="neo-btn">
                                    <i class="fas fa-redo me-2"></i> Try Again
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Download Details -->
                    <div class="download-details mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered" style="border: 2px solid #212529;">
                                    <tr>
                                        <th style="width: 140px; background-color: #f8f9fa;">URL</th>
                                        <td>
                                            <div class="text-truncate" style="max-width: 250px;" data-bs-toggle="tooltip" title="{{ $download->url }}">
                                                <a href="{{ $download->url }}" target="_blank" class="text-decoration-none">
                                                    {{ $download->url }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #f8f9fa;">Platform</th>
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
                                    </tr>
                                    <tr>
                                        <th style="background-color: #f8f9fa;">Format</th>
                                        <td>
                                            @if($download->format == 'mp4')
                                                <span class="badge bg-primary" style="border: 1px solid #212529;">MP4</span>
                                                <small class="ms-1">{{ $download->quality }}</small>
                                            @elseif($download->format == 'mp3')
                                                <span class="badge bg-success" style="border: 1px solid #212529;">MP3</span>
                                            @else
                                                <span class="badge bg-secondary" style="border: 1px solid #212529;">{{ strtoupper($download->format) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered" style="border: 2px solid #212529;">
                                    <tr>
                                        <th style="width: 140px; background-color: #f8f9fa;">Created</th>
                                        <td>{{ $download->created_at->format('M d, Y h:i A') }}</td>
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
                                                Unknown
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #f8f9fa;">File Size</th>
                                        <td>
                                            @if($download->file_size)
                                                {{ round($download->file_size / (1024 * 1024), 2) }} MB
                                            @else
                                                Calculating...
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Token Info -->
                    <div class="token-info p-3 bg-light mb-3" style="border: 2px dashed #ff4b2b; border-radius: 8px;">
                        <div class="d-flex align-items-center">
                            <div class="token-icon me-3">
                                <i class="fas fa-coins fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Tokens Used: <span class="fw-bold">{{ $download->token_cost }}</span></h5>
                                <p class="mb-0 small">
                                    @if($download->status == 'cancelled')
                                        Tokens have been refunded to your account.
                                    @elseif($download->status == 'failed')
                                        Tokens will be refunded if you choose not to retry.
                                    @else
                                        Tokens are charged based on duration and quality.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Card (if in progress) -->
                    @if(in_array($download->status, ['pending', 'processing', 'downloading', 'uploading']))
                        <div class="progress-container mb-3">
                            <h6 class="mb-2">Download Progress</h6>
                            @php
                                $progress = 0;
                                $statusLabel = 'Pending';

                                if($download->progress) {
                                    $progress = $download->progress;
                                } else {
                                    // Simulate progress based on status
                                    switch($download->status) {
                                        case 'pending':
                                            $progress = 5;
                                            $statusLabel = 'In Queue';
                                            break;
                                        case 'processing':
                                            $progress = 25;
                                            $statusLabel = 'Processing';
                                            break;
                                        case 'downloading':
                                            $progress = 65;
                                            $statusLabel = 'Downloading';
                                            break;
                                        case 'uploading':
                                            $progress = 85;
                                            $statusLabel = 'Uploading';
                                            break;
                                    }
                                }
                            @endphp

                            <x-progress-bar
                                :value="$progress"
                                max="100"
                                label="{{ $statusLabel }}: {{ $progress }}%"
                                showLabel="true"
                                animated="true"
                                striped="true"
                                variant="primary"
                            />

                            <div class="text-center mt-2 small text-muted">
                                <i class="fas fa-info-circle me-1"></i> This page will automatically refresh to show progress updates
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Action Card -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        @if($download->status == 'completed')
                            <a href="{{ route('downloads.file', $download) }}" class="neo-btn">
                                <i class="fas fa-download me-2"></i> Download File
                            </a>

                            <a href="{{ route('instance.show', $download) }}" class="neo-btn">
                                <i class="fas fa-play me-2"></i> Open in Media Player
                            </a>

                            <button type="button" class="neo-btn btn-secondary" data-bs-toggle="modal" data-bs-target="#shareModal">
                                <i class="fas fa-share-alt me-2"></i> Share
                            </button>
                        @elseif($download->status == 'failed')
                            <form action="{{ route('downloads.retry', $download) }}" method="POST">
                                @csrf
                                <button type="submit" class="neo-btn w-100">
                                    <i class="fas fa-redo me-2"></i> Retry Download
                                </button>
                            </form>
                        @elseif(in_array($download->status, ['pending', 'processing', 'downloading', 'uploading']))
                            <form action="{{ route('downloads.cancel', $download) }}" method="POST">
                                @csrf
                                <button type="submit" class="neo-btn btn-danger w-100">
                                    <i class="fas fa-times me-2"></i> Cancel Download
                                </button>
                            </form>
                        @elseif($download->status == 'cancelled')
                            <form action="{{ route('downloads.retry', $download) }}" method="POST">
                                @csrf
                                <button type="submit" class="neo-btn w-100">
                                    <i class="fas fa-redo me-2"></i> Try Again
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('downloads.create') }}" class="neo-btn btn-secondary">
                            <i class="fas fa-plus-circle me-2"></i> New Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- Similar Content -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Similar Content</h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($similarDownloads) && count($similarDownloads) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($similarDownloads as $similarDownload)
                                <a href="{{ route('downloads.show', $similarDownload) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            @if($similarDownload->platform == 'youtube')
                                                <i class="fab fa-youtube text-danger fa-2x"></i>
                                            @elseif($similarDownload->platform == 'tiktok')
                                                <i class="fab fa-tiktok fa-2x"></i>
                                            @elseif($similarDownload->platform == 'instagram')
                                                <i class="fab fa-instagram text-purple fa-2x"></i>
                                            @else
                                                <i class="fas fa-link fa-2x"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold text-truncate">{{ $similarDownload->title }}</div>
                                            <small class="text-muted">{{ $similarDownload->created_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <i class="fas fa-search fa-2x mb-3 text-muted"></i>
                            <p class="mb-0">No similar content found</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tokens Card -->
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">Token Balance</h5>
                </div>
                <div class="card-body text-center">
                    <div class="token-display mb-3">
                        <i class="fas fa-coins fa-3x text-warning mb-2"></i>
                        <h3 class="mb-0">{{ auth()->user()->token_balance }}</h3>
                        <p class="text-muted">Available Tokens</p>
                    </div>
                    <a href="{{ route('tokens.purchase') }}" class="neo-btn btn-warning w-100">
                        <i class="fas fa-plus-circle me-2"></i> Get More Tokens
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<x-modal id="shareModal" title="Share Your Media">
    <p>Share this content with others:</p>

    <div class="input-group mb-3">
        <input type="text" class="neo-form-control" value="{{ route('instance.show', $download) }}" id="shareLink" readonly>
        <button class="btn neo-btn" type="button" id="copyLinkBtn">
            <i class="fas fa-copy"></i>
        </button>
    </div>

    <div class="d-flex justify-content-center gap-2 mt-4">
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('instance.show', $download)) }}"
            target="_blank" class="neo-btn">
            <i class="fab fa-facebook-f me-2"></i> Facebook
        </a>

        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('instance.show', $download)) }}&text={{ urlencode($download->title) }}"
            target="_blank" class="neo-btn">
            <i class="fab fa-twitter me-2"></i> Twitter
        </a>

        <a href="https://wa.me/?text={{ urlencode($download->title . ' ' . route('instance.show', $download)) }}"
            target="_blank" class="neo-btn">
            <i class="fab fa-whatsapp me-2"></i> WhatsApp
        </a>
    </div>

    <x-slot name="footer">
        <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </x-slot>
</x-modal>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Copy share link function
    document.getElementById('copyLinkBtn')?.addEventListener('click', function() {
        var shareLink = document.getElementById('shareLink');
        shareLink.select();
        shareLink.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(shareLink.value);

        // Show success state
        this.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-copy"></i>';
        }, 2000);
    });

    // Auto-refresh for in-progress downloads
    @if(in_array($download->status, ['pending', 'processing', 'downloading', 'uploading']))
        function checkStatus() {
            fetch('{{ route('downloads.status', $download->id) }}')
                .then(response => response.json())
                .then(data => {
                    // If status changed, reload the page
                    if (data.status !== '{{ $download->status }}') {
                        location.reload();
                        return;
                    }

                    // Schedule next check
                    setTimeout(checkStatus, 5000);
                })
                .catch(error => {
                    console.error('Error checking status:', error);
                    setTimeout(checkStatus, 10000); // Try again in 10 seconds on error
                });
        }

        // Start polling
        setTimeout(checkStatus, 5000);
    @endif
</script>
@endpush
