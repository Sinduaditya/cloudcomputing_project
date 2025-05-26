<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\tokens\balance.blade.php -->
@extends('layouts.app')

@section('title', 'Token Balance')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">My Tokens</h1>
        <div>
            <a href="{{ route('tokens.purchase') }}" class="neo-btn">
                <i class="fas fa-plus-circle me-2"></i> Buy Tokens
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="neo-card h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0" style="padding: 12px;">Balance Overview</h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="text-center mb-4">
                        <div class="token-balance-circle mb-3" style="
                            width: 150px;
                            height: 150px;
                            border-radius: 50%;
                            border: 10px solid #ff4b2b;
                            margin: 0 auto;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 8px 8px 0 rgba(0,0,0,0.2);
                            background: linear-gradient(45deg, var(--primary-gradient-start), var(--primary-gradient-end));
                        ">
                            <div class="text-white">
                                <div class="h2 mb-0 fw-bold">{{ $tokenBalance }}</div>
                                <div>Tokens</div>
                            </div>
                        </div>

                        <div class="usage-stats row text-center">
                            <div class="col">
                                <h6 class="text-muted">Total Used</h6>
                                <h4>{{ $stats['used_tokens'] }}</h4>
                            </div>
                            <div class="col">
                                <h6 class="text-muted">Purchased</h6>
                                <h4>{{ $stats['purchased_tokens'] }}</h4>
                            </div>
                            <div class="col">
                                <h6 class="text-muted">Free</h6>
                                <h4>{{ $stats['bonus_tokens'] }}</h4>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" style="border-top: 2px dashed #212529;">

                    <div class="token-stats">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>Monthly Average Usage:</div>
                            <div class="fw-bold">{{ $stats['monthly_avg'] }} tokens</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>Last 7 Days:</div>
                            <div class="fw-bold">{{ $stats['last_7_days'] }} tokens</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="neo-card mb-4">
                <div class="card-header" style="padding: 12px;">
                    <h5 class="mb-0">Token Packages</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($packages as $package)
                            <div class="col-sm-6">
                                <div class="token-package p-3 position-relative" style="
                                    border: 3px solid #212529;
                                    border-radius: 8px;
                                    transition: all 0.2s;
                                    height: 100%;
                                    background-color: {{ $package['best_value'] ? '#fff8e8' : 'white' }};
                                ">
                                    @if($package['best_value'])
                                        <div class="position-absolute" style="
                                            top: -10px;
                                            right: -10px;
                                            background: linear-gradient(45deg, #ff4b2b, #ff9a55);
                                            color: white;
                                            padding: 2px 10px;
                                            border: 2px solid #212529;
                                            border-radius: 20px;
                                            font-size: 12px;
                                            font-weight: bold;
                                            transform: rotate(5deg);
                                        ">BEST VALUE</div>
                                    @endif
                                    <div class="text-center" style="padding: 12px;">
                                        <h3 class="mb-1">{{ $package['amount'] }}</h3>
                                        <p class="text-muted mb-3">tokens</p>
                                        <div class="package-price mb-3">
                                            <h5 class="fw-bold">Rp {{ number_format($package['price'], 0, ',', '.') }}</h5>
                                            @if($package['discount'] > 0)
                                                <span class="badge bg-success" style="border: 2px solid #212529;">{{ $package['discount'] }}% OFF</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('tokens.purchase', ['package' => $package['id']]) }}" class="neo-btn btn-sm w-100">Buy Now</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="neo-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="padding: 12px;">Recent Transactions</h5>
                    <a href="{{ route('tokens.index') }}" class="btn btn-sm neo-btn">View All</a>
                </div>
                <div class="card-body p-0">
                    @if(count($recentTransactions) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentTransactions as $transaction)
                                <div class="list-group-item d-flex justify-content-between align-items-center" style="border-left:0; border-right:0;">
                                    <div>
                                        <div class="fw-bold">
                                            @if($transaction->type == 'purchase')
                                                <i class="fas fa-plus-circle text-success me-1"></i> Token Purchase
                                            @elseif($transaction->type == 'download_cost')
                                                <i class="fas fa-minus-circle text-danger me-1"></i> Download Cost
                                            @elseif($transaction->type == 'bonus')
                                                <i class="fas fa-gift text-warning me-1"></i> Bonus Tokens
                                            @else
                                                <i class="fas fa-exchange-alt text-info me-1"></i> {{ ucfirst($transaction->type) }}
                                            @endif
                                        </div>
                                        <div class="small text-muted">{{ $transaction->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                    <div class="token-amount fw-bold {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history text-muted fa-3x mb-3"></i>
                            <p class="mb-0">No transactions yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="neo-card">
        <div class="card-header">
            <h5 class="mb-0" style="padding: 12px;">How Tokens Work</h5>
        </div>
        <div class="card-body" style="padding: 24px;">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h6 class="fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Token Usage</h6>
                    <p>Tokens are required to download videos. The number of tokens needed depends on:</p>
                    <ul>
                        <li>Video length (longer videos need more tokens)</li>
                        <li>Quality selected (higher quality uses more tokens)</li>
                        <li>Source platform (some platforms require more processing)</li>
                    </ul>

                    <h6 class="fw-bold mt-4"><i class="fas fa-gem me-2 text-primary"></i>Premium Features</h6>
                    <p>With tokens, you can:</p>
                    <ul>
                        <li>Download in high quality formats</li>
                        <li>Schedule downloads for later</li>
                        <li>Process multiple downloads simultaneously</li>
                        <li>Access advanced conversion options</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold"><i class="fas fa-calculator me-2 text-primary"></i>Token Consumption Guide</h6>
                    <div class="table-responsive">
                        <table class="table" style="border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr>
                                    <th>Video Length</th>
                                    <th>Low Quality</th>
                                    <th>Medium Quality</th>
                                    <th>High Quality</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Up to 1 min</td>
                                    <td>5 tokens</td>
                                    <td>10 tokens</td>
                                    <td>15 tokens</td>
                                </tr>
                                <tr>
                                    <td>1-5 mins</td>
                                    <td>10 tokens</td>
                                    <td>20 tokens</td>
                                    <td>30 tokens</td>
                                </tr>
                                <tr>
                                    <td>5-15 mins</td>
                                    <td>20 tokens</td>
                                    <td>35 tokens</td>
                                    <td>50 tokens</td>
                                </tr>
                                <tr>
                                    <td>15+ mins</td>
                                    <td>35 tokens</td>
                                    <td>55 tokens</td>
                                    <td>75 tokens</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="small text-muted mt-2">* Actual token costs may vary based on specific platform and processing requirements</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
