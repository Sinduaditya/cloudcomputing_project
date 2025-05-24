<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Controllers\Admin\UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Download;
use App\Models\TokenTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Don't show admins in the list by default
        if (!$request->has('show_admins')) {
            $query->where('is_admin', false);
        }

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('is_active', $request->status === 'active');
        }

        // Order by
        $orderBy = $request->orderBy ?? 'created_at';
        $orderDir = $request->orderDir ?? 'desc';
        $query->orderBy($orderBy, $orderDir);

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a new user
     */
    public function store(UserRequest $request)
    {
        try {
            $validated = $request->validated();

            // Create user with hashed password
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'token_balance' => $validated['token_balance'] ?? config('app.default_token_balance', 100),
                'is_admin' => false, // Default to non-admin
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Create initial token transaction
            if ($user->token_balance > 0) {
                TokenTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $user->token_balance,
                    'type' => 'initial',
                    'description' => 'Initial token allocation set by admin',
                    'admin_id' => auth()->id(),
                    'balance_after' => $user->token_balance,
                ]);
            }

            // Log admin activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'admin_user_created',
                'resource_id' => $user->id,
                'resource_type' => 'User',
                'details' => json_encode([
                    'name' => $user->name,
                    'email' => $user->email,
                    'token_balance' => $user->token_balance,
                    'is_active' => $user->is_active,
                ]),
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', "User {$user->name} created successfully!");

        } catch (\Exception $e) {
            Log::error('Error creating user in admin panel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['password', 'password_confirmation']),
            ]);

            return back()->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Show user details
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Get user statistics
        $stats = [
            'total_downloads' => $user->downloads()->count(),
            'completed_downloads' => $user->downloads()->where('status', 'completed')->count(),
            'failed_downloads' => $user->downloads()->where('status', 'failed')->count(),
            'total_scheduled' => $user->scheduledTasks()->count(),
            'token_transactions' => $user->tokenTransactions()->count(),
            'activities' => $user->activityLogs()->count(),
            'account_age_days' => $user->created_at->diffInDays(),
            'last_activity' => $user->activityLogs()->latest()->first()?->created_at,
            'last_download' => $user->downloads()->latest()->first()?->created_at,
        ];

        // Recent downloads
        $recentDownloads = $user->downloads()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent token transactions
        $recentTransactions = $user->tokenTransactions()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent activity
        $recentActivity = $user->activityLogs()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.users.show', compact(
            'user',
            'stats',
            'recentDownloads',
            'recentTransactions',
            'recentActivity'
        ));
    }

    /**
     * Show form to edit a user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update a user
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $validated = $request->validated();

            // Keep track of changes for activity log
            $changes = [];

            // Handle password separately (it's optional for updates)
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
                $changes['password'] = '********'; // Don't log actual password
            } else {
                unset($validated['password']);
            }

            // Track token balance change
            if (isset($validated['token_balance']) && $validated['token_balance'] != $user->token_balance) {
                $oldBalance = $user->token_balance;
                $newBalance = $validated['token_balance'];
                $changes['token_balance'] = ["from" => $oldBalance, "to" => $newBalance];

                // Create token transaction for the adjustment
                $adjustment = $newBalance - $oldBalance;
                if ($adjustment != 0) {
                    TokenTransaction::create([
                        'user_id' => $user->id,
                        'amount' => $adjustment,
                        'type' => 'admin_adjustment',
                        'description' => 'Token balance adjusted by admin',
                        'admin_id' => auth()->id(),
                        'balance_after' => $newBalance,
                    ]);
                }
            }

            // Track status change
            if (isset($validated['is_active']) && $validated['is_active'] != $user->is_active) {
                $changes['is_active'] = ["from" => $user->is_active, "to" => $validated['is_active']];
            }

            // Track name change
            if (isset($validated['name']) && $validated['name'] != $user->name) {
                $changes['name'] = ["from" => $user->name, "to" => $validated['name']];
            }

            // Track email change
            if (isset($validated['email']) && $validated['email'] != $user->email) {
                $changes['email'] = ["from" => $user->email, "to" => $validated['email']];
            }

            // Update user
            $user->update($validated);

            // Log admin activity if there were changes
            if (!empty($changes)) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'admin_user_updated',
                    'resource_id' => $user->id,
                    'resource_type' => 'User',
                    'details' => json_encode([
                        'changes' => $changes,
                    ]),
                    'ip_address' => $request->ip(),
                ]);
            }

            return redirect()->route('admin.users.show', $user->id)
                ->with('success', "User {$user->name} updated successfully!");

        } catch (\Exception $e) {
            Log::error('Error updating user in admin panel', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);

            // Don't allow deactivating admin accounts (except by super admin)
            if ($user->is_admin && !auth()->user()->isSuperAdmin()) {
                return back()->with('error', 'You cannot deactivate administrator accounts.');
            }

            $user->is_active = !$user->is_active;
            $user->save();

            // Log the action
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $user->is_active ? 'admin_user_activated' : 'admin_user_deactivated',
                'resource_id' => $user->id,
                'resource_type' => 'User',
                'details' => json_encode([
                    'name' => $user->name,
                    'email' => $user->email,
                ]),
                'ip_address' => request()->ip(),
            ]);

            $status = $user->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "User {$user->name} has been {$status}.");

        } catch (\Exception $e) {
            Log::error('Error toggling user status', [
                'user_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to update user status: ' . $e->getMessage());
        }
    }

    /**
     * Delete a user (soft delete)
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Don't allow deleting admin accounts (except by super admin)
            if ($user->is_admin && !auth()->user()->isSuperAdmin()) {
                return back()->with('error', 'You cannot delete administrator accounts.');
            }

            // Log before deletion
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'admin_user_deleted',
                'resource_id' => $user->id,
                'resource_type' => 'User',
                'details' => json_encode([
                    'name' => $user->name,
                    'email' => $user->email,
                    'token_balance' => $user->token_balance,
                    'downloads_count' => $user->downloads()->count(),
                ]),
                'ip_address' => request()->ip(),
            ]);

            // Soft delete
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', "User {$user->name} has been deleted.");

        } catch (\Exception $e) {
            Log::error('Error deleting user', [
                'user_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Show user's all downloads
     */
    public function downloads($id)
    {
        $user = User::findOrFail($id);
        $downloads = $user->downloads()->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.downloads', compact('user', 'downloads'));
    }

    /**
     * Show user's all transactions
     */
    public function transactions($id)
    {
        $user = User::findOrFail($id);
        $transactions = $user->tokenTransactions()->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.transactions', compact('user', 'transactions'));
    }

    /**
     * Show user's all activities
     */
    public function activities($id)
    {
        $user = User::findOrFail($id);
        $activities = $user->activityLogs()->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.activities', compact('user', 'activities'));
    }
}
