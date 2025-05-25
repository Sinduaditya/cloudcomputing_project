<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\auth\passwords\reset.blade.php -->
@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="neo-card">
                <div class="card-header">
                    <h4 class="mb-0">Set New Password</h4>
                </div>
                <div class="card-body p-4">
                    <p class="mb-4">Create a new password for your account.</p>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <x-form-input
                            name="email"
                            label="Email Address"
                            type="email"
                            placeholder="Enter your email"
                            :value="$email ?? old('email')"
                            required
                            autofocus
                            readonly="readonly"
                        />

                        <x-form-input
                            name="password"
                            label="New Password"
                            type="password"
                            placeholder="Enter your new password"
                            required
                        />

                        <x-form-input
                            name="password_confirmation"
                            label="Confirm New Password"
                            type="password"
                            placeholder="Confirm your new password"
                            required
                        />

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="neo-btn">
                                <i class="fas fa-lock me-2"></i> Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
