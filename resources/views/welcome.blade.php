<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\welcome.blade.php -->
@extends('layouts.app')

@section('title', 'Cloud Media Downloader')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5 py-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4">Download & Stream <span class="text-primary-gradient">Media From Anywhere</span></h1>
            <p class="lead mb-4">The cloud-powered platform that lets you download videos, audio, and more from hundreds of websites with powerful cloud processing and token-based pricing.</p>
            <div class="d-flex gap-3 mb-4">
                @guest
                    <a href="{{ route('register') }}" class="neo-btn">Get Started Free</a>
                    <a href="{{ route('login') }}" class="neo-btn btn-secondary">Log In</a>
                @else
                    <a href="{{ route('dashboard') }}" class="neo-btn">Go to Dashboard</a>
                    <a href="{{ route('downloads.create') }}" class="neo-btn btn-secondary">New Download</a>
                @endguest
            </div>
            <div class="d-flex align-items-center">
                <div class="pe-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-star text-warning me-1"></i>
                        <i class="fas fa-star text-warning me-1"></i>
                        <i class="fas fa-star text-warning me-1"></i>
                        <i class="fas fa-star text-warning me-1"></i>
                        <i class="fas fa-star-half-alt text-warning me-2"></i>
                        <span class="fw-bold">4.8/5</span>
                    </div>
                    <small>from 1,200+ reviews</small>
                </div>
                <div class="vr mx-3" style="height: 40px;"></div>
                <div>
                    <div class="fw-bold">10,000+</div>
                    <small>Active Users</small>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="position-relative">
                <img src="{{ asset('images/hero-image.png') }}" alt="Cloud Downloader" class="img-fluid" style="border: 3px solid var(--secondary); border-radius: 12px; box-shadow: 10px 10px 0 var(--shadow-color);">
                <div class="position-absolute" style="top: -20px; right: -20px; background: yellow; border: 3px solid var(--secondary); border-radius: 50%; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; transform: rotate(15deg); box-shadow: 5px 5px 0 var(--shadow-color);">
                    <div class="text-center">
                        <div class="fw-bold" style="font-size: 1.5rem;">50%</div>
                        <div style="font-size: 0.8rem;">FASTER</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Supported Platforms -->
    <div class="text-center mb-5">
        <h6 class="text-uppercase mb-4">Works with all major platforms</h6>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex flex-wrap justify-content-center gap-4">
                    <div class="platform-icon">
                        <i class="fab fa-youtube fa-2x"></i>
                        <span>YouTube</span>
                    </div>
                    <div class="platform-icon">
                        <i class="fab fa-vimeo fa-2x"></i>
                        <span>Vimeo</span>
                    </div>
                    <div class="platform-icon">
                        <i class="fab fa-instagram fa-2x"></i>
                        <span>Instagram</span>
                    </div>
                    <div class="platform-icon">
                        <i class="fab fa-facebook fa-2x"></i>
                        <span>Facebook</span>
                    </div>
                    <div class="platform-icon">
                        <i class="fab fa-twitter fa-2x"></i>
                        <span>Twitter</span>
                    </div>
                    <div class="platform-icon">
                        <i class="fab fa-tiktok fa-2x"></i>
                        <span>TikTok</span>
                    </div>
                    <div class="platform-icon">
                        <i class="fas fa-plus fa-2x"></i>
                        <span>50+ More</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="mb-5 py-4">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Why Choose Our Cloud Downloader?</h2>
            <p class="lead">Get more than just a downloader - experience the power of cloud processing</p>
        </div>

        <div class="row g-4">
            @component('components.card')
                @slot('title')
                    <i class="fas fa-cloud-download-alt me-2" style="color: var(--primary)"></i> Cloud-Powered Downloads
                @endslot
                @slot('body')
                    Download files using our servers, not your bandwidth. Get your files faster with our optimized cloud infrastructure regardless of your connection speed.
                @endslot
            @endcomponent

            @component('components.card')
                @slot('title')
                    <i class="fas fa-lock me-2" style="color: var(--success)"></i> Secure & Private
                @endslot
                @slot('body')
                    All your downloads are processed securely in the cloud. No plugins or software installations required, and your privacy is always protected.
                @endslot
            @endcomponent

            @component('components.card')
                @slot('title')
                    <i class="fas fa-coins me-2" style="color: var(--warning)"></i> Token-Based System
                @endslot
                @slot('body')
                    Our flexible token system lets you pay only for what you need. Purchase tokens in bundles and use them when you want, with no monthly commitments.
                @endslot
            @endcomponent

            @component('components.card')
                @slot('title')
                    <i class="fas fa-cogs me-2" style="color: var(--info)"></i> Format Conversion
                @endslot
                @slot('body')
                    Convert downloaded media to different formats automatically. Extract audio from videos, change resolutions, and more with our powerful cloud processing.
                @endslot
            @endcomponent
        </div>
    </div>

    <!-- How It Works -->
    <div class="mb-5 py-4">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">How It Works</h2>
            <p class="lead">Three simple steps to download anything from the web</p>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="neo-card h-100 text-center px-3 py-4">
                    <div class="position-relative mb-4">
                        <div class="position-absolute" style="top: -15px; left: -15px; background: var(--primary); color: white; border: 3px solid var(--secondary); border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.5rem; box-shadow: 3px 3px 0 var(--shadow-color);">1</div>
                        <i class="fas fa-link fa-4x mb-3" style="color: var(--primary)"></i>
                    </div>
                    <h4 class="fw-bold">Paste Your URL</h4>
                    <p>Simply paste the link of the content you want to download from any supported website.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="neo-card h-100 text-center px-3 py-4">
                    <div class="position-relative mb-4">
                        <div class="position-absolute" style="top: -15px; left: -15px; background: var(--primary); color: white; border: 3px solid var(--secondary); border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.5rem; box-shadow: 3px 3px 0 var(--shadow-color);">2</div>
                        <i class="fas fa-server fa-4x mb-3" style="color: var(--primary)"></i>
                    </div>
                    <h4 class="fw-bold">Cloud Processing</h4>
                    <p>Our servers process your request in the cloud, so you don't have to use your device resources.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="neo-card h-100 text-center px-3 py-4">
                    <div class="position-relative mb-4">
                        <div class="position-absolute" style="top: -15px; left: -15px; background: var(--primary); color: white; border: 3px solid var(--secondary); border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.5rem; box-shadow: 3px 3px 0 var(--shadow-color);">3</div>
                        <i class="fas fa-download fa-4x mb-3" style="color: var(--primary)"></i>
                    </div>
                    <h4 class="fw-bold">Get Your Content</h4>
                    <p>Download or stream your processed content instantly, or save it for later access.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="mb-5 py-4">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">What Our Users Say</h2>
            <p class="lead">Join thousands of satisfied users who trust our cloud downloader</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="neo-card h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://ui-avatars.com/api/?name=John+Smith&background=random&color=fff&size=48" alt="User" class="rounded-circle me-3" style="border: 2px solid var(--secondary);">
                        <div>
                            <h5 class="mb-0 fw-bold">John Smith</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mb-0">"This service is a game-changer! I can now download my favorite content even on my slow internet connection because it all happens in the cloud."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="neo-card h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=random&color=fff&size=48" alt="User" class="rounded-circle me-3" style="border: 2px solid var(--secondary);">
                        <div>
                            <h5 class="mb-0 fw-bold">Sarah Johnson</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mb-0">"The token system is really flexible. I love that I only pay for what I use instead of a monthly subscription that I might not fully utilize."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="neo-card h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://ui-avatars.com/api/?name=David+Wang&background=random&color=fff&size=48" alt="User" class="rounded-circle me-3" style="border: 2px solid var(--secondary);">
                        <div>
                            <h5 class="mb-0 fw-bold">David Wang</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mb-0">"The ability to convert videos to different formats right in the cloud saves me so much time. No more downloading and then converting locally!"</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="mb-5 py-5">
        <div class="neo-card bg-primary-gradient text-white p-5 text-center">
            <h2 class="display-5 fw-bold mb-3">Ready to Get Started?</h2>
            <p class="lead mb-4">Join thousands of users who trust our cloud downloader service daily</p>
            <div class="d-flex justify-content-center gap-3">
                @guest
                    <a href="{{ route('register') }}" class="neo-btn" style="background: white; color: var(--secondary);">Create Free Account</a>
                    <a href="{{ route('pricing') }}" class="neo-btn" style="background: var(--secondary); color: white;">View Pricing</a>
                @else
                    <a href="{{ route('dashboard') }}" class="neo-btn" style="background: white; color: var(--secondary);">Go to Dashboard</a>
                    <a href="{{ route('tokens.purchase') }}" class="neo-btn" style="background: var(--secondary); color: white;">Buy Tokens</a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .platform-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px 15px;
    }

    .platform-icon span {
        margin-top: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Animated gradient background for cards on hover */
    .neo-card:hover {
        background-size: 200% 200%;
        animation: gradientShift 3s ease infinite;
    }

    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
</style>
@endpush
