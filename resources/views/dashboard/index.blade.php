<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Welcome, {{ auth()->user()->name }}</h2>
    <div class="alert alert-info mt-3">
        Token Balance: <strong>{{ auth()->user()->token_balance }}</strong>
    </div>
    <div class="mt-4">
        <a href="{{ route('downloads.index') }}" class="btn btn-primary">My Downloads</a>
        <a href="{{ route('schedules.index') }}" class="btn btn-warning">My Schedules</a>
        <a href="{{ route('tokens.index') }}" class="btn btn-success">Token History</a>
    </div>
</div>
@endsection
