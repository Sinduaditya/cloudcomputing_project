<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\instance\actions.blade.php -->
<div class="instance-actions">
    <div class="neo-card">
        <div class="card-header bg-primary-gradient text-white">
            <h5 class="mb-0">Media Player Controls</h5>
        </div>
        <div class="card-body">
            <div class="media-info mb-4">
                <h5 class="media-title">{{ $download->title ?? 'Media Player' }}</h5>
                <div class="d-flex align-items-center">
                    <span class="badge me-3" style="background: #f5f5f5; color: #212529; border: 2px solid #212529; border-radius: 6px; padding: 5px 10px;">
                        <i class="fas fa-file-video me-1"></i> {{ strtoupper($download->format ?? 'MP4') }}
                    </span>
                    <span class="text-muted">
                        {{ $download->created_at ? $download->created_at->format('M d, Y') : 'Unknown date' }}
                    </span>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <!-- Download Action -->
                <div class="col-sm-4">
                    <div class="d-grid">
                        <a href="{{ route('downloads.file', $download) }}" class="neo-btn">
                            <i class="fas fa-download me-2"></i> Download
                        </a>
                    </div>
                </div>

                <!-- Share Action -->
                <div class="col-sm-4">
                    <div class="d-grid">
                        <button type="button" class="neo-btn btn-secondary" data-bs-toggle="modal" data-bs-target="#shareModal">
                            <i class="fas fa-share-alt me-2"></i> Share
                        </button>
                    </div>
                </div>

                <!-- Info Action -->
                <div class="col-sm-4">
                    <div class="d-grid">
                        <a href="{{ route('downloads.show', $download) }}" class="neo-btn btn-secondary">
                            <i class="fas fa-info-circle me-2"></i> Details
                        </a>
                    </div>
                </div>
            </div>

            <hr class="my-4" style="border-top: 2px dashed #212529;">

            <div class="playback-controls">
                <h6 class="mb-3">Playback Settings</h6>

                <div class="row g-3">
                    <!-- Playback Speed -->
                    <div class="col-sm-6">
                        <label class="form-label fw-bold">Playback Speed</label>
                        <select class="neo-form-control" id="playbackSpeed">
                            <option value="0.5">0.5x</option>
                            <option value="0.75">0.75x</option>
                            <option value="1" selected>Normal (1x)</option>
                            <option value="1.25">1.25x</option>
                            <option value="1.5">1.5x</option>
                            <option value="2">2x</option>
                        </select>
                    </div>

                    <!-- Video Quality (if applicable) -->
                    @if(in_array($download->format ?? '', ['mp4_720p', 'mp4_480p', 'mp4_360p']))
                        <div class="col-sm-6">
                            <label class="form-label fw-bold">Video Quality</label>
                            <select class="neo-form-control" id="videoQuality">
                                <option value="auto" selected>Auto</option>
                                <option value="720p">720p</option>
                                <option value="480p">480p</option>
                                <option value="360p">360p</option>
                            </select>
                        </div>
                    @endif
                </div>

                <!-- Looping -->
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="loopVideo" style="border: 2px solid #212529; width: 20px; height: 20px;">
                    <label class="form-check-label ms-1" for="loopVideo">
                        Loop video
                    </label>
                </div>
            </div>

            <hr class="my-4" style="border-top: 2px dashed #212529;">

            <div class="additional-actions">
                @if($download->status == 'completed')
                    <!-- Report Issue -->
                    <button type="button" class="btn btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#reportIssueModal">
                        <i class="fas fa-exclamation-triangle me-1"></i> Report Issue
                    </button>

                    <!-- Request Deletion -->
                    <form action="{{ route('instance.delete', $download) }}" method="POST" class="d-inline ms-3">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none text-danger" onclick="return confirm('Are you sure you want to request deletion of this media?');">
                            <i class="fas fa-trash-alt me-1"></i> Request Deletion
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 12px; box-shadow: 8px 8px 0 rgba(0,0,0,0.35);">
            <div class="modal-header bg-primary-gradient text-white" style="border-bottom: 3px solid #212529;">
                <h5 class="modal-title" id="shareModalLabel">Share Media</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Share this media with others:</p>

                <div class="input-group mb-3">
                    <input type="text" class="neo-form-control" value="{{ route('instance.show', $download) }}" id="shareLink" readonly>
                    <button class="btn neo-btn" type="button" id="copyLinkBtn">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="expireLink" style="border: 2px solid #212529; width: 20px; height: 20px;">
                    <label class="form-check-label ms-1" for="expireLink">
                        Set expiration (24 hours)
                    </label>
                </div>

                <div class="share-buttons d-flex justify-content-center gap-2 mt-4">
                    <a href="#" class="btn neo-btn" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('instance.show', $download)) }}', '', 'width=600,height=400'); return false;">
                        <i class="fab fa-facebook-f me-2"></i> Facebook
                    </a>
                    <a href="#" class="btn neo-btn" onclick="window.open('https://twitter.com/intent/tweet?url={{ urlencode(route('instance.show', $download)) }}&text={{ urlencode($download->title ?? 'Check out this media!') }}', '', 'width=600,height=400'); return false;">
                        <i class="fab fa-twitter me-2"></i> Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($download->title ?? 'Check out this media!') }}%20{{ urlencode(route('instance.show', $download)) }}" target="_blank" class="btn neo-btn">
                        <i class="fab fa-whatsapp me-2"></i> WhatsApp
                    </a>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #121212;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Report Issue Modal -->
<div class="modal fade" id="reportIssueModal" tabindex="-1" aria-labelledby="reportIssueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: 3px solid #212529; border-radius: 12px; box-shadow: 8px 8px 0 rgba(0,0,0,0.35);">
            <div class="modal-header bg-primary-gradient text-white" style="border-bottom: 3px solid #212529;">
                <h5 class="modal-title" id="reportIssueModalLabel">Report Issue</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportIssueForm">
                    <div class="mb-3">
                        <label for="issueType" class="form-label fw-bold">Issue Type</label>
                        <select class="neo-form-control" id="issueType" required>
                            <option value="">Select an issue type</option>
                            <option value="playback">Playback issue</option>
                            <option value="download">Download issue</option>
                            <option value="quality">Quality issue</option>
                            <option value="audio">Audio issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="issueDescription" class="form-label fw-bold">Description</label>
                        <textarea class="neo-form-control" id="issueDescription" rows="4" required placeholder="Please describe the issue you're experiencing"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #121212;">
                <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="neo-btn" id="submitIssue">Submit Report</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Playback speed control
        const videoElement = document.querySelector('video');
        const speedSelector = document.getElementById('playbackSpeed');

        if (videoElement && speedSelector) {
            speedSelector.addEventListener('change', function() {
                videoElement.playbackRate = parseFloat(this.value);
            });
        }

        // Loop control
        const loopCheckbox = document.getElementById('loopVideo');
        if (videoElement && loopCheckbox) {
            loopCheckbox.addEventListener('change', function() {
                videoElement.loop = this.checked;
            });
        }

        // Copy link functionality
        const copyLinkBtn = document.getElementById('copyLinkBtn');
        if (copyLinkBtn) {
            copyLinkBtn.addEventListener('click', function() {
                const shareLink = document.getElementById('shareLink');
                shareLink.select();
                shareLink.setSelectionRange(0, 99999); // For mobile devices
                navigator.clipboard.writeText(shareLink.value);

                // Change button text temporarily
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.innerHTML = originalContent;
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

                    // Simulate submission with timeout
                    setTimeout(() => {
                        // Hide modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('reportIssueModal'));
                        modal.hide();

                        // Show success toast/alert
                        alert('Issue reported successfully. We will review it soon.');

                        // Reset form
                        form.reset();
                        this.innerHTML = 'Submit Report';
                        this.disabled = false;
                    }, 1500);
                } else {
                    form.reportValidity();
                }
            });
        }
    });
</script>
