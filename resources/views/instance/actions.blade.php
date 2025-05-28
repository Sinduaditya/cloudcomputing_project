@extends('layouts.app')

@section('title', 'Media Player Controls')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center mb-4">
                <h1 class="display-5 fw-bold mb-3">Media Player Controls</h1>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('schedules.index') }}" class="neo-btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Player
                    </a>
                    <span class="badge fs-6 px-4 py-2 bg-success" style="border: 3px solid #000;">
                        Media Ready
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-10">
            <!-- Media Information Section -->
            <div class="neo-card mb-5" style="margin: 0 20px;">
                <div class="card-header text-center py-3">
                    <h4 class="mb-0 fw-bold">Media Information</h4>
                </div>
                <div class="card-body p-4">
                    <div class="media-info-wrapper p-4" style="border: 3px solid #212529; border-radius: 12px; background: #f8f9fa;">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold mb-3">{{ $download->title ?? 'Media Player' }}</h3>
                            <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                                <span class="badge fs-6 px-3 py-2" style="background: #ffeaa7; color: #212529; border: 2px solid #212529; border-radius: 8px;">
                                    <i class="fas fa-file-video me-2"></i> {{ strtoupper($download->format ?? 'MP4') }}
                                </span>
                                @if($download->quality)
                                    <span class="badge fs-6 px-3 py-2" style="background: #fdcb6e; color: #212529; border: 2px solid #212529; border-radius: 8px;">
                                        <i class="fas fa-hd-video me-2"></i> {{ $download->quality }}
                                    </span>
                                @endif
                                <span class="badge fs-6 px-3 py-2" style="background: #e17055; color: white; border: 2px solid #212529; border-radius: 8px;">
                                    <i class="fas fa-calendar me-2"></i> {{ $download->created_at ? $download->created_at->format('M d, Y') : 'Unknown date' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="neo-card mb-5" style="margin: 0 20px;">
                <div class="card-header text-center py-3">
                    <h4 class="mb-0 fw-bold">Quick Actions</h4>
                </div>
                <div class="card-body p-4">
                    <div class="quick-actions-wrapper p-4" style="border: 3px solid #212529; border-radius: 12px; background: #f8f9fa;">
                        <div class="row g-4">
                            <!-- Download Action -->
                            <div class="col-md-4">
                                <div class="action-item text-center p-4" style="border: 2px solid #212529; border-radius: 10px; background: white; height: 100%;">
                                    <i class="fas fa-download fa-3x text-primary mb-3"></i>
                                    <h5 class="mb-3">Download File</h5>
                                    <p class="text-muted mb-3">Save this media to your device</p>
                                    <a href="{{ route('downloads.file', $download) }}" class="neo-btn btn-lg w-100">
                                        <i class="fas fa-download me-2"></i> Download
                                    </a>
                                </div>
                            </div>

                            <!-- Share Action -->
                            <div class="col-md-4">
                                <div class="action-item text-center p-4" style="border: 2px solid #212529; border-radius: 10px; background: white; height: 100%;">
                                    <i class="fas fa-share-alt fa-3x text-success mb-3"></i>
                                    <h5 class="mb-3">Share Media</h5>
                                    <p class="text-muted mb-3">Share with friends and family</p>
                                    <button type="button" class="neo-btn btn-lg w-100" data-bs-toggle="modal" data-bs-target="#shareModal">
                                        <i class="fas fa-share-alt me-2"></i> Share
                                    </button>
                                </div>
                            </div>

                            <!-- Info Action -->
                            <div class="col-md-4">
                                <div class="action-item text-center p-4" style="border: 2px solid #212529; border-radius: 10px; background: white; height: 100%;">
                                    <i class="fas fa-info-circle fa-3x text-warning mb-3"></i>
                                    <h5 class="mb-3">View Details</h5>
                                    <p class="text-muted mb-3">See complete media information</p>
                                    <a href="{{ route('downloads.show', $download) }}" class="neo-btn btn-secondary btn-lg w-100">
                                        <i class="fas fa-info-circle me-2"></i> Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Playback Settings Section -->
            <div class="neo-card mb-5" style="margin: 0 20px;">
                <div class="card-header text-center py-3">
                    <h4 class="mb-0 fw-bold">Playback Settings</h4>
                </div>
                <div class="card-body p-4">
                    <div class="playback-wrapper p-4" style="border: 3px solid #212529; border-radius: 12px; background: #f8f9fa;">
                        <div class="row g-4">
                            <!-- Speed Control -->
                            <div class="col-md-6">
                                <div class="setting-item p-4" style="border: 2px solid #212529; border-radius: 10px; background: white;">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-tachometer-alt fa-2x text-primary me-3"></i>
                                        <div>
                                            <h5 class="mb-1">Playback Speed</h5>
                                            <p class="text-muted mb-0">Adjust video speed</p>
                                        </div>
                                    </div>
                                    <select class="neo-form-control" id="playbackSpeed">
                                        <option value="0.5">0.5x (Slow)</option>
                                        <option value="0.75">0.75x</option>
                                        <option value="1" selected>1x (Normal)</option>
                                        <option value="1.25">1.25x</option>
                                        <option value="1.5">1.5x</option>
                                        <option value="2">2x (Fast)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Quality Control -->
                            @if(in_array($download->format ?? '', ['mp4', 'mp4_720p', 'mp4_480p', 'mp4_360p']))
                                <div class="col-md-6">
                                    <div class="setting-item p-4" style="border: 2px solid #212529; border-radius: 10px; background: white;">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-hd-video fa-2x text-success me-3"></i>
                                            <div>
                                                <h5 class="mb-1">Video Quality</h5>
                                                <p class="text-muted mb-0">Choose playback quality</p>
                                            </div>
                                        </div>
                                        <select class="neo-form-control" id="videoQuality">
                                            <option value="auto" selected>Auto Quality</option>
                                            <option value="720p">720p (HD)</option>
                                            <option value="480p">480p (SD)</option>
                                            <option value="360p">360p (Low)</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Additional Options -->
                        <div class="mt-4">
                            <div class="additional-options p-4" style="border: 2px solid #212529; border-radius: 10px; background: white;">
                                <h5 class="mb-3 text-center">Additional Options</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check p-3" style="border: 2px solid #212529; border-radius: 8px; background: #f8f9fa;">
                                            <input class="form-check-input" type="checkbox" id="loopVideo" style="border: 2px solid #212529; width: 20px; height: 20px;">
                                            <label class="form-check-label ms-2 fw-bold" for="loopVideo">
                                                <i class="fas fa-repeat me-2"></i> Loop Video
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check p-3" style="border: 2px solid #212529; border-radius: 8px; background: #f8f9fa;">
                                            <input class="form-check-input" type="checkbox" id="autoplay" style="border: 2px solid #212529; width: 20px; height: 20px;">
                                            <label class="form-check-label ms-2 fw-bold" for="autoplay">
                                                <i class="fas fa-play me-2"></i> Autoplay
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support & Help Section -->
            <div class="neo-card" style="margin: 0 20px;">
                <div class="card-header text-center py-3">
                    <h4 class="mb-0 fw-bold">Support & Help</h4>
                </div>
                <div class="card-body p-4">
                    <div class="support-wrapper p-4" style="border: 3px dashed #ff4b2b; border-radius: 12px; background: linear-gradient(45deg, #fff9c4, #ffefba);">
                        <div class="text-center mb-4">
                            <i class="fas fa-life-ring fa-3x text-primary mb-3"></i>
                            <h5 class="mb-3">Need Help?</h5>
                            <p class="mb-0">If you're experiencing any issues with this media, we're here to help!</p>
                        </div>

                        <div class="row g-3">
                            @if($download->status == 'completed')
                                <div class="col-md-6">
                                    <button type="button" class="neo-btn btn-warning btn-lg w-100" data-bs-toggle="modal" data-bs-target="#reportIssueModal">
                                        <i class="fas fa-exclamation-triangle me-2"></i> Report Issue
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <form action="{{ route('instance.delete', $download) }}" method="POST" class="d-grid">
                                        @csrf
                                        <button type="submit" class="neo-btn btn-danger btn-lg" onclick="return confirm('Are you sure you want to request deletion of this media?');">
                                            <i class="fas fa-trash-alt me-2"></i> Request Deletion
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: 4px solid #212529; border-radius: 15px; box-shadow: 10px 10px 0 rgba(0,0,0,0.4);">
            <div class="modal-header text-center py-4" style="border-bottom: 3px solid #212529; background: linear-gradient(135deg, #74b9ff, #0984e3); color: white;">
                <h4 class="modal-title w-100 fw-bold" id="shareModalLabel">
                    <i class="fas fa-share-alt me-2"></i> Share Your Media
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="share-content p-4" style="border: 3px solid #212529; border-radius: 12px; background: #f8f9fa;">
                    <div class="text-center mb-4">
                        <i class="fas fa-link fa-3x text-primary mb-3"></i>
                        <h5>Share this media with others:</h5>
                    </div>

                    <div class="input-group mb-4">
                        <input type="text" class="neo-form-control" value="{{ route('instance.show', $download) }}" id="shareLink" readonly>
                        <button class="neo-btn" type="button" id="copyLinkBtn">
                            <i class="fas fa-copy me-2"></i> Copy
                        </button>
                    </div>

                    <div class="form-check mb-4 p-3" style="border: 2px solid #212529; border-radius: 8px; background: white;">
                        <input class="form-check-input" type="checkbox" id="expireLink" style="border: 2px solid #212529; width: 20px; height: 20px;">
                        <label class="form-check-label ms-2 fw-bold" for="expireLink">
                            <i class="fas fa-clock me-2"></i> Set expiration (24 hours)
                        </label>
                    </div>

                    <div class="social-buttons">
                        <h6 class="text-center mb-3">Share on social media:</h6>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            @php
                                $shareUrl = route('instance.show', $download);
                                $shareText = $download->title ?? 'Check out this media!';
                            @endphp

                            <a href="#" class="neo-btn btn-lg" style="background: #3b5998; color: white;"
                                onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}', '', 'width=600,height=400'); return false;">
                                <i class="fab fa-facebook-f me-2"></i> Facebook
                            </a>

                            <a href="#" class="neo-btn btn-lg" style="background: #1da1f2; color: white;"
                                onclick="window.open('https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareText) }}', '', 'width=600,height=400'); return false;">
                                <i class="fab fa-twitter me-2"></i> Twitter
                            </a>

                            <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}"
                                target="_blank" class="neo-btn btn-lg" style="background: #25d366; color: white;">
                                <i class="fab fa-whatsapp me-2"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center py-3" style="border-top: 3px solid #212529;">
                <button type="button" class="neo-btn btn-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Report Issue Modal -->
<div class="modal fade" id="reportIssueModal" tabindex="-1" aria-labelledby="reportIssueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: 4px solid #212529; border-radius: 15px; box-shadow: 10px 10px 0 rgba(0,0,0,0.4);">
            <div class="modal-header text-center py-4" style="border-bottom: 3px solid #212529; background: linear-gradient(135deg, #fd79a8, #e84393); color: white;">
                <h4 class="modal-title w-100 fw-bold" id="reportIssueModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Report an Issue
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="report-content p-4" style="border: 3px solid #212529; border-radius: 12px; background: #f8f9fa;">
                    <form id="reportIssueForm">
                        <div class="mb-4">
                            <label for="issueType" class="form-label fw-bold">
                                <i class="fas fa-list me-2"></i> Issue Type
                            </label>
                            <select class="neo-form-control" id="issueType" required>
                                <option value="">Select an issue type</option>
                                <option value="playback">Playback issue</option>
                                <option value="download">Download issue</option>
                                <option value="quality">Quality issue</option>
                                <option value="audio">Audio issue</option>
                                <option value="loading">Loading issue</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="issueDescription" class="form-label fw-bold">
                                <i class="fas fa-comment me-2"></i> Description
                            </label>
                            <textarea class="neo-form-control" id="issueDescription" rows="5" required
                                placeholder="Please describe the issue you're experiencing in detail..."></textarea>
                        </div>
                        <div class="form-check p-3" style="border: 2px solid #212529; border-radius: 8px; background: white;">
                            <input class="form-check-input" type="checkbox" id="contactMe" style="border: 2px solid #212529; width: 20px; height: 20px;">
                            <label class="form-check-label ms-2 fw-bold" for="contactMe">
                                <i class="fas fa-envelope me-2"></i> Contact me with updates about this issue
                            </label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3 py-3" style="border-top: 3px solid #212529;">
                <button type="button" class="neo-btn btn-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button type="button" class="neo-btn btn-lg" id="submitIssue">
                    <i class="fas fa-paper-plane me-2"></i> Submit Report
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Playback speed control
    const videoElement = document.querySelector('video');
    const speedSelector = document.getElementById('playbackSpeed');

    if (videoElement && speedSelector) {
        speedSelector.addEventListener('change', function() {
            videoElement.playbackRate = parseFloat(this.value);

            // Show feedback
            const feedback = document.createElement('div');
            feedback.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
            feedback.style.zIndex = '9999';
            feedback.innerHTML = `<i class="fas fa-check me-2"></i>Playback speed changed to ${this.value}x`;
            document.body.appendChild(feedback);
            setTimeout(() => feedback.remove(), 2000);
        });
    }

    // Loop control
    const loopCheckbox = document.getElementById('loopVideo');
    if (videoElement && loopCheckbox) {
        loopCheckbox.addEventListener('change', function() {
            videoElement.loop = this.checked;

            // Show feedback
            const feedback = document.createElement('div');
            feedback.className = 'alert alert-info position-fixed top-0 start-50 translate-middle-x mt-3';
            feedback.style.zIndex = '9999';
            feedback.innerHTML = `<i class="fas fa-info me-2"></i>Loop ${this.checked ? 'enabled' : 'disabled'}`;
            document.body.appendChild(feedback);
            setTimeout(() => feedback.remove(), 2000);
        });
    }

    // Autoplay control
    const autoplayCheckbox = document.getElementById('autoplay');
    if (videoElement && autoplayCheckbox) {
        autoplayCheckbox.addEventListener('change', function() {
            if (this.checked) {
                videoElement.setAttribute('autoplay', '');
            } else {
                videoElement.removeAttribute('autoplay');
            }
        });
    }

    // Copy link functionality
    const copyLinkBtn = document.getElementById('copyLinkBtn');
    if (copyLinkBtn) {
        copyLinkBtn.addEventListener('click', function() {
            const shareLink = document.getElementById('shareLink');
            shareLink.select();
            shareLink.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(shareLink.value);

            // Change button content temporarily
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check me-2"></i> Copied!';
            this.style.background = '#00b894';

            setTimeout(() => {
                this.innerHTML = originalContent;
                this.style.background = '';
            }, 2000);
        });
    }

    // Handle report submission
    const submitIssueBtn = document.getElementById('submitIssue');
    if (submitIssueBtn) {
        submitIssueBtn.addEventListener('click', function() {
            const form = document.getElementById('reportIssueForm');
            if (form.checkValidity()) {
                // Show loading state
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
                this.disabled = true;

                // Simulate submission
                setTimeout(() => {
                    // Hide modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('reportIssueModal'));
                    modal.hide();

                    // Show success alert
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                    successAlert.style.zIndex = '9999';
                    successAlert.innerHTML = '<i class="fas fa-check me-2"></i>Issue reported successfully! We will review it soon.';
                    document.body.appendChild(successAlert);
                    setTimeout(() => successAlert.remove(), 4000);

                    // Reset form
                    form.reset();
                    this.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Submit Report';
                    this.disabled = false;
                }, 1500);
            } else {
                form.reportValidity();
            }
        });
    }

    // Add hover effects to action items
    document.querySelectorAll('.action-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '5px 5px 0px rgba(0,0,0,0.3)';
        });

        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>
@endpush
