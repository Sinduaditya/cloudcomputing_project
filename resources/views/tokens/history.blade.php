<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\tokens\history.blade.php -->
@extends('layouts.app')

@section('title', 'Token History')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Token Transaction History</h1>
        <div>
            <a href="{{ route('tokens.balance') }}" class="btn btn-secondary neo-btn">
                <i class="fas fa-coins me-2"></i> Token Balance
            </a>
            <a href="{{ route('tokens.purchase') }}" class="neo-btn ms-2">
                <i class="fas fa-plus-circle me-2"></i> Buy Tokens
            </a>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="neo-card mb-4">
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
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustments</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Amount</label>
                    <div class="d-flex gap-2">
                        <select name="amount_type" class="neo-form-control">
                            <option value="">Any</option>
                            <option value="positive" {{ request('amount_type') == 'positive' ? 'selected' : '' }}>Credits</option>
                            <option value="negative" {{ request('amount_type') == 'negative' ? 'selected' : '' }}>Debits</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="neo-btn">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <x-stats-card
                value="{{ $stats['total_transactions'] }}"
                label="Total Transactions"
                icon="fas fa-exchange-alt"
                color="primary"
            />
        </div>
        <div class="col-md-3">
            <x-stats-card
                value="{{ $stats['total_purchased'] }}"
                label="Total Purchased"
                icon="fas fa-shopping-cart"
                color="success"
            />
        </div>
        <div class="col-md-3">
            <x-stats-card
                value="{{ $stats['total_spent'] }}"
                label="Total Spent"
                icon="fas fa-download"
                color="warning"
            />
        </div>
        <div class="col-md-3">
            <x-stats-card
                value="{{ $stats['net_balance'] }}"
                label="Current Balance"
                icon="fas fa-coins"
                color="info"
                link="{{ route('tokens.balance') }}"
            />
        </div>
    </div>

    <!-- Transactions List -->
    <div class="neo-card">
        <div class="card-header">
            <h5 class="mb-0">Transaction History</h5>
        </div>
        <div class="card-body p-0">
            @if(count($transactions) > 0)
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Balance After</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($transaction->type == 'purchase')
                                            <span class="badge bg-success" style="border: 2px solid #212529;">Purchase</span>
                                        @elseif($transaction->type == 'download_cost')
                                            <span class="badge bg-warning" style="border: 2px solid #212529;">Download</span>
                                        @elseif($transaction->type == 'refund')
                                            <span class="badge bg-info" style="border: 2px solid #212529;">Refund</span>
                                        @elseif($transaction->type == 'bonus')
                                            <span class="badge bg-primary" style="border: 2px solid #212529;">Bonus</span>
                                        @elseif($transaction->type == 'adjustment')
                                            <span class="badge bg-secondary" style="border: 2px solid #212529;">Adjustment</span>
                                        @else
                                            <span class="badge bg-dark" style="border: 2px solid #212529;">{{ ucfirst($transaction->type) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-truncate" style="max-width: 300px;">
                                        {{ $transaction->description ?? 'No description' }}
                                    </td>
                                    <td class="{{ $transaction->amount > 0 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                        {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}
                                    </td>
                                    <td>{{ $transaction->balance_after }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4 pb-2">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt text-muted fa-3x mb-3"></i>
                    <h5>No Transactions Found</h5>
                    <p class="text-muted">
                        @if(request()->has('type') || request()->has('date_from') || request()->has('date_to') || request()->has('amount_type'))
                            No transactions match your filter criteria
                        @else
                            You don't have any token transactions yet
                        @endif
                    </p>

                    @if(request()->has('type') || request()->has('date_from') || request()->has('date_to') || request()->has('amount_type'))
                        <a href="{{ route('tokens.index') }}" class="neo-btn mt-2">
                            <i class="fas fa-times me-2"></i> Clear Filters
                        </a>
                    @else
                        <a href="{{ route('tokens.purchase') }}" class="neo-btn mt-2">
                            <i class="fas fa-shopping-cart me-2"></i> Buy Tokens
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
