<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\tokens\balance.blade.php -->
@extends('layouts.app')

@section('title', 'Token Balance')

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
        <h1 class="mb-0">My Tokens</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('tokens.requests') }}" class="neo-btn btn-secondary">
                <i class="fas fa-clock me-2"></i> Purchase Requests
            </a>
            <a href="{{ route('tokens.purchase') }}" class="neo-btn" style="color: #ffffff;">
                <i class="fas fa-plus-circle me-2"></i> Buy Tokens
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Balance Overview Card -->
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="neo-card h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0" style="padding: 12px;">
                        <i class="fas fa-coins me-2"></i>Balance Overview
                    </h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="text-center mb-4">
                        <!-- Current Balance Display -->
                        <div class="token-balance-circle mb-3" style="
                            width: 150px;
                            height: 150px;
                            border-radius: 50%;
                            border: 10px solid #2B7EC1;
                            margin: 0 auto;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 8px 8px 0 rgba(0,0,0,0.2);
                            background: linear-gradient(45deg, #2B7EC1, #58A7E6);
                        ">
                            <div class="text-white text-center">
                                <div class="h2 mb-0 fw-bold">{{ number_format($tokenBalance) }}</div>
                                <div class="small">Tokens</div>
                            </div>
                        </div>

                        <!-- Token Statistics -->
                        <div class="usage-stats row text-center">
                            <div class="col-4">
                                <h6 class="text-muted small">Total Used</h6>
                                <h5 class="fw-bold text-danger">{{ number_format($stats['used_tokens']) }}</h5>
                            </div>
                            <div class="col-4">
                                <h6 class="text-muted small">Purchased</h6>
                                <h5 class="fw-bold text-success">{{ number_format($stats['purchased_tokens']) }}</h5>
                            </div>
                            <div class="col-4">
                                <h6 class="text-muted small">Bonus</h6>
                                <h5 class="fw-bold text-warning">{{ number_format($stats['bonus_tokens']) }}</h5>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" style="border-top: 2px dashed #212529;">

                    <!-- Detailed Statistics -->
                    <div class="token-stats">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div><i class="fas fa-download me-1 text-info"></i>Download Costs:</div>
                            <div class="fw-bold text-info">{{ number_format($stats['download_costs']) }} tokens</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div><i class="fas fa-undo me-1 text-success"></i>Refunds:</div>
                            <div class="fw-bold text-success">{{ number_format($stats['refunds']) }} tokens</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div><i class="fas fa-calendar-week me-1 text-primary"></i>Last 7 Days:</div>
                            <div class="fw-bold">{{ number_format($stats['last_7_days']) }} tokens</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-chart-line me-1 text-warning"></i>Daily Average:</div>
                            <div class="fw-bold">{{ number_format($stats['monthly_avg']) }} tokens/day</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Token Packages Card -->
        <div class="col-md-6">
            <div class="neo-card mb-4">
                <div class="card-header" style="padding: 12px;">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Token Packages
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($packages as $package)
                            <div class="col-sm-6">
                                <div class="token-package p-3 position-relative" style="
                                    border: 3px solid #212529;
                                    border-radius: 8px;
                                    height: 100%;
                                    background-color: {{ $package['best_value'] ? '#fff8e8' : 'white' }};">
                                    @if($package['best_value'])
                                        <div class="position-absolute" style="
                                            top: -10px;
                                            right: -10px;
                                            background: linear-gradient(45deg, #ff4b2b, #ff9a55);
                                            color: white;
                                            padding: 4px 12px;
                                            border: 2px solid #212529;
                                            border-radius: 20px;
                                            font-size: 11px;
                                            font-weight: bold;
                                            transform: rotate(5deg);
                                            box-shadow: 2px 2px 0 rgba(0,0,0,0.2);
                                        ">BEST VALUE</div>
                                    @endif
                                    <div class="text-center">
                                        <h3 class="mb-1">{{ $package['amount'] }}</h3>
                                        <p class="text-muted mb-3">tokens</p>
                                        <div class="package-price mb-3">
                                            <h5 class="fw-bold">{{ $package['price'] }}</h5>

                                            @if($package['discount'] > 0)
                                                <span class="badge bg-success" style="border: 2px solid #212529;">{{ $package['discount'] }}% OFF</span>
                                            @endif
                                        </div>
                                        <h3 class="mb-1 fw-bold">{{ number_format($package['tokens']) }}</h3>
                                        <p class="text-muted mb-3 small">tokens</p>
                                        <div class="package-price mb-3">
                                            <h5 class="fw-bold mb-1">Rp {{ number_format($package['price'], 0, ',', '.') }}</h5>
                                            @if($package['discount'] > 0)
                                                <span class="badge bg-success" style="border: 2px solid #212529; font-size: 10px;">
                                                    {{ $package['discount'] }}% OFF
                                                </span>
                                            @endif
                                            <div class="small text-muted mt-1">{{ $package['description'] }}</div>
                                        </div>       
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Quick Info -->
                    <div class="mt-3 p-3" style="background: #f8f9fa; border: 2px dashed #212529; border-radius: 8px;">
                        <div class="text-center">
                            <i class="fas fa-info-circle text-info me-1"></i>
                            <small class="text-muted">Purchase requests are reviewed by admin within 24 hours</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Card -->
            <div class="neo-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="padding: 12px;">
                        <i class="fas fa-history me-2"></i>Recent Transactions
                    </h5>
                    <a href="{{ route('tokens.index') }}" class="btn btn-sm neo-btn"  style="color: #ffffff;">
                        <i class="fas fa-list me-1"></i>View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentTransactions && count($recentTransactions) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentTransactions as $transaction)
                                <div class="list-group-item d-flex justify-content-between align-items-center" style="border-left:0; border-right:0; padding: 12px 16px;">
                                    <div class="d-flex align-items-center">
                                        <div class="transaction-icon me-3" style="
                                            width: 40px;
                                            height: 40px;
                                            border-radius: 50%;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            border: 2px solid #212529;
                                            {{ $transaction->amount > 0 ? 'background: linear-gradient(45deg, #4CAF50, #8BC34A);' : 'background: linear-gradient(45deg, #f44336, #FF5722);' }}
                                        ">
                                            @if($transaction->type == 'purchase')
                                                <i class="fas fa-plus text-white"></i>
                                            @elseif($transaction->type == 'download_cost')
                                                <i class="fas fa-download text-white"></i>
                                            @elseif($transaction->type == 'bonus' || $transaction->type == 'initial')
                                                <i class="fas fa-gift text-white"></i>
                                            @elseif($transaction->type == 'refund')
                                                <i class="fas fa-undo text-white"></i>
                                            @else
                                                <i class="fas fa-exchange-alt text-white"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold">
                                                @if($transaction->type == 'purchase')
                                                    Token Purchase
                                                @elseif($transaction->type == 'download_cost')
                                                    Download Cost
                                                @elseif($transaction->type == 'bonus')
                                                    Bonus Tokens
                                                @elseif($transaction->type == 'initial')
                                                    Welcome Bonus
                                                @elseif($transaction->type == 'refund')
                                                    Refund
                                                @else
                                                    {{ ucwords(str_replace('_', ' ', $transaction->type)) }}
                                                @endif
                                            </div>
                                            <div class="small text-muted">
                                                {{ $transaction->created_at->format('M d, Y H:i') }}
                                                <span class="mx-1">•</span>
                                                {{ $transaction->created_at->diffForHumans() }}
                                            </div>
                                            @if($transaction->description)
                                                <div class="small text-muted" style="max-width: 200px;">
                                                    {{ Str::limit($transaction->description, 40) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="token-amount fw-bold fs-5 {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }}
                                        </div>
                                        <div class="small text-muted">
                                            Balance: {{ number_format($transaction->balance_after) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history text-muted fa-3x mb-3"></i>
                            <h6 class="text-muted">No transactions yet</h6>
                            <p class="text-muted small">Your token transaction history will appear here</p>
                            <a href="{{ route('tokens.purchase') }}" class="neo-btn btn-sm"  style="color: #ffffff;">
                                <i class="fas fa-plus me-1"></i>Buy Your First Tokens
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- How Tokens Work Section -->
    <div class="neo-card">
        <div class="card-header">
            <h5 class="mb-0" style="padding: 12px;">
                <i class="fas fa-question-circle me-2"></i>How Tokens Work
            </h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h6 class="fw-bold">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Token Usage
                    </h6>
                    <p class="mb-3">Tokens are required to download videos. The number of tokens needed depends on:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Video length (longer videos need more tokens)</li>
                        <li class="mb-2"><i class="fas fa-hd-video text-info me-2"></i>Quality selected (higher quality uses more tokens)</li>
                        <li class="mb-2"><i class="fas fa-globe text-success me-2"></i>Source platform (some platforms require more processing)</li>
                        <li class="mb-2"><i class="fas fa-file-video text-danger me-2"></i>File format and conversion requirements</li>
                    </ul>

                    <h6 class="fw-bold mt-4">
                        <i class="fas fa-gem me-2 text-primary"></i>Premium Features
                    </h6>
                    <p class="mb-3">With tokens, you can:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Download in high quality formats (up to 4K)</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Schedule downloads for later execution</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Process multiple downloads simultaneously</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Access advanced conversion options</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Priority processing and faster downloads</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold">
                        <i class="fas fa-calculator me-2 text-primary"></i>Token Consumption Guide
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border: 2px solid #212529;">
                            <thead style="background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%);">
                                <tr>
                                    <th style="border: 1px solid #212529;">Video Length</th>
                                    <th style="border: 1px solid #212529;">Low Quality</th>
                                    <th style="border: 1px solid #212529;">High Quality</th>
                                    <th style="border: 1px solid #212529;">4K Quality</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border: 1px solid #212529;"><strong>Up to 1 min</strong></td>
                                    <td style="border: 1px solid #212529;">5 tokens</td>
                                    <td style="border: 1px solid #212529;">10 tokens</td>
                                    <td style="border: 1px solid #212529;">20 tokens</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #212529;"><strong>1-5 mins</strong></td>
                                    <td style="border: 1px solid #212529;">10 tokens</td>
                                    <td style="border: 1px solid #212529;">25 tokens</td>
                                    <td style="border: 1px solid #212529;">50 tokens</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #212529;"><strong>5-15 mins</strong></td>
                                    <td style="border: 1px solid #212529;">25 tokens</td>
                                    <td style="border: 1px solid #212529;">50 tokens</td>
                                    <td style="border: 1px solid #212529;">100 tokens</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #212529;"><strong>15+ mins</strong></td>
                                    <td style="border: 1px solid #212529;">50 tokens</td>
                                    <td style="border: 1px solid #212529;">100 tokens</td>
                                    <td style="border: 1px solid #212529;">200 tokens</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info" style="border: 2px solid #212529; box-shadow: 3px 3px 0 rgba(0,0,0,0.1);">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Pro Tip:</strong> Buy packages with discount to save more tokens!
                    </div>
                    <p class="small text-muted mt-2">
                        <i class="fas fa-asterisk me-1"></i>
                        Actual token costs may vary based on specific platform and processing requirements
                    </p>
                </div>
            </div>
        </div>
    </div>
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

    .token-package {
        box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
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

    .alert {
        border: 2px solid;
        border-radius: 8px;
        box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-weight: 600;
        font-size: 0.75rem;
    }

    @media (max-width: 768px) {
        .token-balance-circle {
            width: 120px !important;
            height: 120px !important;
        }

        .token-balance-circle .h2 {
            font-size: 1.5rem !important;
        }

        .usage-stats .h5 {
            font-size: 1rem !important;
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

        // Add click effect to package cards
        const packageCards = document.querySelectorAll('.token-package');
        packageCards.forEach(function(card) {
            card.addEventListener('click', function() {
                const buyButton = this.querySelector('.neo-btn');
                if (buyButton) {
                    buyButton.click();
                }
            });
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
