<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\tokens\history.blade.php -->
@extends('layouts.app')

@section('title', 'Token History')

@section('content')
<div class="container py-4">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Token Transaction History</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('tokens.balance') }}" class="neo-btn btn-secondary">
                <i class="fas fa-coins me-2"></i> Token Balance
            </a>
            <a href="{{ route('tokens.purchase') }}" class="neo-btn">
                <i class="fas fa-plus-circle me-2"></i> Buy Tokens
            </a>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="neo-card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filter Transactions
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('tokens.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Date Range</label>
                    <div class="d-flex gap-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="neo-form-control">
                        <span class="align-self-center">to</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="neo-form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Transaction Type</label>
                    <select name="type" class="neo-form-control">
                        <option value="">All Types</option>
                        <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Purchases</option>
                        <option value="download_cost" {{ request('type') == 'download_cost' ? 'selected' : '' }}>Download Costs</option>
                        <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refunds</option>
                        <option value="bonus" {{ request('type') == 'bonus' ? 'selected' : '' }}>Bonuses</option>
                        <option value="initial" {{ request('type') == 'initial' ? 'selected' : '' }}>Welcome Bonus</option>
                        <option value="admin_adjustment" {{ request('type') == 'admin_adjustment' ? 'selected' : '' }}>Admin Adjustments</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Amount Type</label>
                    <div class="d-flex gap-2">
                        <select name="amount_type" class="neo-form-control">
                            <option value="">Any</option>
                            <option value="positive" {{ request('amount_type') == 'positive' ? 'selected' : '' }}>Credits (+)</option>
                            <option value="negative" {{ request('amount_type') == 'negative' ? 'selected' : '' }}>Debits (-)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="neo-btn">
                            <i class="fas fa-filter me-2"></i> Apply
                        </button>
                    </div>
                </div>
            </form>

            @if(request()->hasAny(['date_from', 'date_to', 'type', 'amount_type']))
                <div class="mt-3">
                    <a href="{{ route('tokens.index') }}" class="neo-btn btn-sm btn-secondary">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                    <span class="text-muted ms-2">
                        Showing filtered results
                        @if(request('date_from') || request('date_to'))
                            | Date: {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') : 'Start' }} - {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') : 'End' }}
                        @endif
                        @if(request('type'))
                            | Type: {{ ucwords(str_replace('_', ' ', request('type'))) }}
                        @endif
                        @if(request('amount_type'))
                            | {{ request('amount_type') == 'positive' ? 'Credits only' : 'Debits only' }}
                        @endif
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Transactions Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
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
        <div class="col-md-3">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #4CAF50, #8BC34A);">
                    <i class="fas fa-plus fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['total_purchased']) }}</h3>
                    <p class="mb-0">Total Purchased</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #FF9800, #FF5722);">
                    <i class="fas fa-minus fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['total_spent']) }}</h3>
                    <p class="mb-0">Total Spent</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="neo-stat-card">
                <div class="icon-box" style="background: linear-gradient(45deg, #9C27B0, #673AB7);">
                    <i class="fas fa-coins fa-2x text-white"></i>
                </div>
                <div class="stat-content">
                    <h3 class="mb-0">{{ number_format($stats['net_balance']) }}</h3>
                    <p class="mb-0">Current Balance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="neo-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-history me-2"></i>Transaction History
            </h5>
            <span class="badge bg-primary" style="border: 2px solid #212529;">
                {{ $transactions->total() }} {{ Str::plural('transaction', $transactions->total()) }}
            </span>
        </div>
        <div class="card-body p-0">
            @if($transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="border-bottom: 2px solid #212529; padding: 12px;">
                                    <i class="fas fa-calendar me-1"></i>Date & Time
                                </th>
                                <th style="border-bottom: 2px solid #212529; padding: 12px;">
                                    <i class="fas fa-tag me-1"></i>Type
                                </th>
                                <th style="border-bottom: 2px solid #212529; padding: 12px;">
                                    <i class="fas fa-comment me-1"></i>Description
                                </th>
                                <th style="border-bottom: 2px solid #212529; padding: 12px;">
                                    <i class="fas fa-coins me-1"></i>Amount
                                </th>
                                <th style="border-bottom: 2px solid #212529; padding: 12px;">
                                    <i class="fas fa-wallet me-1"></i>Balance After
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="transaction-row" style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 12px; vertical-align: middle;">
                                        <div class="fw-bold">{{ $transaction->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted small">{{ $transaction->created_at->format('H:i:s') }}</div>
                                        <div class="text-muted small">{{ $transaction->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td style="padding: 12px; vertical-align: middle;">
                                        @php
                                            $typeConfig = [
                                                'purchase' => ['label' => 'Purchase', 'class' => 'bg-success', 'icon' => 'fas fa-shopping-cart'],
                                                'download_cost' => ['label' => 'Download', 'class' => 'bg-warning', 'icon' => 'fas fa-download'],
                                                'refund' => ['label' => 'Refund', 'class' => 'bg-info', 'icon' => 'fas fa-undo'],
                                                'bonus' => ['label' => 'Bonus', 'class' => 'bg-primary', 'icon' => 'fas fa-gift'],
                                                'initial' => ['label' => 'Welcome', 'class' => 'bg-secondary', 'icon' => 'fas fa-star'],
                                                'admin_adjustment' => ['label' => 'Adjustment', 'class' => 'bg-dark', 'icon' => 'fas fa-cog'],
                                            ];
                                            $config = $typeConfig[$transaction->type] ?? ['label' => ucfirst($transaction->type), 'class' => 'bg-dark', 'icon' => 'fas fa-question'];
                                        @endphp
                                        <span class="badge {{ $config['class'] }}" style="border: 2px solid #212529; padding: 6px 12px;">
                                            <i class="{{ $config['icon'] }} me-1"></i>{{ $config['label'] }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px; vertical-align: middle;">
                                        <div class="text-truncate" style="max-width: 300px;" title="{{ $transaction->description ?: 'No description provided' }}">
                                            {{ $transaction->description ?: 'No description provided' }}
                                        </div>
                                        @if($transaction->admin_id && $transaction->admin)
                                            <div class="text-muted small mt-1">
                                                <i class="fas fa-user-shield me-1"></i>by {{ $transaction->admin->name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; vertical-align: middle;">
                                        <div class="token-amount {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }} fw-bold fs-5">
                                            {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $transaction->amount > 0 ? 'Credit' : 'Debit' }}
                                        </div>
                                    </td>
                                    <td style="padding: 12px; vertical-align: middle;">
                                        <div class="fw-bold">{{ number_format($transaction->balance_after ?? 0) }}</div>
                                        <div class="text-muted small">tokens</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center align-items-center p-3">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt text-muted fa-3x mb-3"></i>
                    <h5 class="text-muted">No Transactions Found</h5>
                    <p class="text-muted">
                        @if(request()->hasAny(['type', 'date_from', 'date_to', 'amount_type']))
                            No transactions match your filter criteria. Try adjusting your filters or clearing them.
                        @else
                            You don't have any token transactions yet. Start by purchasing tokens or using our services.
                        @endif
                    </p>

                    <div class="mt-4">
                        @if(request()->hasAny(['type', 'date_from', 'date_to', 'amount_type']))
                            <a href="{{ route('tokens.index') }}" class="neo-btn me-2">
                                <i class="fas fa-times me-2"></i> Clear Filters
                            </a>
                        @endif
                        <a href="{{ route('tokens.purchase') }}" class="neo-btn">
                            <i class="fas fa-shopping-cart me-2"></i> Buy Tokens
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Transaction Summary Card -->
    @if($transactions->count() > 0)
    <div class="neo-card mt-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-chart-pie me-2"></i>Transaction Summary
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">Transaction Breakdown</h6>
                    @php
                        $typeBreakdown = $transactions->groupBy('type')->map(function($group) {
                            return [
                                'count' => $group->count(),
                                'total' => $group->sum('amount')
                            ];
                        });
                    @endphp
                    @foreach($typeBreakdown as $type => $data)
                        @php
                            $config = [
                                'purchase' => ['label' => 'Purchases', 'icon' => 'fas fa-shopping-cart', 'color' => 'text-success'],
                                'download_cost' => ['label' => 'Downloads', 'icon' => 'fas fa-download', 'color' => 'text-warning'],
                                'refund' => ['label' => 'Refunds', 'icon' => 'fas fa-undo', 'color' => 'text-info'],
                                'bonus' => ['label' => 'Bonuses', 'icon' => 'fas fa-gift', 'color' => 'text-primary'],
                                'initial' => ['label' => 'Welcome Bonus', 'icon' => 'fas fa-star', 'color' => 'text-secondary'],
                                'admin_adjustment' => ['label' => 'Adjustments', 'icon' => 'fas fa-cog', 'color' => 'text-dark'],
                            ];
                            $typeConfig = $config[$type] ?? ['label' => ucfirst($type), 'icon' => 'fas fa-question', 'color' => 'text-dark'];
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="{{ $typeConfig['color'] }}">
                                <i class="{{ $typeConfig['icon'] }} me-2"></i>{{ $typeConfig['label'] }}
                                <span class="text-muted">({{ $data['count'] }})</span>
                            </div>
                            <div class="fw-bold {{ $data['total'] > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $data['total'] > 0 ? '+' : '' }}{{ number_format($data['total']) }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('tokens.balance') }}" class="neo-btn btn-sm">
                            <i class="fas fa-wallet me-2"></i>View Token Balance
                        </a>
                        <a href="{{ route('tokens.purchase') }}" class="neo-btn btn-sm">
                            <i class="fas fa-plus-circle me-2"></i>Buy More Tokens
                        </a>
                        <a href="{{ route('tokens.requests') }}" class="neo-btn btn-sm btn-secondary">
                            <i class="fas fa-clock me-2"></i>Purchase Requests
                        </a>
                        <a href="{{ route('downloads.create') }}" class="neo-btn btn-sm btn-secondary">
                            <i class="fas fa-download me-2"></i>Start Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
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
        font-weight: 600;
    }

    .neo-form-control {
        border: 2px solid #212529;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
        width: 100%;
        background: #fff;
    }

    .neo-form-control:focus {
        border-color: #ff4b2b;
        box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2);
        outline: none;
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
        color: #212529;
        text-decoration: none;
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
        transition: transform 0.2s ease;
        height: 100%;
    }

    .neo-stat-card:hover {
        transform: translateY(-2px);
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
        color: #212529;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .table td {
        vertical-align: middle;
    }

    .transaction-row:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-weight: 600;
        font-size: 0.75rem;
    }

    .alert {
        border: 2px solid;
        border-radius: 8px;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .token-amount {
        font-family: 'Courier New', monospace;
    }

    @media (max-width: 768px) {
        .neo-stat-card {
            flex-direction: column;
            text-align: center;
        }

        .neo-stat-card .icon-box {
            margin-right: 0;
            margin-bottom: 1rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .d-flex.gap-2 {
            flex-direction: column;
        }

        .d-flex.gap-2 .neo-btn {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add smooth scrolling to pagination
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                setTimeout(function() {
                    document.querySelector('.neo-card').scrollIntoView({
                        behavior: 'smooth'
                    });
                }, 100);
            });
        });
    });
</script>
@endpush
