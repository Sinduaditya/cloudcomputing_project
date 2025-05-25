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
        $tokenBalance = $user->token_balance;

        // Get statistics
        $stats = [
            'total_spent' => abs($user->tokenTransactions()->where('amount', '<', 0)->sum('amount')),
            'total_received' => $user->tokenTransactions()->where('amount', '>', 0)->sum('amount'),
            'download_costs' => abs($user->tokenTransactions()->where('type', 'download_cost')->sum('amount')),
            'refunds' => $user->tokenTransactions()->where('type', 'refund')->sum('amount'),
        ];

        // Get recent transactions
        $recentTransactions = $user->tokenTransactions()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('tokens.balance', compact('tokenBalance', 'stats', 'recentTransactions'));
    }

    /**
     * Display token purchase options
     */
    public function purchase()
    {
        // You could set up different token package options here
        $packages = [
            ['id' => 'basic', 'tokens' => 100, 'price' => 10, 'description' => 'Basic package'],
            ['id' => 'standard', 'tokens' => 500, 'price' => 40, 'description' => 'Standard package - 20% savings'],
            ['id' => 'premium', 'tokens' => 1200, 'price' => 80, 'description' => 'Premium package - 30% savings'],
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
