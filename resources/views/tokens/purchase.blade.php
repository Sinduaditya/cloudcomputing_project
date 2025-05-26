<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\tokens\purchase.blade.php -->
@extends('layouts.app')

@section('title', 'Purchase Tokens')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Purchase Tokens</h1>
            <div>
                <a href="{{ route('tokens.balance') }}" class="neo-btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Balance
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Packages -->
                <div class="neo-card mb-4">
                    <div class="card-header" style="padding: 12px;">
                        <h5 class="mb-0">Select Token Package</h5>
                    </div>
                    <div class="card-body" style="padding: 24px;">
                        <div class="row g-4">
                            @foreach ($packages as $package)
                                <div class="col-md-4 col-sm-6">
                                    <div class="token-package-card position-relative"
                                        style="
                                    border: 3px solid #212529;
                                    border-radius: 12px;
                                    padding: 20px;
                                    text-align: center;
                                    cursor: pointer;
                                    transition: all 0.2s;
                                    height: 100%;
                                    {{ $selectedPackage == $package['id'] ? 'box-shadow: 8px 8px 0 var(--primary); transform: translate(-4px, -4px);' : 'box-shadow: 8px 8px 0 rgba(0,0,0,0.25);' }}
                                    background-color: {{ $package['best_value'] ? '#fff8e8' : 'white' }};
                                "
                                        data-package-id="{{ $package['id'] }}"
                                        onclick="selectPackage({{ $package['id'] }})">
                                        @if ($package['best_value'])
                                            <div class="position-absolute"
                                                style="
                                            top: -15px;
                                            right: -15px;
                                            background: linear-gradient(45deg, #ff4b2b, #ff9a55);
                                            color: white;
                                            padding: 3px 12px;
                                            border: 2px solid #212529;
                                            border-radius: 20px;
                                            font-size: 12px;
                                            font-weight: bold;
                                            transform: rotate(5deg);
                                        ">
                                                BEST VALUE</div>
                                        @endif

                                        <div class="token-amount mb-3">
                                            <h2 class="mb-0">{{ $package['amount'] }}</h2>
                                            <p class="text-muted">tokens</p>
                                        </div>

                                        <div class="price-container">
                                            @if ($package['discount'] > 0)
                                                <p class="text-decoration-line-through text-muted mb-1">
                                                    Rp {{ number_format($package['original_price'], 0, ',', '.') }}
                                                </p>
                                            @endif
                                            <h3 class="fw-bold mb-1">Rp {{ number_format($package['price'], 0, ',', '.') }}
                                            </h3>
                                            @if ($package['discount'] > 0)
                                                <span class="badge bg-success"
                                                    style="border: 2px solid #212529;">{{ $package['discount'] }}%
                                                    OFF</span>
                                            @endif
                                        </div>

                                        <div class="token-price-info mt-3 mb-2">
                                            <span class="small text-muted">{{ $package['price_per_token'] }} per
                                                token</span>
                                        </div>

                                        <div class="mt-3">
                                            <input type="radio" name="package_id" value="{{ $package['id'] }}"
                                                id="package_{{ $package['id'] }}"
                                                style="transform: scale(1.5); margin-right: 8px; accent-color: var(--primary);"
                                                {{ $selectedPackage == $package['id'] ? 'checked' : '' }}>
                                            <label for="package_{{ $package['id'] }}"
                                                class="form-check-label">Select</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="neo-card mb-4">
                    <div class="card-header" style="padding: 12px;">
                        <h5 class="mb-0">Payment Details</h5>
                    </div>
                    <div class="card-body" style="padding: 24px;">
                        <form action="{{ route('tokens.process-purchase') }}" method="POST" id="paymentForm">
                            @csrf
                            <input type="hidden" name="package_id" id="selected_package_id"
                                value="{{ $selectedPackage }}">

                            <div class="mb-4">
                                <label class="form-label fw-bold">Payment Method</label>
                                <div class="payment-methods">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="payment-method-card"
                                                style="
                                            border: 3px solid #212529;
                                            border-radius: 8px;
                                            padding: 15px;
                                            text-align: center;
                                            cursor: pointer;
                                            {{ $paymentMethod == 'bank_transfer' ? 'background-color: #f8f9fa; box-shadow: 5px 5px 0 var(--primary);' : '' }}
                                        "
                                                onclick="selectPaymentMethod('bank_transfer')">
                                                <div class="payment-icon mb-2">
                                                    <i class="fas fa-university fa-2x"></i>
                                                </div>
                                                <div class="payment-name">
                                                    <strong>Bank Transfer</strong>
                                                </div>
                                                <input type="radio" name="payment_method" value="bank_transfer"
                                                    id="payment_bank_transfer"
                                                    {{ $paymentMethod == 'bank_transfer' ? 'checked' : '' }}
                                                    style="display:none;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="payment-method-card"
                                                style="
                                            border: 3px solid #212529;
                                            border-radius: 8px;
                                            padding: 15px;
                                            text-align: center;
                                            cursor: pointer;
                                            {{ $paymentMethod == 'credit_card' ? 'background-color: #f8f9fa; box-shadow: 5px 5px 0 var(--primary);' : '' }}
                                        "
                                                onclick="selectPaymentMethod('credit_card')">
                                                <div class="payment-icon mb-2">
                                                    <i class="fas fa-credit-card fa-2x"></i>
                                                </div>
                                                <div class="payment-name">
                                                    <strong>Credit Card</strong>
                                                </div>
                                                <input type="radio" name="payment_method" value="credit_card"
                                                    id="payment_credit_card"
                                                    {{ $paymentMethod == 'credit_card' ? 'checked' : '' }}
                                                    style="display:none;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="payment-method-card"
                                                style="
                                            border: 3px solid #212529;
                                            border-radius: 8px;
                                            padding: 15px;
                                            text-align: center;
                                            cursor: pointer;
                                            {{ $paymentMethod == 'e_wallet' ? 'background-color: #f8f9fa; box-shadow: 5px 5px 0 var(--primary);' : '' }}
                                        "
                                                onclick="selectPaymentMethod('e_wallet')">
                                                <div class="payment-icon mb-2">
                                                    <i class="fas fa-wallet fa-2x"></i>
                                                </div>
                                                <div class="payment-name">
                                                    <strong>E-Wallet</strong>
                                                </div>
                                                <input type="radio" name="payment_method" value="e_wallet"
                                                    id="payment_e_wallet"
                                                    {{ $paymentMethod == 'e_wallet' ? 'checked' : '' }}
                                                    style="display:none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method Specific Forms -->
                            <div id="bank_transfer_form"
                                class="payment-details-form {{ $paymentMethod == 'bank_transfer' ? '' : 'd-none' }}">
                                <div class="alert alert-info"
                                    style="border: 3px solid #212529; border-radius: 8px; box-shadow: 5px 5px 0 rgba(0,0,0,0.2);">
                                    <i class="fas fa-info-circle me-2"></i> After submitting, you will receive bank transfer
                                    details.
                                </div>

                                <x-form-select name="bank_name" label="Select Bank" :options="[
                                    'bca' => 'BCA',
                                    'mandiri' => 'Bank Mandiri',
                                    'bni' => 'BNI',
                                    'bri' => 'BRI',
                                ]"
                                    placeholder="Select bank" />
                            </div>

                            <div id="credit_card_form"
                                class="payment-details-form {{ $paymentMethod == 'credit_card' ? '' : 'd-none' }}">
                                <x-form-input name="card_number" label="Card Number" placeholder="1234 5678 9012 3456" />

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-form-input name="expiry_date" label="Expiry Date" placeholder="MM/YY" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form-input name="cvv" label="CVV" placeholder="123" type="password" />
                                    </div>
                                </div>

                                <x-form-input name="card_holder" label="Cardholder Name"
                                    placeholder="Enter name on card" />
                            </div>

                            <div id="e_wallet_form"
                                class="payment-details-form {{ $paymentMethod == 'e_wallet' ? '' : 'd-none' }}">
                                <x-form-select name="wallet_provider" label="E-Wallet Provider" :options="[
                                    'gopay' => 'GoPay',
                                    'ovo' => 'OVO',
                                    'dana' => 'DANA',
                                    'linkaja' => 'LinkAja',
                                ]"
                                    placeholder="Select provider" />

                                <x-form-input name="phone_number" label="Phone Number" placeholder="e.g., 08123456789" />
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Package Selected:</span>
                                    <span class="fw-bold"
                                        id="selected_package_display">{{ $selectedPackageAmount ?? '0' }} Tokens</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Price:</span>
                                    <span class="fw-bold" id="selected_price_display">Rp
                                        {{ number_format($selectedPackagePrice ?? 0, 0, ',', '.') }}</span>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="neo-btn btn-lg">
                                        <i class="fas fa-shopping-cart me-2"></i> Submit Purchase Request
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Current Balance -->
                <div class="neo-card mb-4">
                    <div class="card-header" style="padding: 12px;">
                        <h5 class="mb-0">Current Balance</h5>
                    </div>
                    <div class="card-body text-center" style="padding: 24px;">
                        <div class="token-balance-circle mb-3"
                            style="
                        width: 120px;
                        height: 120px;
                        border-radius: 50%;
                        border: 8px solid #ff4b2b;
                        margin: 0 auto;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: linear-gradient(45deg, var(--primary-gradient-start), var(--primary-gradient-end));
                        box-shadow: 6px 6px 0 rgba(0,0,0,0.2);
                    ">
                            <div class="text-white">
                                <div class="h3 mb-0 fw-bold">{{ $currentBalance }}</div>
                                <div>Tokens</div>
                            </div>
                        </div>
                        <div class="after-purchase mt-3">
                            <p class="text-muted mb-2">After purchase:</p>
                            <h3 class="fw-bold" id="balance_after_purchase">
                                {{ $currentBalance + ($selectedPackageAmount ?? 0) }} Tokens</h3>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="neo-card">
                    <div class="card-header" style="padding: 12px;">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Information</h5>
                    </div>
                    <div class="card-body" style="padding: 24px;">
                        <div class="mb-4">
                            <h6 class="fw-bold">Instant Delivery</h6>
                            <p>Tokens are added to your account immediately after successful payment.</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold">Secure Payment</h6>
                            <p>All payments are encrypted and processed securely.</p>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold">Need Help?</h6>
                            <p>If you experience any issues with token purchase, please contact our support team.</p>
                        </div>

                        <div class="text-center mt-4">
                            <a href="#" class="text-decoration-none fw-bold">View Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function selectPackage(packageId) {
        // Reset all package cards
        document.querySelectorAll('.token-package-card').forEach(card => {
            card.style.boxShadow = '8px 8px 0 rgba(0,0,0,0.25)';
            card.style.transform = 'none';
        });

        // Highlight selected card
        const selectedCard = document.querySelector(`.token-package-card[data-package-id="${packageId}"]`);
        if (selectedCard) {
            selectedCard.style.boxShadow = '8px 8px 0 var(--primary)';
            selectedCard.style.transform = 'translate(-4px, -4px)';

            // Select the radio button
            const radioButton = document.getElementById(`package_${packageId}`);
            if (radioButton) {
                radioButton.checked = true;
            }

            // Update hidden input
            document.getElementById('selected_package_id').value = packageId;

            // Update the display text for package and price
            const packages = @json($packages);
            const selectedPackage = packages.find(p => p.id === packageId);

            if (selectedPackage) {
                document.getElementById('selected_package_display').innerText = `${selectedPackage.amount} Tokens`;
                document.getElementById('selected_price_display').innerText = `Rp ${selectedPackage.price.toLocaleString('id-ID')}`;

                // Update balance after purchase
                const currentBalance = {{ $currentBalance }};
                const balanceAfterElement = document.getElementById('balance_after_purchase');
                if (balanceAfterElement) {
                    balanceAfterElement.innerText = `${currentBalance + selectedPackage.amount} Tokens`;
                }
            }
        }
    }

    function selectPaymentMethod(method) {
        // Hide all payment detail forms
        document.querySelectorAll('.payment-details-form').forEach(form => {
            form.classList.add('d-none');
        });

        // Show the selected payment form
        const targetForm = document.getElementById(`${method}_form`);
        if (targetForm) {
            targetForm.classList.remove('d-none');
        }

        // Reset all payment method cards
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.style.backgroundColor = 'white';
            card.style.boxShadow = 'none';
        });

        // Highlight the selected payment method
        const selectedMethodCard = document.querySelector(`[onclick="selectPaymentMethod('${method}')"]`);
        if (selectedMethodCard) {
            selectedMethodCard.style.backgroundColor = '#f8f9fa';
            selectedMethodCard.style.boxShadow = '5px 5px 0 var(--primary)';
        }

        // Select the radio button
        const radioButton = document.getElementById(`payment_${method}`);
        if (radioButton) {
            radioButton.checked = true;
        }
    }

    // Initialize with default selections
    document.addEventListener('DOMContentLoaded', function() {
        const initialPackage = '{{ $selectedPackage }}';
        if (initialPackage) {
            selectPackage(initialPackage);
        }

        const initialPaymentMethod = '{{ $paymentMethod }}';
        if (initialPaymentMethod) {
            selectPaymentMethod(initialPaymentMethod);
        }

        // Add CSS variable for primary color
        document.documentElement.style.setProperty('--primary', '#ff4b2b');
    });
</script>
@endpush
