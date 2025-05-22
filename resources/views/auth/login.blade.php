<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mt-5" style="max-width:400px;">
        <div class="row justify-content-center">
            <h3 class="mb-3">Login</h3>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Login</button>
            </form>
            <div class="mt-3">
                <a href="{{ route('register') }}">Register</a>
            </div>

            <div class="col-md-8 mt-3">
                <div class="card">
                    <div class="card-header">{{ __('OAuth Login') }}</div>
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a href="{{ url('auth/google') }}" class="btn btn-danger btn-block">
                                    <i class="fab fa-google"></i> Login with Google
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
