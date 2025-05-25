<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TokenRequest;
use App\Models\TokenTransaction;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TokenManagementController extends Controller
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->tokenService = $tokenService;
    }

    /**
     * Display token transaction history
     */
    public function index(Request $request)
    {
        $query = TokenTransaction::with(['user', 'admin']);

        // Filter by user
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Filter by amount range
        if ($request->has('min_amount') && is_numeric($request->min_amount)) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->has('max_amount') && is_numeric($request->max_amount)) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Order by created_at desc by default
        $query->orderBy('created_at', 'desc');

        $transactions = $query->paginate(50)->withQueryString();

        // Get transaction types for filter
        $transactionTypes = TokenTransaction::select('type')->distinct()->pluck('type')->sort()->toArray();

        // Get users for filter
        $users = User::orderBy('name')->get(['id', 'name']);

        // Calculate system-wide statistics
        $stats = [
            'total_tokens_in_circulation' => User::sum('token_balance'),
            'total_transactions' => TokenTransaction::count(),
            'total_tokens_spent' => abs(TokenTransaction::where('amount', '<', 0)->sum('amount')),
            'total_tokens_issued' => TokenTransaction::where('amount', '>', 0)->sum('amount'),
        ];

        return view('admin.tokens.index', compact('transactions', 'transactionTypes', 'users', 'stats'));
    }

    /**
     * Show form to add/deduct tokens from a user
     */
    public function showAdjustForm($userId = null)
    {
        $users = User::orderBy('name')->get(['id', 'name', 'token_balance']);
        $selectedUser = $userId ? User::findOrFail($userId) : null;

        return view('admin.tokens.adjust', compact('users', 'selectedUser'));
    }

    /**
     * Process token adjustment
     */
    public function adjust(TokenRequest $request)
    {
        try {
            $user = User::findOrFail($request->user_id);
            $amount = $request->amount;
            $description = $request->description;
            $type = $request->type;

            if ($type === 'refund' && $amount <= 0) {
                return back()->with('error', 'Refund amount must be positive.');
            }

            if ($type === 'admin_adjustment' && $amount === 0) {
                return back()->with('error', 'Adjustment amount cannot be zero.');
            }

            // Perform token adjustment
            if ($amount > 0) {
                // Add tokens
                $this->tokenService->addTokens($user, $amount, $type, $description, null, auth()->id());
            } else {
                // Deduct tokens
                $success = $this->tokenService->deductTokens($user, abs($amount), $type, $description, null, auth()->id());

                if (!$success) {
                    return back()->with('error', 'User does not have enough tokens for this deduction.');
                }
            }

            // Log admin activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'admin_token_adjustment',
                'resource_id' => $user->id,
                'resource_type' => 'User',
                'details' => json_encode([
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'amount' => $amount,
                    'type' => $type,
                    'description' => $description,
                    'new_balance' => $user->fresh()->token_balance,
                ]),
                'ip_address' => $request->ip(),
            ]);

            $actionType = $amount > 0 ? 'added to' : 'deducted from';
            return redirect()
                ->route('admin.tokens.index')
                ->with('success', abs($amount) . " tokens {$actionType} {$user->name}'s account.");
        } catch (\Exception $e) {
            Log::error('Error adjusting tokens', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except('_token'),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to adjust tokens: ' . $e->getMessage());
        }
    }

    /**
     * Update token price settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'token_price' => 'required|numeric|min:0',
            'mb_per_token' => 'required|numeric|min:0.01|max:100',
            'default_token_balance' => 'required|integer|min:0',
            'min_tokens_per_download' => 'required|integer|min:1',
        ]);

        try {
            // Save settings
            $oldSettings = [
                'token_price' => config('app.token_price'),
                'mb_per_token' => config('download.mb_per_token'),
                'default_token_balance' => config('app.default_token_balance'),
                'min_tokens_per_download' => config('download.min_tokens'),
            ];

            // In a real application, you'd use a proper settings management system.
            // This is a simplified version that writes to .env file directly.
            $this->updateEnvValue('TOKEN_PRICE', $request->token_price);
            $this->updateEnvValue('MB_PER_TOKEN', $request->mb_per_token);
            $this->updateEnvValue('DEFAULT_TOKEN_BALANCE', $request->default_token_balance);
            $this->updateEnvValue('MIN_TOKENS_PER_DOWNLOAD', $request->min_tokens_per_download);

            // Log admin activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'admin_token_settings_updated',
                'details' => json_encode([
                    'old_settings' => $oldSettings,
                    'new_settings' => [
                        'token_price' => $request->token_price,
                        'mb_per_token' => $request->mb_per_token,
                        'default_token_balance' => $request->default_token_balance,
                        'min_tokens_per_download' => $request->min_tokens_per_download,
                    ],
                ]),
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Token settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating token settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update token settings: ' . $e->getMessage());
        }
    }

    /**
     * Update .env file value
     */
    private function updateEnvValue($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);

            // If key exists, replace its value
            if (strpos($content, "{$key}=") !== false) {
                $content = preg_replace("/{$key}=.*/", "{$key}={$value}", $content);
            } else {
                // Key doesn't exist, add it
                $content .= "\n{$key}={$value}\n";
            }

            file_put_contents($path, $content);
        }
    }

    /**
     * Show token statistics
     */
    public function statistics()
    {
        // Transactions by day (last 30 days)
        $transactionsByDay = TokenTransaction::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format dates for chart
        $dates = [];
        $amounts = [];
        $counts = [];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()
                ->subDays(29 - $i)
                ->format('Y-m-d');
            $dates[] = Carbon::parse($date)->format('d M');

            $dayData = $transactionsByDay->firstWhere('date', $date);
            $amounts[] = $dayData ? $dayData->total_amount : 0;
            $counts[] = $dayData ? $dayData->count : 0;
        }

        // Transactions by type
        $transactionsByType = TokenTransaction::select('type', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))->groupBy('type')->get();

        // Users with most tokens
        $usersWithMostTokens = User::select('id', 'name', 'email', 'token_balance')->orderBy('token_balance', 'desc')->limit(10)->get();

        // Users with most token consumption
        $usersWithMostConsumption = TokenTransaction::select('user_id', DB::raw('SUM(CASE WHEN amount < 0 THEN ABS(amount) ELSE 0 END) as consumption'))->with('user:id,name,email')->groupBy('user_id')->orderBy('consumption', 'desc')->limit(10)->get();

        // Overall statistics
        $stats = [
            'total_tokens_in_circulation' => User::sum('token_balance'),
            'average_tokens_per_user' => User::avg('token_balance'),
            'total_transactions' => TokenTransaction::count(),
            'tokens_spent_on_downloads' => abs(TokenTransaction::where('type', 'download_cost')->sum('amount')),
            'tokens_from_purchases' => TokenTransaction::where('type', 'purchase')->sum('amount'),
            'tokens_from_initial' => TokenTransaction::where('type', 'initial')->sum('amount'),
            'total_refunds' => TokenTransaction::where('type', 'refund')->sum('amount'),
        ];

        return view('admin.tokens.statistics', compact('dates', 'amounts', 'counts', 'transactionsByType', 'usersWithMostTokens', 'usersWithMostConsumption', 'stats'));
    }
}
