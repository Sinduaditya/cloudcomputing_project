<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width:400px;">
    <h3 class="mb-3">Register</h3>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-success w-100">Register</button>
    </form>
    <div class="mt-3">
        <a href="{{ route('login') }}">Login</a>
    </div>
</div>
@endsection
