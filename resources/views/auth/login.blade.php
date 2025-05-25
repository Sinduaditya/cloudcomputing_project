<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\auth\login.blade.php -->
@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="neo-card">
                    <div class="card-header py-3 text-center bg-light">
                        <h4 class="mb-0 fw-bold">Login to {{ config('app.name') }}</h4>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <x-form-input
                                    name="email"
                                    label="Email Address"
                                    type="email"
                                    placeholder="Enter your email"
                                    value="{{ old('email') }}"
                                    class="w-100 form-control-lg"
                                    required
                                    autocomplete="email"
                                    autofocus
                                />
                            </div>

                            <div class="mb-4">
                                <x-form-input
                                    name="password"
                                    label="Password"
                                    type="password"
                                    placeholder="Enter your password"
                                    class="w-100 form-control-lg"
                                    required
                                    autocomplete="current-password"
                                />
                            </div>

                            <div class="mb-4 d-flex align-items-center">
                                <input type="checkbox" class="form-check-input me-2" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>

                            <div class="d-grid gap-2 mb-4">
                                <button type="submit" class="neo-btn btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i> Login
                                </button>
                            </div>

                            <div class="text-center mb-3">
                                <p class="mb-2">
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

                        <hr class="my-4" style="border-top: 2px dashed rgba(0,0,0,0.2);">

                        <div class="text-center">
                            <p class="mb-3 fw-bold">Or Sign In With</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('oauth.redirect', 'google') }}" class="neo-btn btn-secondary btn-lg">
                                    <i class="fab fa-google me-2"></i> Google
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
