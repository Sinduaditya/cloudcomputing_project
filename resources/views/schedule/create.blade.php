<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\create.blade.php -->
@extends('layouts.app')

@section('title', 'Schedule Download')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Schedule Download</h1>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn neo-btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>

            <x-card>
                <div class="token-info p-3 bg-light" style="border: 2px dashed #ff4b2b; border-radius: 8px;">
                    <div class="d-flex align-items-center">
                        <div class="token-icon me-3">
                            <i class="fas fa-coins fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Token Balance: <span class="fw-bold">{{ auth()->user()->token_balance }}</span></h5>
                            <p class="mb-0 text-muted">Scheduled downloads also require tokens. <a href="{{ route('tokens.purchase') }}" class="text-decoration-none fw-bold">Need more?</a></p>
                        </div>
                    </div>
                </div>

                <for action="{{ route('schedules.store') }}" method="POST" id="scheduleForm">
                    @csrf
                    <div class="options-container p-3" style="border: 2px solid #212529; border-radius: 8px;">
                        <div class="mb-4">
                            <x-form-input
                                name="url"
                                label="Video URL"
                                placeholder="Paste YouTube, TikTok, or Instagram URL here"
                                :value="old('url')"
                                required
                                autofocus
                            />
                            <div class="supported-platforms small text-muted mt-2">
                                <span class="me-3"><i class="fab fa-youtube text-danger me-1"></i> YouTube</span>
                                <span class="me-3"><i class="fab fa-tiktok me-1"></i> TikTok</span>
                                <span><i class="fab fa-instagram text-purple me-1"></i> Instagram</span>
                            </div>
                        </div>
                        <div class="row mb-4">
                                <div class="col-md-6">
                                    <x-form-select name="format" label="Format" :options="[
                                        'mp4' => 'Video (MP4)',
                                        'mp3' => 'Audio (MP3)',
                                    ]" selected="mp4" required />
                                </div>
                                <div class="col-md-6" id="quality-container">
                                    <x-form-select name="quality" label="Quality" :options="[
                                        '1080p' => '1080p (HD)',
                                        '720p' => '720p (HD)',
                                        '480p' => '480p (SD)',
                                        '360p' => '360p (SD)',
                                    ]" selected="720p" required />
                                </div>
                            </div>
                        </div>
                    <div class="mb-4">
                        <div class="token-info p-3 bg-light" style="border: 2px dashed #ff4b2b; border-radius: 8px;">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-1">Schedule Options</h5>
                                </div>
                            </div>
                        </div>    
                        <div class="options-container p-3" style="border: 2px solid #212529; border-radius: 8px;">
                            <div class="mb-4">
                                <label for="scheduled_for" class="form-label">Schedule Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="neo-form-control" name="scheduled_for" id="scheduled_for" required>
                                <div class="form-text">Choose when you want the download to start</div>
                            </div>

                            <div class="mb-3">
                                <label for="schedule_type" class="form-label">Schedule Type</label>
                                <select class="neo-form-control" name="schedule_type" id="schedule_type">
                                    <option value="once">One Time</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>

                            <div id="recurrenceOptions" style="display: none;">
                                <div class="mb-3">
                                    <label for="end_after" class="form-label">End After</label>
                                    <input type="number" class="neo-form-control" name="end_after" id="end_after" placeholder="Number of occurrences">
                                </div>

                                <div id="weeklyOptions" style="display: none;">
                                    <label class="form-label">Repeat On</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="days[]" id="day_{{ $loop->index }}" value="{{ $loop->index }}" style="border: 2px solid #212529;">
                                                <label class="form-check-label" for="day_{{ $loop->index }}">
                                                    {{ $day }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" style="border: 2px solid #212529; width: 20px; height: 20px;" type="checkbox" name="notify_complete" id="notify_complete" value="1" checked>
                                <label class="form-check-label ms-1" for="notify_complete">
                                    Notify me when download completes
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions d-flex justify-content-between" style="padding: 12px;">
                        <button type="button" id="checkUrlBtn" class="btn neo-btn btn-secondary mb-4">
                            <i class="fas fa-search me-2"></i> Check URL
                        </button>
                        <button type="submit" class="btn neo-btn mb-4">
                            <i class="fas fa-calendar-plus me-2"></i> Schedule Download
                        </button>
                    </div>
                </form>
            </x-card>

            <!-- URL Preview Card -->
            <div class="neo-card mt-4" id="previewCard" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0">URL Preview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="" alt="Video Thumbnail" id="previewThumbnail" class="img-fluid" style="border: 3px solid #212529; border-radius: 8px;">
                        </div>
                        <div class="col-md-8">
                            <h5 id="previewTitle" class="mb-3"></h5>
                            <div class="mb-2">
                                <strong>Platform:</strong> <span id="previewPlatform"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Duration:</strong> <span id="previewDuration"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Estimated Tokens:</strong> <span id="previewTokens" class="badge bg-warning" style="border: 2px solid #121212;"></span>
                            </div>
                            <div class="mt-3">
                                <div class="alert alert-info" style="border: 2px solid #121212; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);" role="alert">
                                    <i class="fas fa-info-circle me-2"></i> Choose your schedule settings and click "Schedule Download" when ready.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Set min datetime to now
        const now = new Date();
        now.setMinutes(now.getMinutes() + 5); // Minimum 5 minutes from now
        const minDatetime = now.toISOString().slice(0, 16);
        document.getElementById('scheduled_for').setAttribute('min', minDatetime);

        // Toggle recurrence options
        $('#schedule_type').change(function() {
            if(this.value === 'once') {
                $('#recurrenceOptions').slideUp();
                $('#weeklyOptions').slideUp();
            } else {
                $('#recurrenceOptions').slideDown();

                if(this.value === 'weekly') {
                    $('#weeklyOptions').slideDown();
                } else {
                    $('#weeklyOptions').slideUp();
                }
            }
        });

        // URL check button - Simulate this functionality
        $('#checkUrlBtn').click(function() {
            const url = $('input[name="url"]').val();
            if(!url) {
                alert('Please enter a URL to check');
                return;
            }

            // Show loading state
            $(this).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Checking...');

            // Simulate AJAX request with timeout
            setTimeout(function() {
                // Reset button
                $('#checkUrlBtn').html('<i class="fas fa-search me-2"></i> Check URL');

                // Determine platform from URL (simplified)
                let platform = 'unknown';
                if(url.includes('youtube') || url.includes('youtu.be')) {
                    platform = 'YouTube';
                } else if(url.includes('tiktok')) {
                    platform = 'TikTok';
                } else if(url.includes('instagram')) {
                    platform = 'Instagram';
                }

                // Display preview (mock data)
                $('#previewThumbnail').attr('src', 'https://via.placeholder.com/400x225/f8f9fa/212529?text=Video+Thumbnail');
                $('#previewTitle').text('Sample Video Title');
                $('#previewPlatform').text(platform);
                $('#previewDuration').text('3:45');
                $('#previewTokens').text('15');

                // Show preview card
                $('#previewCard').slideDown();

            }, 1500);
        });
    });
</script>
@endpush
