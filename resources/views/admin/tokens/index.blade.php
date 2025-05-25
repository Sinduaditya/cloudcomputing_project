<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\tokens\index.blade.php -->
@extends('layouts.admin')

@section('title', 'Token Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Token Management</h1>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="neo-btn btn-secondary">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #4CAF50, #8BC34A);">
                    <i class="fas fa-coins fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['total_tokens_in_circulation']) }}</h3>
                    <p class="mb-0">Tokens in Circulation</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #2196F3, #03A9F4);">
                    <i class="fas fa-exchange-alt fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['total_transactions']) }}</h3>
                    <p class="mb-0">Total Transactions</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #FF9800, #FF5722);">
                    <i class="fas fa-shopping-cart fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['total_tokens_spent']) }}</h3>
                    <p class="mb-0">Tokens Spent</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #9C27B0, #673AB7);">
                    <i class="fas fa-gift fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['total_tokens_issued']) }}</h3>
                    <p class="mb-0">Tokens Issued</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Token Management Sections -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Adjust Tokens Form -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Adjust User Tokens</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tokens.adjust') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Select User</label>
                            <select name="user_id" class="neo-form-control" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} (Current: {{ $user->token_balance }} tokens)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Choose the user whose token balance you want to adjust</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Token Amount</label>
                            <input type="number" name="amount" class="neo-form-control" required placeholder="Enter positive or negative amount">
                            <small class="text-muted">Use positive value to add tokens, negative to deduct</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Transaction Type</label>
                            <select name="type" class="neo-form-control" required>
                                <option value="admin_adjustment">Admin Adjustment</option>
                                <option value="bonus">Bonus</option>
                                <option value="refund">Refund</option>
                                <option value="penalty">Penalty</option>
                                <option value="reward">Reward</option>
                                <option value="correction">Balance Correction</option>
                            </select>
                            <small class="text-muted">Categorize this token adjustment</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Description</label>
                            <input type="text" name="description" class="neo-form-control" placeholder="Reason for adjustment">
                            <small class="text-muted">Optional note about this adjustment</small>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="neo-btn">
                                <i class="fas fa-exchange-alt me-2"></i> Process Token Adjustment
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="neo-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Token Transactions</h5>
                    <a href="{{ route('admin.tokens.transactions') }}" class="neo-btn btn-sm btn-secondary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions->take(5) as $transaction)
                                    <tr>
                                        <td>
                                            @if($transaction->user)
                                                <a href="{{ route('admin.users.show', $transaction->user_id) }}" class="d-flex align-items-center text-decoration-none">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($transaction->user->name) }}&size=32&background=ff4b2b&color=fff"
                                                        class="rounded-circle me-2" alt="{{ $transaction->user->name }}"
                                                        style="border: 2px solid #212529; width: 32px; height: 32px;">
                                                    <div class="fw-bold">{{ $transaction->user->name }}</div>
                                                </a>
                                            @else
                                                <span class="text-muted">System</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill
                                                @if(str_contains($transaction->type, 'purchase')) bg-success
                                                @elseif(str_contains($transaction->type, 'download')) bg-info
                                                @elseif(str_contains($transaction->type, 'refund')) bg-warning
                                                @elseif(str_contains($transaction->type, 'adjustment')) bg-primary
                                                @elseif(str_contains($transaction->type, 'initial')) bg-secondary
                                                @elseif(str_contains($transaction->type, 'bonus')) bg-purple
                                                @else bg-light text-dark @endif"
                                                style="border: 1px solid #212529;">
                                                {{ ucwords(str_replace('_', ' ', $transaction->type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="{{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $transaction->description }}">
                                                {{ $transaction->description ?: 'No description' }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Links Card -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Token Management</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tokens.transactions') }}" class="neo-btn">
                            <i class="fas fa-exchange-alt me-2"></i> View All Transactions
                        </a>
                        <a href="{{ route('admin.tokens.pricing') }}" class="neo-btn">
                            <i class="fas fa-tags me-2"></i> Token Pricing Settings
                        </a>
                        <a href="#" class="neo-btn btn-secondary">
                            <i class="fas fa-chart-bar me-2"></i> Token Statistics
                        </a>
                    </div>
                </div>
            </div>

            <!-- Current Settings -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Current Token Settings</h5>
                </div>
                <div class="card-body">
                    <div class="p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Token Price:</span>
                            <span class="fw-bold">${{ config('app.token_price', 0.10) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>MB Per Token:</span>
                            <span class="fw-bold">{{ config('download.mb_per_token', 10) }} MB</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Min Tokens Per Download:</span>
                            <span class="fw-bold">{{ config('download.min_tokens', 1) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Free Tokens For New Users:</span>
                            <span class="fw-bold">{{ config('app.default_token_balance', 10) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Users by Balance -->
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">Top Token Balances</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($users->sortByDesc('token_balance')->take(5) as $user)
                            <a href="{{ route('admin.users.show', $user->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=32&background=ff4b2b&color=fff"
                                            class="rounded-circle me-2" alt="{{ $user->name }}"
                                            style="border: 2px solid #212529; width: 32px; height: 32px;">
                                        <div>{{ $user->name }}</div>
                                    </div>
                                    <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 14px;">
                                        {{ number_format($user->token_balance) }} tokens
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .neo-form-control {
        border: 2px solid #212529;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .neo-form-control:focus {
        border-color: #ff4b2b;
        box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2);
        outline: none;
    }

    .neo-card {
        border: 3px solid #212529;
        border-radius: 8px;
        box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
        background: #ffffff;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .neo-card .card-header {
        background: linear-gradient(90deg, #ff9a9e 0%, #fad0<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\tokens\index.blade.php -->
@extends('layouts.admin')

@section('title', 'Token Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Token Management</h1>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="neo-btn btn-secondary">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #4CAF50, #8BC34A);">
                    <i class="fas fa-coins fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['total_tokens_in_circulation']) }}</h3>
                    <p class="mb-0">Tokens in Circulation</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #2196F3, #03A9F4);">
                    <i class="fas fa-exchange-alt fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['total_transactions']) }}</h3>
                    <p class="mb-0">Total Transactions</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #FF9800, #FF5722);">
                    <i class="fas fa-shopping-cart fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['total_tokens_spent']) }}</h3>
                    <p class="mb-0">Tokens Spent</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #9C27B0, #673AB7);">
                    <i class="fas fa-gift fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['total_tokens_issued']) }}</h3>
                    <p class="mb-0">Tokens Issued</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Token Management Sections -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Adjust Tokens Form -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Adjust User Tokens</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tokens.adjust') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Select User</label>
                            <select name="user_id" class="neo-form-control" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} (Current: {{ $user->token_balance }} tokens)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Choose the user whose token balance you want to adjust</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Token Amount</label>
                            <input type="number" name="amount" class="neo-form-control" required placeholder="Enter positive or negative amount">
                            <small class="text-muted">Use positive value to add tokens, negative to deduct</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Transaction Type</label>
                            <select name="type" class="neo-form-control" required>
                                <option value="admin_adjustment">Admin Adjustment</option>
                                <option value="bonus">Bonus</option>
                                <option value="refund">Refund</option>
                                <option value="penalty">Penalty</option>
                                <option value="reward">Reward</option>
                                <option value="correction">Balance Correction</option>
                            </select>
                            <small class="text-muted">Categorize this token adjustment</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Description</label>
                            <input type="text" name="description" class="neo-form-control" placeholder="Reason for adjustment">
                            <small class="text-muted">Optional note about this adjustment</small>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="neo-btn">
                                <i class="fas fa-exchange-alt me-2"></i> Process Token Adjustment
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="neo-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Token Transactions</h5>
                    <a href="{{ route('admin.tokens.transactions') }}" class="neo-btn btn-sm btn-secondary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions->take(5) as $transaction)
                                    <tr>
                                        <td>
                                            @if($transaction->user)
                                                <a href="{{ route('admin.users.show', $transaction->user_id) }}" class="d-flex align-items-center text-decoration-none">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($transaction->user->name) }}&size=32&background=ff4b2b&color=fff"
                                                        class="rounded-circle me-2" alt="{{ $transaction->user->name }}"
                                                        style="border: 2px solid #212529; width: 32px; height: 32px;">
                                                    <div class="fw-bold">{{ $transaction->user->name }}</div>
                                                </a>
                                            @else
                                                <span class="text-muted">System</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill
                                                @if(str_contains($transaction->type, 'purchase')) bg-success
                                                @elseif(str_contains($transaction->type, 'download')) bg-info
                                                @elseif(str_contains($transaction->type, 'refund')) bg-warning
                                                @elseif(str_contains($transaction->type, 'adjustment')) bg-primary
                                                @elseif(str_contains($transaction->type, 'initial')) bg-secondary
                                                @elseif(str_contains($transaction->type, 'bonus')) bg-purple
                                                @else bg-light text-dark @endif"
                                                style="border: 1px solid #212529;">
                                                {{ ucwords(str_replace('_', ' ', $transaction->type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="{{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $transaction->description }}">
                                                {{ $transaction->description ?: 'No description' }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Links Card -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Token Management</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tokens.transactions') }}" class="neo-btn">
                            <i class="fas fa-exchange-alt me-2"></i> View All Transactions
                        </a>
                        <a href="{{ route('admin.tokens.pricing') }}" class="neo-btn">
                            <i class="fas fa-tags me-2"></i> Token Pricing Settings
                        </a>
                        <a href="#" class="neo-btn btn-secondary">
                            <i class="fas fa-chart-bar me-2"></i> Token Statistics
                        </a>
                    </div>
                </div>
            </div>

            <!-- Current Settings -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Current Token Settings</h5>
                </div>
                <div class="card-body">
                    <div class="p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Token Price:</span>
                            <span class="fw-bold">${{ config('app.token_price', 0.10) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>MB Per Token:</span>
                            <span class="fw-bold">{{ config('download.mb_per_token', 10) }} MB</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Min Tokens Per Download:</span>
                            <span class="fw-bold">{{ config('download.min_tokens', 1) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Free Tokens For New Users:</span>
                            <span class="fw-bold">{{ config('app.default_token_balance', 10) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Users by Balance -->
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">Top Token Balances</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($users->sortByDesc('token_balance')->take(5) as $user)
                            <a href="{{ route('admin.users.show', $user->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=32&background=ff4b2b&color=fff"
                                            class="rounded-circle me-2" alt="{{ $user->name }}"
                                            style="border: 2px solid #212529; width: 32px; height: 32px;">
                                        <div>{{ $user->name }}</div>
                                    </div>
                                    <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 14px;">
                                        {{ number_format($user->token_balance) }} tokens
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .neo-form-control {
        border: 2px solid #212529;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .neo-form-control:focus {
        border-color: #ff4b2b;
        box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2);
        outline: none;
    }

    .neo-card {
        border: 3px solid #212529;
        border-radius: 8px;
        box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
        background: #ffffff;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .neo-card .card-header {
        background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%);
        border-bottom: 2px solid #212529;
        padding: 1rem;
    }

    .neo-btn {
        display: inline-block;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border: 2px solid #212529;
        border-radius: 0.375rem;
        background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%);
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.2);
        transition: transform 0.1s, box-shadow 0.1s;
        cursor: pointer;
        text-decoration: none;
        color: #212529;
    }

    .neo-btn:hover {
        transform: translate(-1px, -1px);
        box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2);
    }

    .neo-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 2px 2px 0 rgba(0, 0, 0, 0.2);
    }

    .neo-btn.btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .neo-btn.btn-secondary {
        background: #f8f9fa;
    }

    .neo-stat-card {
        display: flex;
        align-items: center;
        padding: 1rem;
        border: 3px solid #212529;
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
    }

    .neo-stat-card .icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 8px;
        border: 2px solid #212529;
        margin-right: 1rem;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .neo-stat-card .stat-content h3 {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        border-bottom: 2px solid #212529;
        background-color: #f8f9fa;
    }

    .table td {
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }

    .badge {
        padding: 0.35em 0.65em;
        font-weight: 600;
    }

    .text-purple {
        color: #6f42c1 !important;
    }

    .list-group-item {
        border-left: 0;
        border-right: 0;
        border-top: 0;
        border-bottom: 1px solid #dee2e6;
    }

    .list-group-item:last-child {
        border-bottom: 0;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select2 initialization for user dropdown if available
        if (typeof $.fn.select2 !== 'undefined') {
            $('select[name="user_id"]').select2({
                placeholder: 'Select User',
                allowClear: true,
                width: '100%'
            });
        }

        // Form validation
        const form = document.querySelector('form[action="{{ route("admin.tokens.adjust") }}"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                const userId = this.querySelector('[name="user_id"]').value;
                const amount = this.querySelector('[name="amount"]').value;

                if (!userId) {
                    e.preventDefault();
                    alert('Please select a user');
                    return;
                }

                if (!amount || amount === '0') {
                    e.preventDefault();
                    alert('Please enter a non-zero amount');
                    return;
                }

                // Confirm large adjustments
                if (Math.abs(amount) > 100) {
                    if (!confirm(`You are about to ${amount > 0 ? 'add' : 'deduct'} ${Math.abs(amount)} tokens. Are you sure?`)) {
                        e.preventDefault();
                    }
                }
            });
        }

        // Handle tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Chart initialization if present
        if (typeof Chart !== 'undefined' && document.getElementById('tokenTransactionsChart')) {
            const ctx = document.getElementById('tokenTransactionsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['dates'] ?? []) !!},
                    datasets: [{
                        label: 'Token Transactions',
                        data: {!! json_encode($chartData['values'] ?? []) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
