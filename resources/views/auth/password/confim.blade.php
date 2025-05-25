<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\auth\passwords\confirm.blade.php -->
@extends('layouts.app')

@section('title', 'Confirm Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="neo-card">
                <div class="card-header">
                    <h4 class="mb-0">Confirm Password</h4>
                </div>
                <div class="card-body p-4">
                    <p class="mb-4">
                        This is a secure area of the application. Please confirm your password before continuing.
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <x-form-input
                            name="password"
                            label="Password"
                            type="password"
                            placeholder="Enter your password"
                            required
                        />

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="neo-btn">
                                <i class="fas fa-shield-alt me-2"></i> Confirm Password
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">
                                <a href="{{ route('password.request') }}" class="text-decoration-none fw-bold">
                                    Forgot Your Password?
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
