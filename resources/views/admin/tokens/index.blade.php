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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                    <h5 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>Adjust User Tokens
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tokens.adjust') }}" method="POST" class="row g-3" id="tokenAdjustForm">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>Select User
                            </label>
                            <select name="user_id" class="neo-form-control" required>
                                <option value="">Choose a user...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->token_balance }} tokens)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Choose the user whose token balance you want to adjust</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-coins me-1"></i>Token Amount
                            </label>
                            <input type="number" name="amount" class="neo-form-control" value="{{ old('amount') }}" required placeholder="e.g., 50 or -20">
                            <small class="text-muted">Use positive value to add tokens, negative to deduct</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i>Transaction Type
                            </label>
                            <select name="type" class="neo-form-control" required>
                                <option value="">Select type...</option>
                                <option value="admin_adjustment" {{ old('type') == 'admin_adjustment' ? 'selected' : '' }}>Admin Adjustment</option>
                                <option value="bonus" {{ old('type') == 'bonus' ? 'selected' : '' }}>Bonus</option>
                                <option value="refund" {{ old('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                                <option value="penalty" {{ old('type') == 'penalty' ? 'selected' : '' }}>Penalty</option>
                                <option value="reward" {{ old('type') == 'reward' ? 'selected' : '' }}>Reward</option>
                                <option value="correction" {{ old('type') == 'correction' ? 'selected' : '' }}>Balance Correction</option>
                            </select>
                            <small class="text-muted">Categorize this token adjustment</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-comment me-1"></i>Description
                            </label>
                            <input type="text" name="description" class="neo-form-control" value="{{ old('description') }}" placeholder="Reason for adjustment" maxlength="255">
                            <small class="text-muted">Optional note about this adjustment</small>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="neo-btn">
                                <i class="fas fa-check me-2"></i>Process Token Adjustment
                            </button>
                            <button type="reset" class="neo-btn btn-secondary ms-2">
                                <i class="fas fa-undo me-2"></i>Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="neo-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Recent Token Transactions
                    </h5>
                    <a href="{{ route('admin.tokens.transactions') }}" class="neo-btn btn-sm btn-secondary">
                        <i class="fas fa-list me-1"></i>View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-user me-1"></i>User</th>
                                        <th><i class="fas fa-tag me-1"></i>Type</th>
                                        <th><i class="fas fa-coins me-1"></i>Amount</th>
                                        <th><i class="fas fa-comment me-1"></i>Description</th>
                                        <th><i class="fas fa-clock me-1"></i>Date & Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions->take(10) as $transaction)
                                        <tr>
                                            <td>
                                                @if($transaction->user)
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($transaction->user->name) }}&size=32&background=ff4b2b&color=fff"
                                                            class="rounded-circle me-2" alt="{{ $transaction->user->name }}"
                                                            style="border: 2px solid #212529; width: 32px; height: 32px;">
                                                        <div>
                                                            <div class="fw-bold">{{ $transaction->user->name }}</div>
                                                            <small class="text-muted">{{ $transaction->user->email }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-cog me-1"></i>System
                                                    </span>
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
                                                    @elseif(str_contains($transaction->type, 'penalty')) bg-danger
                                                    @else bg-light text-dark @endif"
                                                    style="border: 1px solid #212529;">
                                                    {{ ucwords(str_replace('_', ' ', $transaction->type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="{{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                                    {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                                      title="{{ $transaction->description ?: 'No description' }}">
                                                    {{ $transaction->description ?: 'No description' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span data-bs-toggle="tooltip" title="{{ $transaction->created_at->format('F d, Y \a\t g:i A') }}">
                                                    {{ $transaction->created_at->format('M d, Y H:i') }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No token transactions found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Links Card -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Token Management
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tokens.transactions') }}" class="neo-btn">
                            <i class="fas fa-exchange-alt me-2"></i>View All Transactions
                        </a>
                        <a href="{{ route('admin.tokens.pricing') }}" class="neo-btn">
                            <i class="fas fa-tags me-2"></i>Token Pricing Settings
                        </a>
                        {{-- <a href="{{ route('admin.tokens.statistics') }}" class="neo-btn">
                            <i class="fas fa-chart-bar me-2"></i>Token Statistics
                        </a> --}}
                    </div>
                </div>
            </div>

            <!-- Current Settings -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sliders-h me-2"></i>Current Token Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-dollar-sign me-1"></i>Token Price:</span>
                            <span class="fw-bold">${{ number_format(config('app.token_price', 0.10), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-weight me-1"></i>MB Per Token:</span>
                            <span class="fw-bold">{{ config('download.mb_per_token', 10) }} MB</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-download me-1"></i>Min Tokens/Download:</span>
                            <span class="fw-bold">{{ config('download.min_tokens', 1) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-gift me-1"></i>Free Tokens (New Users):</span>
                            <span class="fw-bold">{{ config('app.default_token_balance', 10) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Users by Balance -->
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-crown me-2"></i>Top Token Balances
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($users->sortByDesc('token_balance')->take(5) as $index => $user)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : 'info') }} me-2">
                                                #{{ $index + 1 }}
                                            </span>
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=32&background=ff4b2b&color=fff"
                                                class="rounded-circle me-2" alt="{{ $user->name }}"
                                                style="border: 2px solid #212529; width: 32px; height: 32px;">
                                            <div>
                                                <div class="fw-bold">{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                        <span class="badge bg-primary" style="border: 2px solid #121212; font-size: 14px;">
                                            <i class="fas fa-coins me-1"></i>{{ number_format($user->token_balance) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No users found.</p>
                        </div>
                    @endif
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
        background: #fff;
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
        font-weight: 600;
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
        border-bottom: 2px solid #212529;
        background-color: #f8f9fa;
        font-weight: 600;
        padding: 12px;
    }

    .table td {
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
        padding: 12px;
    }

    .badge {
        padding: 0.35em 0.65em;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .bg-purple {
        background-color: #6f42c1 !important;
    }

    .list-group-item {
        border-left: 0;
        border-right: 0;
        border-top: 0;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem;
    }

    .list-group-item:last-child {
        border-bottom: 0;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .alert {
        border: 2px solid;
        border-radius: 8px;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .form-label {
        margin-bottom: 0.5rem;
        color: #212529;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
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
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Form validation
        const form = document.getElementById('tokenAdjustForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const userId = this.querySelector('[name="user_id"]').value;
                const amount = this.querySelector('[name="amount"]').value;
                const type = this.querySelector('[name="type"]').value;

                if (!userId) {
                    e.preventDefault();
                    alert('Please select a user');
                    this.querySelector('[name="user_id"]').focus();
                    return;
                }

                if (!amount || amount === '0') {
                    e.preventDefault();
                    alert('Please enter a non-zero amount');
                    this.querySelector('[name="amount"]').focus();
                    return;
                }

                if (!type) {
                    e.preventDefault();
                    alert('Please select a transaction type');
                    this.querySelector('[name="type"]').focus();
                    return;
                }

                // Confirm large adjustments
                if (Math.abs(amount) > 100) {
                    const action = amount > 0 ? 'add' : 'deduct';
                    if (!confirm(`You are about to ${action} ${Math.abs(amount)} tokens. Are you sure?`)) {
                        e.preventDefault();
                    }
                }

                // Confirm negative adjustments
                if (amount < 0 && type !== 'penalty') {
                    if (!confirm(`You are deducting ${Math.abs(amount)} tokens. This action cannot be undone. Continue?`)) {
                        e.preventDefault();
                    }
                }
            });

            // Real-time amount validation
            const amountInput = form.querySelector('[name="amount"]');
            if (amountInput) {
                amountInput.addEventListener('input', function() {
                    const value = parseInt(this.value);
                    const warning = document.getElementById('amount-warning');

                    // Remove existing warning
                    if (warning) {
                        warning.remove();
                    }

                    // Add warning for large amounts
                    if (Math.abs(value) > 100) {
                        const warningEl = document.createElement('small');
                        warningEl.id = 'amount-warning';
                        warningEl.className = 'text-warning d-block mt-1';
                        warningEl.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Large amount detected!';
                        this.parentNode.appendChild(warningEl);
                    }
                });
            }
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Add loading state to form submission
        form?.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            }
        });
    });
</script>
@endpush
