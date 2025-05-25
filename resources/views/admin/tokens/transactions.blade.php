<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\tokens\transactions.blade.php -->
@extends('layouts.admin')

@section('title', 'Token Transactions')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Token Transactions</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tokens.index') }}" class="neo-btn btn-secondary">
                <i class="fas fa-coins me-2"></i> Token Dashboard
            </a>
            <a href="{{ route('admin.tokens.pricing') }}" class="neo-btn btn-secondary">
                <i class="fas fa-tags me-2"></i> Token Pricing
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
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
        <div class="col-xl-3 col-md-6">
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
        <div class="col-xl-3 col-md-6">
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
        <div class="col-xl-3 col-md-6">
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

    <!-- Filter Card -->
    <div class="neo-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Transactions</h5>
            <button class="neo-btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
                <i class="fas fa-filter me-1"></i> Toggle Filters
            </button>
        </div>
        <div class="collapse show" id="filtersCollapse">
            <div class="card-body">
                <form action="{{ route('admin.tokens.transactions') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">User</label>
                        <select name="user_id" class="neo-form-control">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Transaction Type</label>
                        <select name="type" class="neo-form-control">
                            <option value="">All Types</option>
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Min Amount</label>
                        <input type="number" name="min_amount" class="neo-form-control" value="{{ request('min_amount') }}" placeholder="0">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Max Amount</label>
                        <input type="number" name="max_amount" class="neo-form-control" value="{{ request('max_amount') }}" placeholder="1000">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Date From</label>
                        <input type="date" name="date_from" class="neo-form-control" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Date To</label>
                        <input type="date" name="date_to" class="neo-form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sort By</label>
                        <select name="sort" class="neo-form-control">
                            <option value="created_at_desc" {{ request('sort', 'created_at_desc') == 'created_at_desc' ? 'selected' : '' }}>Newest First</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Oldest First</option>
                            <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Amount (High to Low)</option>
                            <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Amount (Low to High)</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="neo-btn w-100">
                                <i class="fas fa-search me-2"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.tokens.transactions') }}" class="neo-btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Action Card -->
    <div class="neo-card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                        <h5 class="mb-3">Adjust User Tokens</h5>
                        <form action="{{ route('admin.tokens.adjust') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Select User</label>
                                <select name="user_id" class="neo-form-control" required>
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }} (Current: {{ $user->token_balance }} tokens)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Token Amount</label>
                                <input type="number" name="amount" class="neo-form-control" required placeholder="Enter positive or negative amount">
                                <small class="text-muted">Use positive for adding, negative for deducting</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Transaction Type</label>
                                <select name="type" class="neo-form-control" required>
                                    <option value="admin_adjustment">Admin Adjustment</option>
                                    <option value="bonus">Bonus</option>
                                    <option value="refund">Refund</option>
                                    <option value="penalty">Penalty</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Description</label>
                                <input type="text" name="description" class="neo-form-control" placeholder="Reason for adjustment">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="neo-btn">
                                    <i class="fas fa-exchange-alt me-2"></i> Adjust Tokens
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                        <h5 class="mb-3">Token Statistics</h5>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Average balance per user:</span>
                                <span class="fw-bold">{{ number_format(User::avg('token_balance'), 2) }} tokens</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Users with zero balance:</span>
                                <span class="fw-bold">{{ User::where('token_balance', 0)->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Highest user balance:</span>
                                <span class="fw-bold">{{ number_format(User::max('token_balance')) }} tokens</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Transactions today:</span>
                                <span class="fw-bold">{{ TokenTransaction::whereDate('created_at', today())->count() }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.tokens.statistics') }}" class="neo-btn w-100">
                            <i class="fas fa-chart-bar me-2"></i> View Full Statistics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="neo-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Token Transactions
                @if(request()->has('user_id') || request()->has('type') || request()->has('min_amount') || request()->has('max_amount') || request()->has('date_from') || request()->has('date_to'))
                    <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 12px;">Filtered</span>
                @endif
            </h5>
            <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 14px;">
                {{ $transactions->total() }} Results
            </span>
        </div>
        <div class="card-body p-0">
            @if($transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Admin</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>
                                        @if($transaction->user)
                                            <a href="{{ route('admin.users.show', $transaction->user_id) }}" class="d-flex align-items-center text-decoration-none">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($transaction->user->name) }}&size=32&background=ff4b2b&color=fff"
                                                    class="rounded-circle me-2" alt="{{ $transaction->user->name }}"
                                                    style="border: 2px solid #212529; width: 32px; height: 32px;">
                                                <div>
                                                    <div class="fw-bold">{{ $transaction->user->name }}</div>
                                                    <div class="small text-muted">{{ $transaction->user->email }}</div>
                                                </div>
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
                                    <td>
                                        @if($transaction->admin)
                                            <a href="{{ route('admin.users.show', $transaction->admin_id) }}" class="text-decoration-none">
                                                <i class="fas fa-user-shield me-1"></i>
                                                {{ $transaction->admin->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div data-bs-toggle="tooltip" title="{{ $transaction->created_at->format('M d, Y H:i:s') }}">
                                            {{ $transaction->created_at->format('M d, Y') }}<br>
                                            <small class="text-muted">{{ $transaction->created_at->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4 mb-3">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @else
                <x-empty-state
                    title="No Token Transactions Found"
                    message="No token transactions found matching your filter criteria."
                    icon="fas fa-coins"
                />
            @endif
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

    .text-purple {
        color: #6f42c1 !important;
    }

    .bg-purple {
        background-color: #6f42c1 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
