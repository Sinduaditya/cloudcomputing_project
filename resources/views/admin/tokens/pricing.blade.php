<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\admin\tokens\pricing.blade.php -->
@extends('layouts.admin')

@section('title', 'Token Pricing Settings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Token Pricing Settings</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tokens.index') }}" class="neo-btn btn-secondary">
                <i class="fas fa-coins me-2"></i> Token Dashboard
            </a>
            <a href="{{ route('admin.tokens.transactions') }}" class="neo-btn btn-secondary">
                <i class="fas fa-exchange-alt me-2"></i> Transactions
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main settings form -->
        <div class="col-lg-8">
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Token Pricing Configuration</h5>
                </div>
                <div class="card-body"  style="padding: 24px;">
                    <form action="{{ route('admin.tokens.update-pricing') }}" method="POST">
                        @csrf

                        <div class="mb-4 pb-3" style="border-bottom: 1px dashed #dee2e6;">
                            <h5 class="mb-3">Monetary Value</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Token Price ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border: 2px solid #212529; border-right: none;">$</span>
                                        <input type="number" name="token_price" class="neo-form-control" style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                            min="0" step="0.01" value="{{ config('app.token_price', 0.10) }}" required>
                                    </div>
                                    <small class="text-muted">The price users pay for a single token</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Default Token Balance</label>
                                    <input type="number" name="default_token_balance" class="neo-form-control"
                                        min="0" step="1" value="{{ config('app.default_token_balance', 10) }}" required>
                                    <small class="text-muted">Free tokens given to new users upon registration</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 pb-3" style="border-bottom: 1px dashed #dee2e6;">
                            <h5 class="mb-3">Download Costs</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">MB Per Token</label>
                                    <div class="input-group">
                                        <input type="number" name="mb_per_token" class="neo-form-control"
                                            min="0.01" max="100" step="0.01" value="{{ config('download.mb_per_token', 10) }}" required>
                                        <span class="input-group-text" style="border: 2px solid #212529; border-left: none;">MB</span>
                                    </div>
                                    <small class="text-muted">How many megabytes each token covers</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Minimum Tokens Per Download</label>
                                    <input type="number" name="min_tokens_per_download" class="neo-form-control"
                                        min="1" step="1" value="{{ config('download.min_tokens', 1) }}" required>
                                    <small class="text-muted">Minimum token cost for any download</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3">Package Deals</h5>
                            <p class="text-muted">Configure token package deals with discount pricing</p>

                            <div class="table-responsive mb-3">
                                <table class="table table-bordered" id="packageTable" style="border: 2px solid #212529;">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">#</th>
                                            <th>Package Name</th>
                                            <th>Token Amount</th>
                                            <th>Price ($)</th>
                                            <th>Discount (%)</th>
                                            <th style="width: 100px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(config('tokens.packages', []) as $index => $package)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <input type="text" name="packages[{{ $index }}][name]" class="neo-form-control"
                                                    value="{{ $package['name'] }}" required>
                                            </td>
                                            <td>
                                                <input type="number" name="packages[{{ $index }}][amount]" class="neo-form-control"
                                                    value="{{ $package['amount'] }}" min="1" required>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text" style="border: 2px solid #212529; border-right: none;">$</span>
                                                    <input type="number" name="packages[{{ $index }}][price]" class="neo-form-control" style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                                        value="{{ $package['price'] }}" min="0.01" step="0.01" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" name="packages[{{ $index }}][discount]" class="neo-form-control"
                                                        value="{{ $package['discount'] ?? 0 }}" min="0" max="100" step="1">
                                                    <span class="input-group-text" style="border: 2px solid #212529; border-left: none;">%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-package" data-bs-toggle="tooltip" title="Remove Package">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button type="button" id="addPackageBtn" class="neo-btn btn-secondary">
                                <i class="fas fa-plus me-2"></i> Add Package
                            </button>
                        </div>

                        <button type="submit" class="neo-btn btn-lg" style="color: #ffffff">
                            <i class="fas fa-save me-2"></i> Save Pricing Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Price Calculator -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Token Price Calculator</h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="mb-3">
                        <label class="form-label fw-bold">File Size (MB)</label>
                        <input type="number" id="fileSize" class="neo-form-control" min="0" step="0.1" value="100">
                    </div>

                    <div class="results p-3 mb-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Required Tokens:</span>
                            <span class="fw-bold" id="requiredTokens">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Cost ($):</span>
                            <span class="fw-bold" id="calculatedCost">-</span>
                        </div>
                    </div>

                    <button type="button" id="calculateBtn" class="neo-btn w-100" style="color: #ffffff">
                        <i class="fas fa-calculator me-2"></i> Calculate
                    </button>
                </div>
            </div>

            <!-- Pricing Preview -->
            <div class="neo-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Pricing Preview</h5>
                </div>
                <div class="card-body"  style="padding: 24px;">
                    <p class="text-muted mb-3">This is how pricing will appear to users:</p>

                    <div class="pricing-preview p-3" style="border: 2px solid #212529; border-radius: 8px; background-color: #f8f9fa;">
                        <h5 class="text-center mb-3">Token Packages</h5>
                        <div class="packages-preview" id="packagesPreview">
                            @foreach(config('tokens.packages', []) as $package)
                            <div class="package-item mb-2 p-2" style="border: 2px solid #212529; border-radius: 5px; background: linear-gradient(45deg, #ff9a9e, #fad0c4);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ $package['name'] }}</h6>
                                    <span class="badge bg-dark" style="border: 1px solid #212529;">${{ $package['price'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <span>{{ $package['amount'] }} tokens</span>
                                    @if(isset($package['discount']) && $package['discount'] > 0)
                                    <span class="badge bg-success" style="border: 1px solid #212529;">{{ $package['discount'] }}% OFF</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-3">
                            <div class="small text-muted mb-1">Per-token price:</div>
                            <h5 class="mb-0">${{ config('app.token_price', 0.10) }} / token</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="neo-card">
                <div class="card-header">
                    <h5 class="mb-0">Pricing Guidelines</h5>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="alert alert-info" style="border: 2px solid #212529; border-radius: 8px; box-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                        <i class="fas fa-info-circle me-2"></i> Changing token pricing will affect all future downloads and purchases. Existing transactions and user balances will remain unchanged.
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold">Recommended Settings:</h6>
                        <ul>
                            <li>Token Price: $0.05 - $0.20</li>
                            <li>MB Per Token: 5 - 20</li>
                            <li>Default Balance: 5 - 20 tokens</li>
                            <li>Min Tokens: 1 - 3 tokens</li>
                        </ul>
                    </div>

                    <div>
                        <h6 class="fw-bold">Package Discounts:</h6>
                        <p class="small text-muted">Set up bulk purchase incentives with increasing discounts for larger token packages. This encourages users to purchase more tokens at once.</p>
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
    }

    .neo-card .card-header {
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

    .neo-btn.btn-secondary {
        background: #f8f9fa;
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Token price calculator
        const calculateBtn = document.getElementById('calculateBtn');
        const fileSize = document.getElementById('fileSize');
        const requiredTokens = document.getElementById('requiredTokens');
        const calculatedCost = document.getElementById('calculatedCost');

        calculateBtn.addEventListener('click', function() {
            const fileSizeValue = parseFloat(fileSize.value) || 0;
            const mbPerToken = parseFloat('{{ config("download.mb_per_token", 10) }}');
            const minTokens = parseInt('{{ config("download.min_tokens", 1) }}');
            const tokenPrice = parseFloat('{{ config("app.token_price", 0.10) }}');

            let tokens = Math.ceil(fileSizeValue / mbPerToken);
            tokens = Math.max(tokens, minTokens);

            requiredTokens.textContent = tokens;
            calculatedCost.textContent = '$' + (tokens * tokenPrice).toFixed(2);
        });

        // Package management
        const addPackageBtn = document.getElementById('addPackageBtn');
        const packageTable = document.getElementById('packageTable').querySelector('tbody');
        const packagesPreview = document.getElementById('packagesPreview');

        addPackageBtn.addEventListener('click', function() {
            const rowCount = packageTable.rows.length;
            const newRow = packageTable.insertRow();

            newRow.innerHTML = `
                <td>${rowCount + 1}</td>
                <td>
                    <input type="text" name="packages[${rowCount}][name]" class="neo-form-control"
                        value="Package ${rowCount + 1}" required>
                </td>
                <td>
                    <input type="number" name="packages[${rowCount}][amount]" class="neo-form-control"
                        value="100" min="1" required>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text" style="border: 2px solid #212529; border-right: none;">$</span>
                        <input type="number" name="packages[${rowCount}][price]" class="neo-form-control" style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                            value="9.99" min="0.01" step="0.01" required>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" name="packages[${rowCount}][discount]" class="neo-form-control"
                            value="0" min="0" max="100" step="1">
                        <span class="input-group-text" style="border: 2px solid #212529; border-left: none;">%</span>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-package" data-bs-toggle="tooltip" title="Remove Package">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            // Add event listener to the new remove button
            newRow.querySelector('.remove-package').addEventListener('click', function() {
                packageTable.removeChild(newRow);
                updateRowNumbers();
                updatePackagePreview();
            });

            // Initialize tooltip for the new button
            var newTooltip = new bootstrap.Tooltip(newRow.querySelector('[data-bs-toggle="tooltip"]'));

            updatePackagePreview();
        });

        // Add event listeners to existing remove buttons
        document.querySelectorAll('.remove-package').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                packageTable.removeChild(row);
                updateRowNumbers();
                updatePackagePreview();
            });
        });

        // Update row numbers
        function updateRowNumbers() {
            const rows = packageTable.rows;
            for (let i = 0; i < rows.length; i++) {
                rows[i].cells[0].textContent = i + 1;

                // Update input names with new indices
                const inputs = rows[i].querySelectorAll('input');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    const newName = name.replace(/packages\[\d+\]/, `packages[${i}]`);
                    input.setAttribute('name', newName);
                });
            }
        }

        // Update package preview
        function updatePackagePreview() {
            packagesPreview.innerHTML = '';

            const rows = packageTable.rows;
            for (let i = 0; i < rows.length; i++) {
                const nameInput = rows[i].querySelector('input[name$="[name]"]');
                const amountInput = rows[i].querySelector('input[name$="[amount]"]');
                const priceInput = rows[i].querySelector('input[name$="[price]"]');
                const discountInput = rows[i].querySelector('input[name$="[discount]"]');

                if (nameInput && amountInput && priceInput) {
                    const packageItem = document.createElement('div');
                    packageItem.className = 'package-item mb-2 p-2';
                    packageItem.style = 'border: 2px solid #212529; border-radius: 5px; background: linear-gradient(45deg, #ff9a9e, #fad0c4);';

                    const discount = parseInt(discountInput.value) || 0;

                    packageItem.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">${nameInput.value}</h6>
                            <span class="badge bg-dark" style="border: 1px solid #212529;">$${priceInput.value}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span>${amountInput.value} tokens</span>
                            ${discount > 0 ? `<span class="badge bg-success" style="border: 1px solid #212529;">${discount}% OFF</span>` : ''}
                        </div>
                    `;

                    packagesPreview.appendChild(packageItem);
                }
            }
        }

        // Track form input changes to update preview
        document.querySelectorAll('#packageTable input').forEach(input => {
            input.addEventListener('input', updatePackagePreview);
        });
    });
</script>
@endpush
