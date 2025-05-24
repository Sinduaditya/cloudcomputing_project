<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\auth\login.blade.php -->
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="neo-card">
                <div class="card-header">
                    <h4 class="mb-0">Login to {{ config('app.name') }}</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <x-form-input
                            name="email"
                            label="Email Address"
                            type="email"
                            placeholder="Enter your email"
                            :value="old('email')"
                            required
                            autofocus
                        />

                        <x-form-input
                            name="password"
                            label="Password"
                            type="password"
                            placeholder="Enter your password"
                            required
                        />

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="neo-btn">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </div>

                        <div class="text-center">
                            <p>
                                {{-- <a href="{{ route('password.request') }}" class="text-decoration-none fw-bold">
                                    Forgot Your Password?
                                </a> --}}
                            </p>
                            <p class="mb-0">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="text-decoration-none fw-bold">
                                    Register Now
                                </a>
                            </p>
                        </div>
                    </form>

                    <hr class="my-4" style="border-top: 2px dashed #212529;">

                    <div class="text-center">
                        <p class="mb-3 fw-bold">Or Sign In With</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('oauth.redirect', 'google') }}" class="neo-btn btn-secondary">
                                <i class="fab fa-google me-2"></i> Google
                            </a>
                            <a href="{{ route('oauth.redirect', 'facebook') }}" class="neo-btn btn-secondary">
                                <i class="fab fa-facebook-f me-2"></i> Facebook
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-2"></i> Back to Homepage
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-check-input {
        border: 2px solid #212529;
        width: 20px;
        height: 20px;
    }
    .form-check-label {
        padding-left: 5px;
    }
</style>
@endpush
