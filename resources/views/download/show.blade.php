@extends('layouts.app')

@section('title', $download->title ?? 'Download Details')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center mb-4">
                <h1 class="display-5 fw-bold mb-3 text-truncate">{{ $download->title ?? 'Download Details' }}</h1>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('downloads.index') }}" class="neo-btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Downloads
                    </a>
                    <span class="badge fs-6 px-4 py-2 {{
                        $download->status == 'completed' ? 'bg-success' :
                        ($download->status == 'processing' || $download->status == 'downloading' || $download->status == 'uploading' ? 'bg-primary' :
                        ($download->status == 'pending' ? 'bg-warning' :
                        ($download->status == 'failed' ? 'bg-danger' : 'bg-secondary')))
                    }}" style="border: 3px solid #000;">
                        {{ ucfirst($download->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Media Preview Section -->
            <div class="neo-card mb-5 mx-3">
                <div class="card-header text-center py-3 border-bottom border-3 border-dark">
                    <h4 class="mb-0 fw-bold">Media Preview</h4>
                </div>
                <div class="card-body p-4">
                    @if($download->status == 'completed')
                        <div class="media-preview">
                            @if(in_array($download->format, ['mp4']))
                                <div class="video-container" style="border: 4px solid #212529; border-radius: 12px; overflow: hidden; position: relative; padding-bottom: 56.25%; height: 0; margin: 20px 0;">
                                    <video width="100%" height="auto" controls style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                        <source src="{{ $download->storage_url }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @elseif(in_array($download->format, ['mp3']))
                                <div class="audio-container p-5 bg-light d-flex align-items-center justify-content-center" style="border: 4px solid #212529; border-radius: 12px; margin: 20px 0; min-height: 200px; box-shadow: 6px 6px 0 #000;">
                                    <div class="text-center w-100">
                                        <i class="fas fa-music fa-4x mb-4 text-primary"></i>
                                        <h5 class="mb-4">Audio Player</h5>
                                        <audio controls class="w-100" style="max-width: 400px;">
                                            <source src="{{ $download->storage_url }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                            @else
                                <div class="unknown-media-container p-5 bg-light text-center" style="border: 4px solid #212529; border-radius: 12px; margin: 20px 0; min-height: 200px; box-shadow: 6px 6px 0 #000;">
                                    <i class="fas fa-file-download fa-4x mb-4 text-primary"></i>
                                    <h5 class="mb-3">Preview Not Available</h5>
                                    <p class="mb-0">Preview not available for this format. Click download to access your file.</p>
                                </div>
                            @endif
                        </div>
                    @elseif(in_array($download->status, ['pending', 'processing', 'downloading', 'uploading']))
                        <div class="processing-container p-5 bg-light text-center" style="border: 4px solid #212529; border-radius: 12px; margin: 20px 0; min-height: 250px; box-shadow: 6px 6px 0 #000;">
                            <i class="fas fa-spinner fa-spin fa-4x mb-4 text-primary"></i>
                            <h4 class="mb-3">Your download is processing</h4>
                            <p class="mb-0 fs-5">We're working on your download. This might take a few moments.</p>
                        </div>
                    @elseif($download->status == 'failed')
                        <div class="failed-container p-5 bg-light text-center" style="border: 4px solid #212529; border-radius: 12px; margin: 20px 0; min-height: 250px; box-shadow: 6px 6px 0 #000;">
                            <i class="fas fa-exclamation-triangle fa-4x mb-4 text-danger"></i>
                            <h4 class="mb-3">Download Failed</h4>
                            <p class="mb-4 fs-5">{{ $download->error_message ?? 'There was an error processing your download.' }}</p>
                            <form action="{{ route('downloads.retry', $download) }}" method="POST">
                                @csrf
                                <button type="submit" class="neo-btn btn-lg" style="transform: translateY(0); transition: transform 0.2s; box-shadow: 4px 4px 0 #000;">
                                    <i class="fas fa-redo me-2"></i> Retry Download
                                </button>
                            </form>
                        </div>
                    @elseif($download->status == 'cancelled')
                        <div class="cancelled-container p-5 bg-light text-center" style="border: 4px solid #212529; border-radius: 12px; margin: 20px 0; min-height: 250px; box-shadow: 6px 6px 0 #000;">
                            <i class="fas fa-ban fa-4x mb-4 text-secondary"></i>
                            <h4 class="mb-3">Download Cancelled</h4>
                            <p class="mb-4 fs-5">This download was cancelled. Your tokens have been refunded.</p>
                            <form action="{{ route('downloads.retry', $download) }}" method="POST">
                                @csrf
                                <button type="submit" class="neo-btn btn-lg" style="transform: translateY(0); transition: transform 0.2s; box-shadow: 4px 4px 0 #000;">
                                    <i class="fas fa-redo me-2"></i> Try Again
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Download Information Section -->
            <div class="neo-card mb-5 mx-3">
                <div class="card-header text-center py-3 border-bottom border-3 border-dark">
                    <h4 class="mb-0 fw-bold">Download Information</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-section p-4" style="border: 3px solid #212529; border-radius: 10px; background: #f8f9fa; box-shadow: 5px 5px 0 #000;">
                                <h5 class="mb-4 text-center fw-bold">Basic Info</h5>
                                <div class="info-item mb-3 p-3" style="border: 2px solid #212529; border-radius: 8px; background: white;">
                                    <strong class="d-block mb-2">URL:</strong>
                                    <div class="text-truncate" data-bs-toggle="tooltip" title="{{ $download->url }}">
                                        <a href="{{ $download->url }}" target="_blank" class="text-decoration-none">
                                            {{ $download->url }}
                                        </a>
                                    </div>
                                </div>
                                <div class="info-item mb-3 p-3" style="border: 2px solid #212529; border-radius: 8px; background: white;">
                                    <strong class="d-block mb-2">Platform:</strong>
                                    @if($download->platform == 'youtube')
                                        <i class="fab fa-youtube text-danger me-2"></i>
                                    @elseif($download->platform == 'tiktok')
                                        <i class="fab fa-tiktok me-2"></i>
                                    @elseif($download->platform == 'instagram')
                                        <i class="fab fa-instagram text-purple me-2"></i>
                                    @else
                                        <i class="fas fa-link me-2"></i>
                                    @endif
                                    {{ ucfirst($download->platform) }}
                                </div>
                                <div class="info-item p-3" style="border: 2px solid #212529; border-radius: 8px; background: white;">
                                    <strong class="d-block mb-2">Format:</strong>
                                    @if($download->format == 'mp4')
                                        <span class="badge bg-primary fs-6" style="border: 2px solid #212529;">MP4</span>
                                        <small class="ms-2">{{ $download->quality }}</small>
                                    @elseif($download->format == 'mp3')
                                        <span class="badge bg-success fs-6" style="border: 2px solid #212529;">MP3</span>
                                    @else
                                        <span class="badge bg-secondary fs-6" style="border: 2px solid #212529;">{{ strtoupper($download->format) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section p-4" style="border: 3px solid #212529; border-radius: 10px; background: #f8f9fa; box-shadow: 5px 5px 0 #000;">
                                <h5 class="mb-4 text-center fw-bold">File Details</h5>
                                <div class="info-item mb-3 p-3" style="border: 2px solid #212529; border-radius: 8px; background: white;">
                                    <strong class="d-block mb-2">Created:</strong>
                                    {{ $download->created_at->format('M d, Y h:i A') }}
                                </div>
                                <div class="info-item mb-3 p-3" style="border: 2px solid #212529; border-radius: 8px; background: white;">
                                    <strong class="d-block mb-2">Duration:</strong>
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
                                </div>
                                <div class="info-item p-3" style="border: 2px solid #212529; border-radius: 8px; background: white;">
                                    <strong class="d-block mb-2">File Size:</strong>
                                    @if($download->file_size)
                                        {{ round($download->file_size / (1024 * 1024), 2) }} MB
                                    @else
                                        Calculating...
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Token Information -->
            <div class="neo-card mb-5 mx-3">
                <div class="card-header text-center py-3 border-bottom border-3 border-dark">
                    <h4 class="mb-0 fw-bold">Token Information</h4>
                </div>
                <div class="card-body p-4">
                    <div class="token-info p-4 text-center" style="border: 3px dashed #ff4b2b; border-radius: 12px; background: linear-gradient(45deg, #fff9c4, #ffefba); box-shadow: 5px 5px 0 #000;">
                        <i class="fas fa-coins fa-3x text-warning mb-3"></i>
                        <h4 class="mb-2">Tokens Used: <span class="fw-bold fs-2">{{ $download->token_cost }}</span></h4>
                        <p class="mb-0 fs-5">
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

            <!-- Progress Section (if in progress) -->
            @if(in_array($download->status, ['pending', 'processing', 'downloading', 'uploading']))
                <div class="neo-card mb-5 mx-3">
                    <div class="card-header text-center py-3 border-bottom border-3 border-dark">
                        <h4 class="mb-0 fw-bold">Download Progress</h4>
                    </div>
                    <div class="card-body p-4">
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

                        <div class="progress-wrapper p-4" style="border: 3px solid #212529; border-radius: 10px; background: #f8f9fa; box-shadow: 5px 5px 0 #000;">
                            <div class="progress mb-3" style="height: 35px; border: 2px solid #212529;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fw-bold fs-6"
                                    role="progressbar"
                                    style="width: {{ $progress }}%"
                                    aria-valuenow="{{ $progress }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{ $statusLabel }}: {{ $progress }}%
                                </div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <span class="fs-6">This page will automatically refresh to show progress updates</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Action Section -->
            <div class="neo-card mb-5 mx-3">
                <div class="card-header text-center py-3 border-bottom border-3 border-dark">
                    <h4 class="mb-0 fw-bold">Actions</h4>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-4">
                        @if($download->status == 'completed')
                            <a href="{{ route('downloads.file', $download) }}" class="neo-btn btn-lg" style="box-shadow: 4px 4px 0 #000; transform: translateY(0); transition: all 0.2s ease;">
                                <i class="fas fa-download me-2"></i> Download File
                            </a>

                            <a href="{{ route('instance.show', $download) }}" class="neo-btn btn-lg" style="box-shadow: 4px 4px 0 #000; transform: translateY(0); transition: all 0.2s ease;">
                                <i class="fas fa-play me-2"></i> Open in Media Player
                            </a>

                            <button type="button" class="neo-btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#shareModal" style="box-shadow: 4px 4px 0 #000; transform: translateY(0); transition: all 0.2s ease;">
                                <i class="fas fa-share-alt me-2"></i> Share
                            </button>
                        @elseif($download->status == 'failed')
                            <form action="{{ route('downloads.retry', $download) }}" method="POST">
                                @csrf
                                <button type="submit" class="neo-btn btn-lg w-100" style="box-shadow: 4px 4px 0 #000; transform: translateY(0); transition: all 0.2s ease;">
                                    <i class="fas fa-redo me-2"></i> Retry Download
                                </button>
                            </form>
                        @elseif(in_array($download->status, ['pending', 'processing', 'downloading', 'uploading']))
                            <form action="{{ route('downloads.cancel', $download) }}" method="POST">
                                @csrf
                                <button type="submit" class="neo-btn btn-danger btn-lg w-100" style="box-shadow: 4px 4px 0 #000; transform: translateY(0); transition: all 0.2s ease;">
                                    <i class="fas fa-times me-2"></i> Cancel Download
                                </button>
                            </form>
                        @elseif($download->status == 'cancelled')
                            <form action="{{ route('downloads.retry', $download) }}" method="POST">
                                @csrf
                                <button type="submit" class="neo-btn btn-lg w-100" style="box-shadow: 4px 4px 0 #000; transform: translateY(0); transition: all 0.2s ease;">
                                    <i class="fas fa-redo me-2"></i> Try Again
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('downloads.create') }}" class="neo-btn btn-secondary btn-lg" style="box-shadow: 4px 4px 0 #000; transform: translateY(0); transition: all 0.2s ease;">
                            <i class="fas fa-plus-circle me-2"></i> New Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- Similar Content -->
            <div class="neo-card mb-5 mx-3">
                <div class="card-header text-center py-3 border-bottom border-3 border-dark">
                    <h4 class="mb-0 fw-bold">Similar Content</h4>
                </div>
                <div class="card-body p-0">
                    @if(isset($similarDownloads) && count($similarDownloads) > 0)
                        <div class="p-3">
                            @foreach($similarDownloads as $similarDownload)
                                <a href="{{ route('downloads.show', $similarDownload) }}" class="d-block mb-3 text-decoration-none">
                                    <div class="similar-item p-3 d-flex align-items-center" style="border: 2px solid #212529; border-radius: 8px; background: #f8f9fa; transition: all 0.2s; box-shadow: 3px 3px 0 #000;">
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
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-truncate">{{ $similarDownload->title }}</div>
                                            <small class="text-muted">{{ $similarDownload->created_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-5 text-center">
                            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                            <h5 class="mb-0">No similar content found</h5>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Token Balance -->
            <div class="neo-card mx-3">
                <div class="card-header text-center py-3 border-bottom border-3 border-dark">
                    <h4 class="mb-0 fw-bold">Token Balance</h4>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="token-display p-4" style="border: 3px solid #212529; border-radius: 10px; background: linear-gradient(135deg, #ffeaa7, #fdcb6e); box-shadow: 5px 5px 0 #000;">
                        <i class="fas fa-coins fa-4x text-warning mb-3"></i>
                        <h2 class="mb-2 fw-bold">{{ auth()->user()->token_balance }}</h2>
                        <p class="text-dark mb-4 fs-5">Available Tokens</p>
                        <a href="{{ route('tokens.purchase') }}" class="neo-btn btn-warning btn-lg" style="box-shadow: 4px 4px 0 #000; transform: translateY(0); transition: all 0.2s ease;">
                            <i class="fas fa-plus-circle me-2"></i> Get More Tokens
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
y
<!-- Share Modal -->
<x-modal id="shareModal" title="Share Your Media" size="sm">
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

    // Hover effects for similar content
    document.querySelectorAll('.similar-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '4px 4px 0px #000';
        });

        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
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
