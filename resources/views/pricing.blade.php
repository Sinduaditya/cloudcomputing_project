@extends('layouts.app')

@section('title', 'Pricing')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="text-center mb-5 py-4">
        <h1 class="display-4 fw-bold mb-4">Simple <span class="text-primary-gradient">Token-Based Pricing</span></h1>
        <p class="lead col-lg-8 mx-auto">Our token system gives you complete flexibility - pay only for what you use, with no monthly subscriptions or commitments</p>
    </div>

    <!-- Token Usage Calculator -->
    <div class="neo-card mb-5">
        <div class="card-header">
            <h4 class="mb-0 fw-bold">Token Usage Calculator</h4>
        </div>
        <div class="card-body" style="padding: 12px;">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="mb-3" style="pad">
                        <label class="form-label fw-bold">Content Type</label>
                        <select id="contentType" class="form-select neo-form-control">
                            <option value="1">Video (YouTube, Vimeo, etc.)</option>
                            <option value="0.8">Audio (SoundCloud, Podcasts, etc.)</option>
                            <option value="1.2">Streaming Content</option>
                            <option value="1.5">High-Definition Content (4K)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estimated Size (MB)</label>
                        <input type="range" class="form-range" id="sizeRange" min="10" max="2000" step="10" value="100">
                        <div class="d-flex justify-content-between">
                            <span>10 MB</span>
                            <span id="sizeValue">100 MB</span>
                            <span>2 GB</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Additional Processing</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="formatConversion">
                            <label class="form-check-label" for="formatConversion">Format Conversion (+2 tokens)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="qualityEnhancement">
                            <label class="form-check-label" for="qualityEnhancement">Quality Enhancement (+3 tokens)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="subtitleExtraction">
                            <label class="form-check-label" for="subtitleExtraction">Subtitle Extraction (+1 token)</label>
                        </div>
                    </div>
                    <button id="calculateBtn" class="neo-btn w-100" style="color: #ffffff;">Calculate Token Cost</button>
                </div>
                <div class="col-md-6">
                    <div class="h-100 d-flex flex-column">
                        <div class="pt-3 pb-4 text-center flex-grow-1">
                            <h2 class="mb-1 fw-bold">Estimated Cost</h2>
                            <div class="d-flex justify-content-center align-items-baseline mb-4">
                                <span class="display-1 fw-bold" id="tokenAmount">5</span>
                                <span class="h4 ms-2">tokens</span>
                            </div>
                            <p class="mb-1">Approximate USD Price</p>
                            <p class="h3 fw-bold text-primary-gradient" id="usdPrice">$0.50</p>
                            <p class="small text-muted mb-4">Based on standard token package pricing</p>
                            <div class="d-flex flex-column gap-2">
                                <div class="neo-card p-3" style="border: 2px dashed var(--secondary); background-color: #f8f9fa;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold">Base Download Cost:</span>
                                            <div id="baseTokens" class="mt-1">5 tokens</div>
                                        </div>
                                        <i class="fas fa-cloud-download-alt fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div id="processingCostCard" class="neo-card p-3" style="border: 2px dashed var(--secondary); background-color: #f8f9fa; display: none;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold">Processing Cost:</span>
                                            <div id="processingTokens" class="mt-1">0 tokens</div>
                                        </div>
                                        <i class="fas fa-cogs fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @guest
                            <a href="{{ route('register') }}" class="neo-btn btn-secondary w-100">Create Account to Start</a>
                        @else
                            <a href="{{ route('tokens.purchase') }}" class="neo-btn btn-secondary w-100">Purchase Tokens</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Token Packages -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold mb-3">Token Packages</h2>
            <p class="lead">Choose the package that best fits your needs</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="neo-card h-100 position-relative overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">Starter</h3>
                        <div class="d-flex align-items-end mb-4">
                            <span class="display-4 fw-bold">$5</span>
                            <span class="text-muted ms-2 mb-2">.99</span>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div style="width: 50px; height: 50px; background: #FFD700; border: 2px solid var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                <span class="fw-bold">50</span>
                            </div>
                            <div>
                                <span class="fw-bold">50 Tokens</span><br>
                                <span class="text-muted">$0.12 per token</span>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>Approximately 500 MB of downloads</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>Standard processing options</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>7-day cloud storage</span>
                            </li>
                            <li class="d-flex align-items-center text-muted">
                                <i class="fas fa-times-circle me-2 text-muted"></i>
                                <span>No priority processing</span>
                            </li>
                        </ul>
                        <a href="{{ route('tokens.purchase') }}" class="neo-btn w-100" style="color: #ffffff;">Purchase</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="neo-card h-100 position-relative overflow-hidden">
                    <div class="position-absolute" style="top: 20px; right: -35px; background: var(--primary); color: white; transform: rotate(45deg); padding: 5px 40px; font-weight: bold; border: 2px solid var(--secondary); box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        POPULAR
                    </div>
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">Plus</h3>
                        <div class="d-flex align-items-end mb-4">
                            <span class="display-4 fw-bold">$15</span>
                            <span class="text-muted ms-2 mb-2">.99</span>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div style="width: 50px; height: 50px; background: #FF6B6B; border: 2px solid var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                <span class="fw-bold">200</span>
                            </div>
                            <div>
                                <span class="fw-bold">200 Tokens</span><br>
                                <span class="text-muted">$0.08 per token</span>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>Approximately 2 GB of downloads</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>All processing options</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>15-day cloud storage</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>Normal priority processing</span>
                            </li>
                        </ul>
                        <a href="{{ route('tokens.purchase') }}" class="neo-btn w-100" style="color: #ffffff;"  >Purchase</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="neo-card h-100 position-relative overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">Pro</h3>
                        <div class="d-flex align-items-end mb-4">
                            <span class="display-4 fw-bold">$29</span>
                            <span class="text-muted ms-2 mb-2">.99</span>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div style="width: 50px; height: 50px; background: #4ECDC4; border: 2px solid var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; box-shadow: 3px 3px 0 var(--<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\pricing.blade.php -->
@extends('layouts.app')

@section('title', 'Pricing')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="text-center mb-5 py-4">
        <h1 class="display-4 fw-bold mb-4">Simple <span class="text-primary-gradient">Token-Based Pricing</span></h1>
        <p class="lead col-lg-8 mx-auto">Our token system gives you complete flexibility - pay only for what you use, with no monthly subscriptions or commitments</p>
    </div>

    <!-- Token Usage Calculator -->
    <div class="neo-card mb-5">
        <div class="card-header">
            <h4 class="mb-0 fw-bold">Token Usage Calculator</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Content Type</label>
                        <select id="contentType" class="form-select neo-form-control">
                            <option value="1">Video (YouTube, Vimeo, etc.)</option>
                            <option value="0.8">Audio (SoundCloud, Podcasts, etc.)</option>
                            <option value="1.2">Streaming Content</option>
                            <option value="1.5">High-Definition Content (4K)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estimated Size (MB)</label>
                        <input type="range" class="form-range" id="sizeRange" min="10" max="2000" step="10" value="100">
                        <div class="d-flex justify-content-between">
                            <span>10 MB</span>
                            <span id="sizeValue">100 MB</span>
                            <span>2 GB</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Additional Processing</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="formatConversion">
                            <label class="form-check-label" for="formatConversion">Format Conversion (+2 tokens)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="qualityEnhancement">
                            <label class="form-check-label" for="qualityEnhancement">Quality Enhancement (+3 tokens)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="subtitleExtraction">
                            <label class="form-check-label" for="subtitleExtraction">Subtitle Extraction (+1 token)</label>
                        </div>
                    </div>
                    <button id="calculateBtn" class="neo-btn w-100">Calculate Token Cost</button>
                </div>
                <div class="col-md-6">
                    <div class="h-100 d-flex flex-column">
                        <div class="pt-3 pb-4 text-center flex-grow-1">
                            <h2 class="mb-1 fw-bold">Estimated Cost</h2>
                            <div class="d-flex justify-content-center align-items-baseline mb-4">
                                <span class="display-1 fw-bold" id="tokenAmount">5</span>
                                <span class="h4 ms-2">tokens</span>
                            </div>
                            <p class="mb-1">Approximate USD Price</p>
                            <p class="h3 fw-bold text-primary-gradient" id="usdPrice">$0.50</p>
                            <p class="small text-muted mb-4">Based on standard token package pricing</p>
                            <div class="d-flex flex-column gap-2">
                                <div class="neo-card p-3" style="border: 2px dashed var(--secondary); background-color: #f8f9fa;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold">Base Download Cost:</span>
                                            <div id="baseTokens" class="mt-1">5 tokens</div>
                                        </div>
                                        <i class="fas fa-cloud-download-alt fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div id="processingCostCard" class="neo-card p-3" style="border: 2px dashed var(--secondary); background-color: #f8f9fa; display: none;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold">Processing Cost:</span>
                                            <div id="processingTokens" class="mt-1">0 tokens</div>
                                        </div>
                                        <i class="fas fa-cogs fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @guest
                            <a href="{{ route('register') }}" class="neo-btn btn-secondary w-100">Create Account to Start</a>
                        @else
                            <a href="{{ route('tokens.purchase') }}" class="neo-btn btn-secondary w-100">Purchase Tokens</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Token Packages -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold mb-3">Token Packages</h2>
            <p class="lead">Choose the package that best fits your needs</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="neo-card h-100 position-relative overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">Starter</h3>
                        <div class="d-flex align-items-end mb-4">
                            <span class="display-4 fw-bold">$5</span>
                            <span class="text-muted ms-2 mb-2">.99</span>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div style="width: 50px; height: 50px; background: #FFD700; border: 2px solid var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                <span class="fw-bold">50</span>
                            </div>
                            <div>
                                <span class="fw-bold">50 Tokens</span><br>
                                <span class="text-muted">$0.12 per token</span>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>Approximately 500 MB of downloads</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>Standard processing options</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>7-day cloud storage</span>
                            </li>
                            <li class="d-flex align-items-center text-muted">
                                <i class="fas fa-times-circle me-2 text-muted"></i>
                                <span>No priority processing</span>
                            </li>
                        </ul>
                        <a href="{{ route('tokens.purchase') }}" class="neo-btn w-100">Purchase</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="neo-card h-100 position-relative overflow-hidden">
                    <div class="position-absolute" style="top: 20px; right: -35px; background: var(--primary); color: white; transform: rotate(45deg); padding: 5px 40px; font-weight: bold; border: 2px solid var(--secondary); box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        POPULAR
                    </div>
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">Plus</h3>
                        <div class="d-flex align-items-end mb-4">
                            <span class="display-4 fw-bold">$15</span>
                            <span class="text-muted ms-2 mb-2">.99</span>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div style="width: 50px; height: 50px; background: #FF6B6B; border: 2px solid var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                <span class="fw-bold">200</span>
                            </div>
                            <div>
                                <span class="fw-bold">200 Tokens</span><br>
                                <span class="text-muted">$0.08 per token</span>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>Approximately 2 GB of downloads</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>All processing options</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>15-day cloud storage</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>Normal priority processing</span>
                            </li>
                        </ul>
                        <a href="{{ route('tokens.purchase') }}" class="neo-btn w-100">Purchase</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="neo-card h-100 position-relative overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-3">Pro</h3>
                        <div class="d-flex align-items-end mb-4">
                            <span class="display-4 fw-bold">$29</span>
                            <span class="text-muted ms-2 mb-2">.99</span>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div style="width: 50px; height: 50px; background: #4ECDC4; border: 2px solid var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                <span class="fw-bold">500</span>
                            </div>
                            <div>
                                <span class="fw-bold">500 Tokens</span><br>
                                <span class="text-muted">$0.06 per token</span>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>Approximately 5 GB of downloads</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>Premium processing options</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>30-day cloud storage</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                <span>High priority processing</span>
                            </li>
                        </ul>
                        <a href="{{ route('tokens.purchase') }}" class="neo-btn w-100">Purchase</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pay-as-you-go Option -->
    <div class="neo-card mb-5">
        <div class="card-header">
            <h4 class="mb-0 fw-bold">Pay-As-You-Go Option</h4>
        </div>
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h3 class="fw-bold mb-3">Need Just a Few Tokens?</h3>
                    <p class="mb-4">Purchase exactly what you need without committing to a larger package. Our pay-as-you-go option gives you complete flexibility.</p>

                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 50px; height: 50px; background: #C39BD3; border: 2px solid var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; box-shadow: 3px 3px 0 var(--shadow-color);">
                            <i class="fas fa-coins text-white"></i>
                        </div>
                        <div>
                            <span class="fw-bold">Individual Tokens</span><br>
                            <span class="text-muted">Starting at $0.15 per token</span>
                        </div>
                    </div>

                    <ul class="list-unstyled mb-4">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            <span>No minimum purchase required</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            <span>Standard processing options</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 text-success"></i>
                            <span>3-day cloud storage</span>
                        </li>
                        <li class="d-flex align-items-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>Better value when purchasing packages</span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-5">
                    <div class="neo-card p-4" style="background-color: #f8f9fa;">
                        <div class="text-center mb-3">
                            <span class="h6 mb-0 d-inline-block px-3 py-1" style="border: 2px solid var(--secondary); background-color: #AED6F1; border-radius: 20px; box-shadow: 2px 2px 0 var(--shadow-color);">
                                <i class="fas fa-gift me-1"></i> NEW USERS
                            </span>
                        </div>
                        <div class="mb-3 text-center">
                            <h4 class="fw-bold">Get 10 Free Tokens</h4>
                            <p>When you create a new account</p>
                        </div>
                        <div class="neo-card p-3 mb-4" style="border: 2px dashed var(--secondary); background-color: white;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">Free Starter Tokens:</span>
                                    <div class="mt-1">10 tokens (~$1.50 value)</div>
                                </div>
                                <i class="fas fa-gift fa-2x text-primary"></i>
                            </div>
                        </div>
                        @guest
                            <a href="{{ route('register') }}" class="neo-btn w-100">
                                <i class="fas fa-user-plus me-2"></i> Sign Up & Claim
                            </a>
                        @else
                            <a href="{{ route('tokens.purchase') }}" class="neo-btn w-100">Purchase More</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold mb-3">Frequently Asked Questions</h2>
            <p class="lead">Everything you need to know about our token system</p>
        </div>

        <div class="neo-card mb-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-question-circle me-2 text-primary"></i> What are tokens?</h5>
                            <p class="mb-0">Tokens are our virtual currency used to pay for downloads and processing. Each token has a value in terms of the amount of data you can download or the processing features you can use.</p>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-question-circle me-2 text-primary"></i> How many tokens do I need?</h5>
                            <p class="mb-0">The number of tokens needed depends on the size and type of content you want to download. Generally, 1 token covers approximately 10MB of content. Use our calculator above to get an estimate.</p>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-question-circle me-2 text-primary"></i> Do tokens expire?</h5>
                            <p class="mb-0">No, your tokens never expire. Once purchased, they remain in your account until used.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-question-circle me-2 text-primary"></i> Can I get a refund for unused tokens?</h5>
                            <p class="mb-0">While tokens are non-refundable, if you experience technical issues that prevent you from using the service, please contact our support team for assistance.</p>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-question-circle me-2 text-primary"></i> What payment methods do you accept?</h5>
                            <p class="mb-0">We accept all major credit cards, PayPal, and select cryptocurrency payments. All transactions are secure and encrypted.</p>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-question-circle me-2 text-primary"></i> Are there any subscription fees?</h5>
                            <p class="mb-0">No, we don't have any subscription fees or recurring charges. You only pay for the tokens you purchase.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold mb-3">What Our Users Say</h2>
            <p class="lead">Thousands of satisfied customers use our token system</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="neo-card h-100 p-4">
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="User" class="rounded-circle" width="60" height="60" style="border: 2px solid var(--secondary); box-shadow: 3px 3px 0 var(--shadow-color);">
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">Sarah J.</h5>
                            <p class="mb-0 text-muted">Content Creator</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex mb-2">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="fst-italic">"The token system is so convenient. I only pay for what I use, and the quality of downloads is excellent. Definitely worth every token!"</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="neo-card h-100 p-4">
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <img src="https://randomuser.me/api/portraits/men/54.jpg" alt="User" class="rounded-circle" width="60" height="60" style="border: 2px solid var(--secondary); box-shadow: 3px 3px 0 var(--shadow-color);">
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">Michael T.</h5>
                            <p class="mb-0 text-muted">Podcast Producer</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex mb-2">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                        </div>
                        <p class="fst-italic">"I love that I can buy tokens in bulk for my team. We've saved so much time and money compared to our previous solution."</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="neo-card h-100 p-4">
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User" class="rounded-circle" width="60" height="60" style="border: 2px solid var(--secondary); box-shadow: 3px 3px 0 var(--shadow-color);">
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">Elena R.</h5>
                            <p class="mb-0 text-muted">Student</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex mb-2">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="fst-italic">"The free tokens for new users got me started, and I've been hooked ever since. The processing options save me hours of work!"</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="neo-card p-5 mb-5 text-center">
        <h2 class="display-5 fw-bold mb-3">Ready to Get Started?</h2>
        <p class="lead col-lg-8 mx-auto mb-4">Join thousands of satisfied users who trust our token-based system for their content needs.</p>
        <div class="d-flex justify-content-center gap-3">
            @guest
                <a href="{{ route('register') }}" class="neo-btn btn-lg">
                    <i class="fas fa-user-plus me-2"></i> Create Account
                </a>
                <a href="{{ route('login') }}" class="neo-btn btn-secondary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i> Log In
                </a>
            @else
                <a href="{{ route('tokens.purchase') }}" class="neo-btn btn-lg">
                    <i class="fas fa-coins me-2"></i> Purchase Tokens
                </a>
                <a href="{{ route('dashboard') }}" class="neo-btn btn-secondary btn-lg">
                    <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                </a>
            @endguest
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --secondary: #212529;
        --shadow-color: rgba(0, 0, 0, 0.2);
    }

    .text-primary-gradient {
        background: linear-gradient(90deg,rgb(231, 177, 0),rgb(235, 180, 0));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .neo-card {
        border: 3px solid var(--secondary);
        border-radius: 8px;
        box-shadow: 5px 5px 0 var(--shadow-color);
        background: #ffffff;
        overflow: hidden;
    }

    .neo-card .card-header {
        border-bottom: 2px solid var(--secondary);
        padding: 1rem;
    }

    .neo-form-control {
        border: 2px solid var(--secondary);
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        box-shadow: 3px 3px 0 var(--shadow-color);
    }

    .neo-form-control:focus {
        border-color: var(--primary);
        box-shadow: 4px 4px 0 var(--shadow-color);
        outline: none;
    }

    .form-range::-webkit-slider-thumb {
        background-color: var(--primary);
        border: 2px solid var(--secondary);
        box-shadow: 2px 2px 0 var(--shadow-color);
    }

    .form-check-input {
        border: 2px solid var(--secondary);
        box-shadow: 2px 2px 0 var(--shadow-color);
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--secondary);
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
        border: 2px solid var(--secondary);
        border-radius: 0.375rem;
        box-shadow: 3px 3px 0 var(--shadow-color);
        transition: transform 0.1s, box-shadow 0.1s;
        cursor: pointer;
        text-decoration: none;
        color: var(--secondary);
    }

    .neo-btn:hover {
        transform: translate(-1px, -1px);
        box-shadow: 4px 4px 0 var(--shadow-color);
    }

    .neo-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 2px 2px 0 var(--shadow-color);
    }

    .neo-btn.btn-secondary {
        background: #f8f9fa;
    }

    .neo-btn.btn-lg {
        padding: 0.5rem 1rem;
        font-size: 1.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sizeRange = document.getElementById('sizeRange');
        const sizeValue = document.getElementById('sizeValue');
        const contentType = document.getElementById('contentType');
        const formatConversion = document.getElementById('formatConversion');
        const qualityEnhancement = document.getElementById('qualityEnhancement');
        const subtitleExtraction = document.getElementById('subtitleExtraction');
        const calculateBtn = document.getElementById('calculateBtn');
        const tokenAmount = document.getElementById('tokenAmount');
        const usdPrice = document.getElementById('usdPrice');
        const baseTokens = document.getElementById('baseTokens');
        const processingTokens = document.getElementById('processingTokens');
        const processingCostCard = document.getElementById('processingCostCard');

        // Update size value display when range input changes
        sizeRange.addEventListener('input', function() {
            sizeValue.textContent = this.value + ' MB';
        });

        // Calculate token cost
        calculateBtn.addEventListener('click', function() {
            // Base calculation: size (MB) / 10 MB per token * content type multiplier
            const size = parseInt(sizeRange.value);
            const typeMultiplier = parseFloat(contentType.value);
            let tokens = Math.ceil((size / 10) * typeMultiplier);

            // Minimum 1 token
            tokens = Math.max(1, tokens);

            // Additional processing costs
            let additionalTokens = 0;
            if (formatConversion.checked) additionalTokens += 2;
            if (qualityEnhancement.checked) additionalTokens += 3;
            if (subtitleExtraction.checked) additionalTokens += 1;

            const totalTokens = tokens + additionalTokens;
            const price = (totalTokens * 0.10).toFixed(2); // Assuming $0.10 per token

            // Update UI
            tokenAmount.textContent = totalTokens;
            usdPrice.textContent = '$' + price;
            baseTokens.textContent = tokens + ' tokens';

            if (additionalTokens > 0) {
                processingTokens.textContent = additionalTokens + ' tokens';
                processingCostCard.style.display = 'block';
            } else {
                processingCostCard.style.display = 'none';
            }
        });
    });
</script>
@endpush
