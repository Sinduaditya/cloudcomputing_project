<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Controllers\Admin\TokenManagementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TokenRequest;
use App\Models\TokenTransaction;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\TokenPurchaseRequest;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
     * Display token transaction history and main dashboard
     */
    public function index(Request $request)
    {
        // Get transactions with filters
        $query = TokenTransaction::with(['user', 'admin']);

        // Apply filters
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $query->orderBy('created_at', 'desc');
        $transactions = $query->paginate(50)->appends(request()->query());

        $transactionTypes = TokenTransaction::select('type')->distinct()->pluck('type')->sort()->toArray();
        $users = User::orderBy('name')->get(['id', 'name', 'token_balance']);

        // Calculate statistics including purchase requests
        $stats = [
            'total_tokens_in_circulation' => User::sum('token_balance'),
            'total_transactions' => TokenTransaction::count(),
            'total_tokens_spent' => abs(TokenTransaction::where('amount', '<', 0)->sum('amount')),
            'total_tokens_issued' => TokenTransaction::where('amount', '>', 0)->sum('amount'),
            'pending_purchase_requests' => TokenPurchaseRequest::where('status', 'pending')->count(),
        ];

        // Get recent purchase requests for sidebar
        $recentPurchaseRequests = TokenPurchaseRequest::with('user')
            ->pending()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.tokens.index', compact(
            'transactions',
            'transactionTypes',
            'users',
            'stats',
            'recentPurchaseRequests'
        ));
    }

    /**
     * Show all transactions (same as index but with different view)
     */
    public function transactions(Request $request)
    {
        return $this->index($request);
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
     * Show pricing settings
     */
    public function pricing()
    {
        $settings = [
            'token_price' => config('app.token_price', 0.10),
            'mb_per_token' => config('download.mb_per_token', 10),
            'default_token_balance' => config('app.default_token_balance', 10),
            'min_tokens_per_download' => config('download.min_tokens', 1),
        ];

        return view('admin.tokens.pricing', compact('settings'));
    }

    /**
     * Update pricing settings
     */
    public function updatePricing(Request $request)
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

            // Update .env file
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
     * Show all purchase requests
     */
    public function purchaseRequests(Request $request)
    {
        $query = TokenPurchaseRequest::with(['user', 'processedBy']);

        // Apply filters
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('user_search') && !empty($request->user_search)) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_search . '%')
                  ->orWhere('email', 'like', '%' . $request->user_search . '%');
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate statistics
        $stats = [
            'total_requests' => TokenPurchaseRequest::count(),
            'pending_requests' => TokenPurchaseRequest::where('status', 'pending')->count(),
            'approved_requests' => TokenPurchaseRequest::where('status', 'approved')->count(),
            'rejected_requests' => TokenPurchaseRequest::where('status', 'rejected')->count(),
            'total_pending_value' => TokenPurchaseRequest::where('status', 'pending')->sum('price'),
        ];

        return view('admin.tokens.purchase-request', compact('requests', 'stats'));
    }

    /**
     * Approve purchase request
     */
    public function approvePurchaseRequest(Request $request, TokenPurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        if (!$purchaseRequest->canBeProcessed()) {
            return back()->with('error', 'This request cannot be processed.');
        }

        try {
            // Add tokens to user
            $this->tokenService->addTokens(
                $purchaseRequest->user,
                $purchaseRequest->token_amount,
                'purchase',
                "Token purchase approved - {$purchaseRequest->package_name}",
                $purchaseRequest->id,
                auth()->id()
            );

            // Update request status
            $purchaseRequest->update([
                'status' => TokenPurchaseRequest::STATUS_APPROVED,
                'admin_notes' => $request->admin_notes,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'token_purchase_request_approved',
                'resource_type' => 'TokenPurchaseRequest',
                'resource_id' => $purchaseRequest->id,
                'details' => json_encode([
                    'user_id' => $purchaseRequest->user_id,
                    'user_name' => $purchaseRequest->user->name,
                    'tokens_added' => $purchaseRequest->token_amount,
                    'price' => $purchaseRequest->price,
                    'admin_notes' => $request->admin_notes,
                ]),
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', "Purchase request approved! {$purchaseRequest->token_amount} tokens added to {$purchaseRequest->user->name}'s account.");

        } catch (\Exception $e) {
            Log::error('Error approving purchase request', [
                'request_id' => $purchaseRequest->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    /**
     * Reject purchase request
     */
    public function rejectPurchaseRequest(Request $request, TokenPurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        if (!$purchaseRequest->canBeProcessed()) {
            return back()->with('error', 'This request cannot be processed.');
        }

        try {
            $purchaseRequest->update([
                'status' => TokenPurchaseRequest::STATUS_REJECTED,
                'admin_notes' => $request->admin_notes,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'token_purchase_request_rejected',
                'resource_type' => 'TokenPurchaseRequest',
                'resource_id' => $purchaseRequest->id,
                'details' => json_encode([
                    'user_id' => $purchaseRequest->user_id,
                    'user_name' => $purchaseRequest->user->name,
                    'reason' => $request->admin_notes,
                ]),
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Purchase request rejected.');

        } catch (\Exception $e) {
            Log::error('Error rejecting purchase request', [
                'request_id' => $purchaseRequest->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }

    /**
     * Show token statistics
     */
    public function statistics()
    {
        // Transactions by day (last 30 days)
        $transactionsByDay = TokenTransaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format dates for chart
        $dates = [];
        $amounts = [];
        $counts = [];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(29 - $i)->format('Y-m-d');
            $dates[] = Carbon::parse($date)->format('d M');

            $dayData = $transactionsByDay->firstWhere('date', $date);
            $amounts[] = $dayData ? $dayData->total_amount : 0;
            $counts[] = $dayData ? $dayData->count : 0;
        }

        // Transactions by type
        $transactionsByType = TokenTransaction::select(
                'type',
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('type')
            ->get();

        // Users with most tokens
        $usersWithMostTokens = User::select('id', 'name', 'email', 'token_balance')
            ->orderBy('token_balance', 'desc')
            ->limit(10)
            ->get();

        // Users with most token consumption
        $usersWithMostConsumption = TokenTransaction::select(
                'user_id',
                DB::raw('SUM(CASE WHEN amount < 0 THEN ABS(amount) ELSE 0 END) as consumption')
            )
            ->with('user:id,name,email')
            ->groupBy('user_id')
            ->orderBy('consumption', 'desc')
            ->limit(10)
            ->get();

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

        return view('admin.tokens.statistics', compact(
            'dates',
            'amounts',
            'counts',
            'transactionsByType',
            'usersWithMostTokens',
            'usersWithMostConsumption',
            'stats'
        ));
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
}
