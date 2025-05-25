<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\features.blade.php -->
@extends('layouts.app')

@section('title', 'Features')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="text-center mb-5 py-4">
        <h1 class="display-4 fw-bold mb-4">Powerful <span class="text-primary-gradient">Cloud Features</span></h1>
        <p class="lead col-lg-8 mx-auto">Discover all the powerful features that make our cloud downloader the best choice for content creators, media enthusiasts, and professionals</p>
    </div>

    <!-- Feature Categories Navigation -->
    <div class="mb-5">
        <div class="neo-card p-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="fw-bold">Jump to:</span>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#downloading" class="neo-btn btn-sm">Downloading</a>
                    <a href="#processing" class="neo-btn btn-sm">Processing</a>
                    <a href="#storage" class="neo-btn btn-sm">Storage</a>
                    <a href="#scheduling" class="neo-btn btn-sm">Scheduling</a>
                    <a href="#security" class="neo-btn btn-sm">Security</a>
                    <a href="#tokens" class="neo-btn btn-sm">Token System</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Downloading Features -->
    <section id="downloading" class="mb-5 py-4">
        <div class="d-flex align-items-center mb-4">
            <div style="width: 60px; height: 60px; background: var(--primary); border: 3px solid var(--secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; box-shadow: 4px 4px 0 var(--shadow-color);">
                <i class="fas fa-cloud-download-alt fa-2x text-white"></i>
            </div>
            <h2 class="display-6 fw-bold mb-0">Cloud Downloading</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-bolt me-2 text-warning"></i> High-Speed Processing
                    @endslot
                    @slot('body')
                        <p>Our cloud servers handle downloads at blazing speeds, up to 10x faster than your local connection.</p>
                        <ul class="mb-0">
                            <li>Multiple simultaneous downloads</li>
                            <li>Optimized server-to-server transfers</li>
                            <li>No bandwidth throttling</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-globe me-2 text-info"></i> Wide Platform Support
                    @endslot
                    @slot('body')
                        <p>Download content from 100+ popular websites and platforms:</p>
                        <div class="d-flex flex-wrap gap-2 mb-0">
                            <span class="badge bg-light text-dark" style="border: 1px solid var(--secondary);">YouTube</span>
                            <span class="badge bg-light text-dark" style="border: 1px solid var(--secondary);">Vimeo</span>
                            <span class="badge bg-light text-dark" style="border: 1px solid var(--secondary);">Facebook</span>
                            <span class="badge bg-light text-dark" style="border: 1px solid var(--secondary);">Instagram</span>
                            <span class="badge bg-light text-dark" style="border: 1px solid var(--secondary);">Twitter</span>
                            <span class="badge bg-light text-dark" style="border: 1px solid var(--secondary);">TikTok</span>
                            <span class="badge bg-light text-dark" style="border: 1px solid var(--secondary);">Twitch</span>
                            <span class="badge bg-light text-dark" style="border: 1px solid var(--secondary);">+90 more</span>
                        </div>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-film me-2 text-danger"></i> Quality Options
                    @endslot
                    @slot('body')
                        <p>Choose from multiple quality options for your downloads:</p>
                        <ul class="mb-0">
                            <li>4K, 1080p, 720p, and lower resolutions</li>
                            <li>High-bitrate audio extraction</li>
                            <li>Optimized for mobile or desktop viewing</li>
                            <li>Quality preview before downloading</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-link me-2 text-primary"></i> Batch Processing
                    @endslot
                    @slot('body')
                        <p>Handle multiple downloads efficiently:</p>
                        <ul class="mb-0">
                            <li>Bulk URL processing</li>
                            <li>Playlist and channel downloads</li>
                            <li>Queue management</li>
                            <li>Priority downloads for premium users</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
        </div>
    </section>

    <!-- Processing Features -->
    <section id="processing" class="mb-5 py-4">
        <div class="d-flex align-items-center mb-4">
            <div style="width: 60px; height: 60px; background: #6c5ce7; border: 3px solid var(--secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; box-shadow: 4px 4px 0 var(--shadow-color);">
                <i class="fas fa-cogs fa-2x text-white"></i>
            </div>
            <h2 class="display-6 fw-bold mb-0">Media Processing</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-exchange-alt me-2 text-success"></i> Format Conversion
                    @endslot
                    @slot('body')
                        <p>Convert your downloads to different formats:</p>
                        <ul class="mb-0">
                            <li>Video: MP4, MKV, AVI, MOV, WebM</li>
                            <li>Audio: MP3, AAC, FLAC, WAV, OGG</li>
                            <li>Cloud-based transcoding - no local software required</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-cut me-2 text-danger"></i> Basic Editing
                    @endslot
                    @slot('body')
                        <p>Perform simple edits before downloading:</p>
                        <ul class="mb-0">
                            <li>Trim start and end points</li>
                            <li>Extract specific clips</li>
                            <li>Audio extraction from videos</li>
                            <li>Merge multiple files</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-closed-captioning me-2 text-info"></i> Subtitle Processing
                    @endslot
                    @slot('body')
                        <p>Handle subtitles with ease:</p>
                        <ul class="mb-0">
                            <li>Extract subtitles from videos</li>
                            <li>Download subtitles in multiple languages</li>
                            <li>Embed subtitles into video files</li>
                            <li>Convert between subtitle formats</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-compress-arrows-alt me-2 text-warning"></i> Compression
                    @endslot
                    @slot('body')
                        <p>Save space with optimized compression:</p>
                        <ul class="mb-0">
                            <li>Reduce file size while maintaining quality</li>
                            <li>Optimize for mobile devices</li>
                            <li>Batch compression for multiple files</li>
                            <li>Custom compression settings</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
        </div>
    </section>

    <!-- Storage Features -->
    <section id="storage" class="mb-5 py-4">
        <div class="d-flex align-items-center mb-4">
            <div style="width: 60px; height: 60px; background: #00b894; border: 3px solid var(--secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; box-shadow: 4px 4px 0 var(--shadow-color);">
                <i class="fas fa-database fa-2x text-white"></i>
            </div>
            <h2 class="display-6 fw-bold mb-0">Cloud Storage</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-cloud me-2 text-primary"></i> Temporary Storage
                    @endslot
                    @slot('body')
                        <p>All downloads are stored securely in the cloud:</p>
                        <ul class="mb-0">
                            <li>7-day storage for all downloads</li>
                            <li>No storage limits on your device</li>
                            <li>Direct streaming from cloud storage</li>
                            <li>Automatic cleanup of expired files</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-share-alt me-2 text-success"></i> Sharing Options
                    @endslot
                    @slot('body')
                        <p>Share your downloaded content easily:</p>
                        <ul class="mb-0">
                            <li>Generate shareable links</li>
                            <li>Password protection for shared content</li>
                            <li>Expiring links for temporary access</li>
                            <li>Track download statistics</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-lg-12">
                <div class="neo-card overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-8 p-4">
                            <h4 class="fw-bold mb-3">Extended Storage Plans</h4>
                            <p>Need to keep your downloads longer? Our premium storage options offer extended retention periods and additional features:</p>
                            <ul class="mb-4">
                                <li>30-day storage with Basic plan</li>
                                <li>90-day storage with Premium plan</li>
                                <li>Unlimited storage time with Enterprise plan</li>
                                <li>Organize downloads into folders and collections</li>
                                <li>Advanced file management and searching</li>
                            </ul>
                            <a href="{{ route('pricing') }}" class="neo-btn">View Storage Plans</a>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-center" style="background: linear-gradient(45deg, #00b894, #00cec9); border-left: 3px solid var(--secondary);">
                            <div class="text-center p-4">
                                <i class="fas fa-archive fa-5x text-white mb-3" style="filter: drop-shadow(3px 3px 0 rgba(0,0,0,0.3));"></i>
                                <h3 class="text-white fw-bold">Store More</h3>
                                <p class="text-white mb-0">Keep your valuable content safe and accessible</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scheduling Features -->
    <section id="scheduling" class="mb-5 py-4">
        <div class="d-flex align-items-center mb-4">
            <div style="width: 60px; height: 60px; background: #e84393; border: 3px solid var(--secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; box-shadow: 4px 4px 0 var(--shadow-color);">
                <i class="fas fa-calendar-alt fa-2x text-white"></i>
            </div>
            <h2 class="display-6 fw-bold mb-0">Scheduled Downloads</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-clock me-2 text-warning"></i> Timed Downloads
                    @endslot
                    @slot('body')
                        <p>Schedule downloads for specific times:</p>
                        <ul class="mb-0">
                            <li>Set exact date and time for downloads</li>
                            <li>Perfect for live streams that will be available later</li>
                            <li>Queue management and prioritization</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-redo me-2 text-info"></i> Recurring Downloads
                    @endslot
                    @slot('body')
                        <p>Set up automatic recurring downloads:</p>
                        <ul class="mb-0">
                            <li>Daily, weekly, or monthly schedules</li>
                            <li>Perfect for podcasts and series</li>
                            <li>Custom naming patterns for organized files</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-rss me-2 text-danger"></i> Channel Monitoring
                    @endslot
                    @slot('body')
                        <p>Automatically download new content from your favorite creators:</p>
                        <ul class="mb-0">
                            <li>Monitor YouTube channels, playlists</li>
                            <li>Set filters for specific content</li>
                            <li>Get notifications when new content is downloaded</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-bell me-2 text-primary"></i> Notifications
                    @endslot
                    @slot('body')
                        <p>Stay informed about your downloads:</p>
                        <ul class="mb-0">
                            <li>Email notifications when downloads complete</li>
                            <li>Browser notifications</li>
                            <li>Weekly download reports</li>
                            <li>Error alerts and automatic retry options</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
        </div>
    </section>

    <!-- Security Features -->
    <section id="security" class="mb-5 py-4">
        <div class="d-flex align-items-center mb-4">
            <div style="width: 60px; height: 60px; background: #0984e3; border: 3px solid var(--secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; box-shadow: 4px 4px 0 var(--shadow-color);">
                <i class="fas fa-shield-alt fa-2x text-white"></i>
            </div>
            <h2 class="display-6 fw-bold mb-0">Security & Privacy</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-lock me-2 text-success"></i> Secure Processing
                    @endslot
                    @slot('body')
                        <p>Your downloads are protected with industry-standard security:</p>
                        <ul class="mb-0">
                            <li>End-to-end encryption for all transfers</li>
                            <li>No logging of content being downloaded</li>
                            <li>Secure server infrastructure</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-user-shield me-2 text-info"></i> Privacy Controls
                    @endslot
                    @slot('body')
                        <p>We respect your privacy:</p>
                        <ul class="mb-0">
                            <li>No sharing of user data with third parties</li>
                            <li>Automatic deletion of completed downloads after storage period</li>
                            <li>Anonymous download options</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-fingerprint me-2 text-warning"></i> Account Security
                    @endslot
                    @slot('body')
                        <p>Keep your account safe with advanced security features:</p>
                        <ul class="mb-0">
                            <li>Two-factor authentication</li>
                            <li>Login notification alerts</li>
                            <li>Session management</li>
                            <li>Strong password enforcement</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('components.card')
                    @slot('title')
                        <i class="fas fa-virus-slash me-2 text-danger"></i> Content Safety
                    @endslot
                    @slot('body')
                        <p>Safety first for all downloads:</p>
                        <ul class="mb-0">
                            <li>Automatic virus scanning for all downloaded content</li>
                            <li>Malware detection and prevention</li>
                            <li>Safe browsing integration</li>
                        </ul>
                    @endslot
                @endcomponent
            </div>
        </div>
    </section>

    <!-- Token System -->
    <section id="tokens" class="mb-5 py-4">
        <div class="d-flex align-items-center mb-4">
            <div style="width: 60px; height: 60px; background: #f39c12; border: 3px solid var(--secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 20px; box-shadow: 4px 4px 0 var(--shadow-color);">
                <i class="fas fa-coins fa-2x text-white"></i>
            </div>
            <h2 class="display-6 fw-bold mb-0">Token System</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-12">
                <div class="neo-card overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-8 p-4">
                            <h4 class="fw-bold mb-3">Pay As You Go</h4>
                            <p>Our flexible token system ensures you only pay for what you use:</p>
                            <ul class="mb-4">
                                <li>Purchase tokens in bundles at discounted rates</li>
                                <li>Tokens never expire - use them whenever you need</li>
                                <li>No monthly subscription required</li>
                                <li>Token cost based on file size and processing requirements</li>
                                <li>Free tokens for new users to get started</li>
                                <li>Earn bonus tokens through referrals</li>
                            </ul>
                            <a href="{{ route('pricing') }}" class="neo-btn">View Token Packages</a>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-center" style="background: linear-gradient(45deg, #f39c12, #f1c40f); border-left: 3px solid var(--secondary);">
                            <div class="text-center p-4">
                                <i class="fas fa-coins fa-5x text-white mb-3" style="filter: drop-shadow(3px 3px 0 rgba(0,0,0,0.3));"></i>
                                <h3 class="text-white fw-bold">Token Economy</h3>
                                <p class="text-white mb-0">Fair pricing with maximum flexibility</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Comparison -->
    <section class="mb-5 py-5">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Compare Features</h2>
            <p class="lead">See how our service stacks up against other downloaders</p>
        </div>

        <div class="neo-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="min-width: 200px;">Feature</th>
                            <th class="text-center" style="min-width: 150px; background: linear-gradient(45deg, var(--primary-gradient-start), var(--primary-gradient-end)); color: white;">Our Cloud Downloader</th>
                            <th class="text-center" style="min-width: 150px;">Browser Extensions</th>
                            <th class="text-center" style="min-width: 150px;">Desktop Software</th>
                            <th class="text-center" style="min-width: 150px;">Other Online Services</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold">Download Speed</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> Ultra Fast</td>
                            <td class="text-center"><i class="fas fa-times-circle text-danger fa-lg"></i> Limited by your connection</td>
                            <td class="text-center"><i class="fas fa-times-circle text-danger fa-lg"></i> Limited by your connection</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> Fast</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Server Resources</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> Uses cloud servers</td>
                            <td class="text-center"><i class="fas fa-times-circle text-danger fa-lg"></i> Uses your computer</td>
                            <td class="text-center"><i class="fas fa-times-circle text-danger fa-lg"></i> Uses your computer</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> Uses their servers</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Format Conversion</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> 20+ formats</td>
                            <td class="text-center"><i class="fas fa-minus-circle text-warning fa-lg"></i> Limited</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> Multiple formats</td>
                            <td class="text-center"><i class="fas fa-minus-circle text-warning fa-lg"></i> Limited</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Scheduling</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> Advanced</td>
                            <td class="text-center"><i class="fas fa-times-circle text-danger fa-lg"></i> No</td>
                            <td class="text-center"><i class="fas fa-minus-circle text-warning fa-lg"></i> Basic</td>
                            <td class="text-center"><i class="fas fa-minus-circle text-warning fa-lg"></i> Basic</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Platform Support</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> 100+ sites</td>
                            <td class="text-center"><i class="fas fa-minus-circle text-warning fa-lg"></i> Site-specific</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> Many sites</td>
                            <td class="text-center"><i class="fas fa-minus-circle text-warning fa-lg"></i> Limited</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Device Compatibility</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> All devices</td>
                            <td class="text-center"><i class="fas fa-minus-circle text-warning fa-lg"></i> Browser only</td>
                            <td class="text-center"><i class="fas fa-minus-circle text-warning fa-lg"></i> Desktop only</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> All devices</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Pricing Model</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> Pay-per-use tokens</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> Free / One-time</td>
                            <td class="text-center"><i class="fas fa-check-circle text-success fa-lg"></i> One-time purchase</td>
                            <td class="text-center"><i class="fas fa-times-circle text-danger fa-lg"></i> Monthly subscription</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="mb-5 py-4">
        <div class="neo-card bg-primary-gradient text-white p-5 text-center">
            <h2 class="display-5 fw-bold mb-3">Ready to Experience These Features?</h2>
            <p class="lead mb-4">Get started with 10 free tokens and discover the power of cloud downloading</p>
            <div class="d-flex justify-content-center gap-3">
                @guest
                    <a href="{{ route('register') }}" class="neo-btn" style="background: white; color: var(--secondary);">Create Free Account</a>
                    <a href="{{ route('pricing') }}" class="neo-btn" style="background: var(--secondary); color: white;">View Pricing</a>
                @else
                    <a href="{{ route('dashboard') }}" class="neo-btn" style="background: white; color: var(--secondary);">Go to Dashboard</a>
                    <a href="{{ route('downloads.create') }}" class="neo-btn" style="background: var(--secondary); color: white;">Try It Now</a>
                @endguest
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    /* Smooth scrolling for anchor links */
    html {
        scroll-behavior: smooth;
    }

    /* Offset for fixed header */
    section {
        scroll-margin-top: 80px;
    }

    /* Feature icon animation */
    section .fa-2x {
        transition: transform 0.3s ease;
    }

    section:hover .fa-2x {
        transform: scale(1.2);
    }

    /* Table styling */
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th, .table td {
        border: 1px solid #dee2e6;
    }

    .table th {
        border-bottom: 2px solid var(--secondary);
    }

    /* Jump navigation */
    .neo-btn.btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush
