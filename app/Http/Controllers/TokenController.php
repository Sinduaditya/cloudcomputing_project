<?php


namespace App\Http\Controllers;

use App\Models\TokenTransaction;
use App\Models\ActivityLog;
use App\Services\TokenService;
use App\Http\Requests\TokenRequest;
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
    public function index()
    {
        $transactions = auth()->user()->tokenTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tokens.history', compact('transactions'));
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
        $monthlyUsage = abs($user->tokenTransactions()
            ->where('created_at', '>=', now()->subMonths(1))
            ->where('amount', '<', 0)
            ->sum('amount')) ?? 0;

        $stats = [
            'total_spent' => $totalSpent,
            'total_received' => $totalReceived,
            'download_costs' => abs($user->tokenTransactions()->where('type', 'download_cost')->sum('amount')) ?? 0,
            'refunds' => $user->tokenTransactions()->where('type', 'refund')->sum('amount') ?? 0,
            'purchased_tokens' => $user->tokenTransactions()->where('type', 'purchase')->sum('amount') ?? 0,
            'bonus_tokens' => $user->tokenTransactions()->where('type', 'bonus')->sum('amount') ?? 0,
            'used_tokens' => $totalSpent,
            'monthly_avg' => $monthlyUsage > 0 ? round($monthlyUsage / 30, 1) : 0,
            'last_7_days' => abs($user->tokenTransactions()
                ->where('created_at', '>=', now()->subDays(7))
                ->where('amount', '<', 0)
                ->sum('amount')) ?? 0,
            //estimated_days' => $this->calculateEstimatedDays($user),
        ];

        // Get recent transactions
        $recentTransactions = $user->tokenTransactions()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $packages = [
            [
                'id' => 'basic', 
                'amount' => 50,
                'tokens' => 50,
                'price' => '$5.99',
                'description' => 'Starter',
                'discount' => 0,
                'best_value' => false
            ],
            [
                'id' => 'standard', 
                'amount' => 200,
                'tokens' => 200,
                'price' => '$15.99',
                'description' => 'Plus',
                'discount' => 0,
                'best_value' => false
            ],
            [
                'id' => 'premium', 
                'amount' => 500,
                'tokens' => 500,
                'price' => '$29',
                'description' => 'Pro',
                'discount' => 0,
                'best_value' => true
            ],
        ];

        return view('tokens.balance', compact('tokenBalance', 'stats', 'recentTransactions', 'packages'));
    }

    /**
     * Display token purchase options
     */
    public function purchase()
    {
        // You could set up different token package options here
        $packages = [
            [
                'id' => 'basic', 
                'amount' => 50,
                'tokens' => 50,
                'price' => '$5.99',
                'description' => 'Starter',
                'discount' => 0,
                'best_value' => false
            ],
            [
                'id' => 'standard', 
                'amount' => 200,
                'tokens' => 200,
                'price' => '$15.99',
                'description' => 'Plus',
                'discount' => 0,
                'best_value' => false
            ],
            [
                'id' => 'premium', 
                'amount' => 500,
                'tokens' => 500,
                'price' => '$29',
                'description' => 'Pro',
                'discount' => 0,
                'best_value' => true
            ],
        ];

        return view('tokens.purchase', compact('packages'));
    }

    /**
     * Process token purchase (placeholder for payment integration)
     */
    public function processPurchase(Request $request)
    {
        $request->validate([
            'package' => 'required|in:basic,standard,premium',
        ]);

        // This is a placeholder. In a real implementation, you would:
        // 1. Process payment through a payment gateway
        // 2. On successful payment, add tokens to user account

        $packageTokens = [
            'basic' => 100,
            'standard' => 500,
            'premium' => 1200,
        ];

        $tokens = $packageTokens[$request->package];

        try {
            // Add tokens to user account
            $this->tokenService->addTokens(
                auth()->user(),
                $tokens,
                'purchase',
                "Purchased {$tokens} tokens ({$request->package} package)"
            );

            // Log the activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'token_purchase',
                'details' => json_encode([
                    'package' => $request->package,
                    'tokens' => $tokens,
                ]),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('tokens.balance')
                ->with('success', "Successfully purchased {$tokens} tokens!");

        } catch (\Exception $e) {
            Log::error('Token purchase failed', [
                'user' => auth()->id(),
                'package' => $request->package,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to process purchase: ' . $e->getMessage());
        }
    }
}
