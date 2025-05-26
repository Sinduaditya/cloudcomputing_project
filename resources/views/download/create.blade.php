<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\download\create.blade.php -->
@extends('layouts.app')

@section('title', 'Create Download')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">New Download</h1>
                    <div>
                        <a href="{{ route('downloads.index') }}" class="neo-btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Downloads
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
                                <h5 class="mb-1">Token Balance: <span
                                        class="fw-bold">{{ auth()->user()->token_balance }}</span></h5>
                                <p class="mb-0 text-muted">Downloads require tokens based on file size. <a
                                        href="{{ route('tokens.purchase') }}" class="text-decoration-none fw-bold">Need
                                        more?</a></p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('downloads.store') }}" method="POST" id="downloadForm">
                        @csrf
                        <div class="options-container p-3" style="border: 2px solid #212529; border-radius: 8px;">
                            <div class="mb-4">
                                <x-form-input name="url" label="Video URL"
                                    placeholder="Paste YouTube, TikTok, or Instagram URL here" :value="old('url')" required
                                    autofocus />
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
                                    <h5 class="mb-1">Download Options</h5>
                                </div>
                            </div>
                        </div>  
                            <div class="options-container p-3" style="border: 2px solid #212529; border-radius: 8px;">
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                        style="border: 2px solid #212529; width: 20px; height: 20px;" type="checkbox"
                                        name="trim_video" id="trim_video" value="1">
                                    <label class="form-check-label" for="trim_video">
                                        <span class="ms-1">Trim Video</span>
                                    </label>
                                </div>
                                <div class="trim-options ms-4 mt-2 mb-3" id="trimOptions" style="display: none;">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label">Start Time (mm:ss)</label>
                                            <input type="text" class="neo-form-control" name="start_time"
                                                placeholder="00:00">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">End Time (mm:ss)</label>
                                            <input type="text" class="neo-form-control" name="end_time"
                                                placeholder="00:00">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                        style="border: 2px solid #212529; width: 20px; height: 20px;" type="checkbox"
                                        name="notify_complete" id="notify_complete" value="1" checked>
                                    <label class="form-check-label" for="notify_complete">
                                        <span class="ms-1">Notify me when download completes</span>
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input"
                                        style="border: 2px solid #212529; width: 20px; height: 20px;" type="checkbox"
                                        name="schedule_later" id="schedule_later" value="1">
                                    <label class="form-check-label" for="schedule_later">
                                        <span class="ms-1">Schedule for later</span>
                                    </label>
                                </div>
                                <div class="schedule-options ms-4 mt-2" id="scheduleOptions" style="display: none;">
                                    <input type="datetime-local" class="neo-form-control" name="scheduled_for">
                                </div>

                                <div class="form-check mt-2">
                                    <input class="form-check-input"
                                        style="border: 2px solid #212529; width: 20px; height: 20px;" type="checkbox"
                                        name="is_public" id="is_public" value="1">
                                    <label class="form-check-label" for="is_public">
                                        <span class="ms-1">Make download public</span>
                                    </label>
                                    <div class="form-text ms-4">Public downloads can be shared with others without
                                        requiring login</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions d-flex justify-content-between" style="padding: 12px;">
                            <button type="button" id="checkUrlBtn" class="btn neo-btn btn-secondary mb-4">
                                <i class="fas fa-search me-2"></i> Check URL
                            </button>
                            <button type="submit" class="btn neo-btn mb-4">
                                <i class="fas fa-download me-2"></i> Start Download
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
                                <img src="" alt="Video Thumbnail" id="previewThumbnail" class="img-fluid"
                                    style="border: 3px solid #212529; border-radius: 8px;">
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
                                    <strong>Estimated Tokens:</strong> <span id="previewTokens" class="badge bg-warning"
                                        style="border: 2px solid #121212;"></span>
                                </div>
                                <div class="mt-3">
                                    <div class="alert alert-info"
                                        style="border: 2px solid #121212; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);"
                                        role="alert">
                                        <i class="fas fa-info-circle me-2"></i> Review the information above and click
                                        "Start Download" when ready.
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
            $('select[name="format"]').change(function() {
                if ($(this).val() === 'mp3') {
                    $('#quality-container').hide();
                    $('select[name="quality"]').val('high').prop('required', false);
                } else {
                    $('#quality-container').show();
                    $('select[name="quality"]').prop('required', true);
                }
            }).trigger('change');
            // Toggle trim options
            $('#trim_video').change(function() {
                if (this.checked) {
                    $('#trimOptions').slideDown();
                } else {
                    $('#trimOptions').slideUp();
                }
            });

            // Toggle schedule options
            $('#schedule_later').change(function() {
                if (this.checked) {
                    $('#scheduleOptions').slideDown();
                } else {
                    $('#scheduleOptions').slideUp();
                }
            });

            // URL check button - Simulate this functionality
            $('#checkUrlBtn').click(function() {
                const url = $('input[name="url"]').val();
                if (!url) {
                    alert('Please enter a URL to check');
                    return;
                }

                // Show loading state
                $(this).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Checking...'
                );

                // Make AJAX call to check URL
                $.ajax({
                    url: '{{ route('api.url.check') }}',
                    method: 'POST',
                    data: {
                        url: url,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Reset button
                        $('#checkUrlBtn').html('<i class="fas fa-search me-2"></i> Check URL');

                        if (response.success) {
                            // Display preview
                            $('#previewThumbnail').attr('src', response.thumbnail ||
                                'https://via.placeholder.com/400x225/f8f9fa/212529?text=Video+Thumbnail'
                            );
                            $('#previewTitle').text(response.title || 'Unknown Title');
                            $('#previewPlatform').text(response.platform || 'Unknown');
                            $('#previewDuration').text(response.duration || 'Unknown');
                            $('#previewTokens').text(response.estimated_tokens || '10-20');

                            // Auto-fill custom title if empty
                            if (!$('input[name="custom_title"]').val() && response.title) {
                                $('input[name="custom_title"]').val(response.title);
                            }

                            // Show preview card
                            $('#previewCard').slideDown();
                        } else {
                            alert(response.message ||
                                'Invalid URL. Please check and try again.');
                        }
                    },
                    error: function() {
                        $('#checkUrlBtn').html('<i class="fas fa-search me-2"></i> Check URL');
                        alert('Error checking URL. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
