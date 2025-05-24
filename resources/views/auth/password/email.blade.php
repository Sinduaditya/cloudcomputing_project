<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\auth\passwords\email.blade.php -->
@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="neo-card">
                <div class="card-header">
                    <h4 class="mb-0">Reset Password</h4>
                </div>
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success mb-4" style="border: 3px solid #121212; border-radius: 8px; box-shadow: 5px 5px 0 rgba(0,0,0,0.3);" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                        </div>
                    @endif

                    <p class="mb-4">Enter your email address and we'll send you a link to reset your password.</p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <x-form-input
                            name="email"
                            label="Email Address"
                            type="email"
                            placeholder="Enter your registered email"
                            :value="old('email')"
                            required
                            autofocus
                        />

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="neo-btn">
                                <i class="fas fa-paper-plane me-2"></i> Send Password Reset Link
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Login
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
