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
        </div>
        <div class="col-lg-6">
            <div class="position-relative">
                <img src="{{ asset('images/4743634.png') }}" alt="Cloud Downloader" class="img-fluid" style="border: 3px solid var(--secondary); border-radius: 12px; box-shadow: 10px 10px 0 var(--shadow-color); width: 75%">
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
                        <i class="fab fa-instagram fa-2x"></i>
                        <span>Instagram</span>
                    </div>
                    <div class="platform-icon">
                        <i class="fab fa-tiktok fa-2x"></i>
                        <span>TikTok</span>
                    </div>
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
