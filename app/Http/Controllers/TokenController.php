<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Controllers\TokenController.php

namespace App\Http\Controllers;

use App\Models\TokenTransaction;
use App\Models\ActivityLog;
use App\Services\TokenService;
use App\Http\Requests\TokenRequest;
use App\Models\TokenPurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TokenController extends Controller
{
    protected $tokenService;

    /**
     * Create a new controller instance
     *
     * @param TokenService $tokenService
     */
    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
        $this->middleware('auth');
    }

    /**
     * Display token history
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $user->tokenTransactions();

        // Apply filters
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        if ($request->has('amount_type') && !empty($request->amount_type)) {
            if ($request->amount_type === 'positive') {
                $query->where('amount', '>', 0);
            } elseif ($request->amount_type === 'negative') {
                $query->where('amount', '<', 0);
            }
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate statistics
        $stats = [
            'total_transactions' => $user->tokenTransactions()->count(),
            'total_purchased' => $user->tokenTransactions()->where('amount', '>', 0)->sum('amount'),
            'total_spent' => abs($user->tokenTransactions()->where('amount', '<', 0)->sum('amount')),
            'net_balance' => $user->token_balance,
        ];

        return view('tokens.history', compact('transactions', 'stats'));
    }

    /**
     * Display token balance
     */
    public function balance()
    {
        $user = auth()->user();
        $tokenBalance = $user->token_balance ?? 0;

        // Get statistics
        $totalSpent = abs($user->tokenTransactions()->where('amount', '<', 0)->sum('amount')) ?? 0;
        $totalReceived = $user->tokenTransactions()->where('amount', '>', 0)->sum('amount') ?? 0;
        $monthlyUsage =
            abs(
                $user
                    ->tokenTransactions()
                    ->where('created_at', '>=', now()->subMonths(1))
                    ->where('amount', '<', 0)
                    ->sum('amount'),
            ) ?? 0;

        $stats = [
            'total_spent' => $totalSpent,
            'total_received' => $totalReceived,
            'download_costs' => abs($user->tokenTransactions()->where('type', 'download_cost')->sum('amount')) ?? 0,
            'refunds' => $user->tokenTransactions()->where('type', 'refund')->sum('amount') ?? 0,
            'purchased_tokens' => $user->tokenTransactions()->where('type', 'purchase')->sum('amount') ?? 0,
            'bonus_tokens' => $user->tokenTransactions()->where('type', 'bonus')->sum('amount') ?? 0,
            'used_tokens' => $totalSpent,
            'monthly_avg' => $monthlyUsage > 0 ? round($monthlyUsage / 30, 1) : 0,
            'last_7_days' =>
                abs(
                    $user
                        ->tokenTransactions()
                        ->where('created_at', '>=', now()->subDays(7))
                        ->where('amount', '<', 0)
                        ->sum('amount'),
                ) ?? 0,
        ];

        // Get recent transactions
        $recentTransactions = $user->tokenTransactions()->orderBy('created_at', 'desc')->limit(5)->get();

        $packages = [
            [
                'id' => 'basic',
                'amount' => 100,
                'tokens' => 100,
                'price' => 10000,
                'description' => 'Basic package',
                'discount' => 0,
                'best_value' => false,
            ],
            [
                'id' => 'standard',
                'amount' => 500,
                'tokens' => 500,
                'price' => 45000,
                'description' => 'Standard package - 10% savings',
                'discount' => 10,
                'best_value' => false,
            ],
            [
                'id' => 'premium',
                'amount' => 1000,
                'tokens' => 1000,
                'price' => 80000,
                'description' => 'Premium package - 20% savings',
                'discount' => 20,
                'best_value' => true,
            ],
        ];

        return view('tokens.balance', compact('tokenBalance', 'stats', 'recentTransactions', 'packages'));
    }

    /**
     * Display token purchase options
     */
    public function purchase(Request $request)
    {
        $user = auth()->user();
        $currentBalance = $user->token_balance ?? 0;

        // Define packages with consistent structure
        $packages = [
            [
                'id' => 'basic',
                'amount' => 100,
                'price' => 10000,
                'original_price' => 10000,
                'price_per_token' => 'Rp 100',
                'description' => 'Perfect for light usage',
                'discount' => 0,
                'best_value' => false,
            ],
            [
                'id' => 'standard',
                'amount' => 500,
                'price' => 45000,
                'original_price' => 50000,
                'price_per_token' => 'Rp 90',
                'description' => 'Great for regular users',
                'discount' => 10,
                'best_value' => false,
            ],
            [
                'id' => 'premium',
                'amount' => 1000,
                'price' => 80000,
                'original_price' => 100000,
                'price_per_token' => 'Rp 80',
                'description' => 'Best value for power users',
                'discount' => 20,
                'best_value' => true,
            ],
        ];

        // Get selected package from URL parameter
        $selectedPackage = $request->get('package', 'basic');

        // Validate selected package
        $validPackages = collect($packages)->pluck('id')->toArray();
        if (!in_array($selectedPackage, $validPackages)) {
            $selectedPackage = 'basic';
        }

        // Get selected package details
        $selectedPackageData = collect($packages)->firstWhere('id', $selectedPackage);
        $selectedPackageAmount = $selectedPackageData['amount'] ?? 0;
        $selectedPackagePrice = $selectedPackageData['price'] ?? 0;

        // Get payment method from URL parameter
        $paymentMethod = $request->get('payment_method', 'bank_transfer');

        // Validate payment method
        $validPaymentMethods = ['bank_transfer', 'credit_card', 'e_wallet'];
        if (!in_array($paymentMethod, $validPaymentMethods)) {
            $paymentMethod = 'bank_transfer';
        }

        return view('tokens.purchase', compact(
            'packages',
            'currentBalance',
            'selectedPackage',
            'selectedPackageAmount',
            'selectedPackagePrice',
            'paymentMethod'
        ));
    }

    public function processPurchase(Request $request)
    {
        $request->validate([
            'package_id' => 'required|in:basic,standard,premium',
            'payment_method' => 'required|in:bank_transfer,e_wallet,credit_card',
            'user_notes' => 'nullable|string|max:500',
        ]);

        // Define packages (same as in purchase method)
        $packages = [
            'basic' => [
                'name' => 'Basic Package',
                'tokens' => 100,
                'price' => 10000,
                'discount' => 0,
            ],
            'standard' => [
                'name' => 'Standard Package',
                'tokens' => 500,
                'price' => 45000,
                'discount' => 10,
            ],
            'premium' => [
                'name' => 'Premium Package',
                'tokens' => 1000,
                'price' => 80000,
                'discount' => 20,
            ],
        ];

        $selectedPackage = $packages[$request->package_id];

        try {
            // For now, we'll create purchase request without payment proof
            // In production, you'd require payment proof
            $purchaseRequest = TokenPurchaseRequest::create([
                'user_id' => auth()->id(),
                'package_id' => $request->package_id,
                'package_name' => $selectedPackage['name'],
                'token_amount' => $selectedPackage['tokens'],
                'price' => $selectedPackage['price'],
                'discount' => $selectedPackage['discount'],
                'payment_method' => $request->payment_method,
                'user_notes' => $request->user_notes,
                'payment_proof' => null, // Will be added when payment proof upload is implemented
                'status' => TokenPurchaseRequest::STATUS_PENDING,
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'token_purchase_request_created',
                'resource_type' => 'TokenPurchaseRequest',
                'resource_id' => $purchaseRequest->id,
                'details' => json_encode([
                    'package' => $request->package_id,
                    'tokens' => $selectedPackage['tokens'],
                    'price' => $selectedPackage['price'],
                    'payment_method' => $request->payment_method,
                ]),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('tokens.balance')->with('success', 'Token purchase request submitted successfully! Please wait for admin approval.');
        } catch (\Exception $e) {
            Log::error('Token purchase request failed', [
                'user' => auth()->id(),
                'package' => $request->package_id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to submit purchase request: ' . $e->getMessage());
        }
    }

    // Method untuk melihat status request
    public function requests()
    {
        $requests = auth()
            ->user()
            ->tokenPurchaseRequests()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tokens.requests', compact('requests'));
    }
}
