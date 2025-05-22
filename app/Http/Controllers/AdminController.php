<?php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Download;
use App\Models\ScheduledTask;
use App\Models\ActivityLog;
use App\Services\TokenService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->middleware('admin');
        $this->tokenService = $tokenService;
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('is_admin', false)->count(),
            'active_users' => User::where('is_admin', false)->where('is_active', true)->count(),
            'pending_downloads' => Download::where('status', 'pending')->orWhere('status', 'processing')->count(),
            'completed_downloads' => Download::where('status', 'completed')->count(),
            'scheduled_tasks' => ScheduledTask::where('status', 'scheduled')->count()
        ];

        $recent_downloads = Download::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recent_activities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_downloads', 'recent_activities'));
    }

    public function users()
    {
        $users = User::where('is_admin', false)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function userDownloads(User $user)
    {
        $downloads = $user->downloads()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.user-downloads', compact('user', 'downloads'));
    }

    public function userSchedules(User $user)
    {
        $schedules = $user->scheduledTasks()
            ->orderBy('scheduled_for')
            ->paginate(15);

        return view('admin.user-schedules', compact('user', 'schedules'));
    }

    public function tokenManagement()
    {
        $users = User::where('is_admin', false)
            ->orderBy('name')
            ->get();

        return view('admin.token-management', compact('users'));
    }

    public function updateTokens(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|integer',
            'reason' => 'required|string|max:255',
        ]);

        $amount = $request->amount;
        $reason = $request->reason;

        // Update tokens
        $this->tokenService->addTokens(
            $user,
            $amount,
            'admin_adjustment',
            $reason,
            null,
            auth()->id()
        );

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'token_adjustment',
            'resource_id' => $user->id,
            'resource_type' => 'User',
            'details' => json_encode([
                'amount' => $amount,
                'reason' => $reason,
                'target_user' => $user->email
            ]),
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', "Tokens updated for {$user->name}. {$amount} tokens added/removed.");
    }

    public function toggleUserStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_status_change',
            'resource_id' => $user->id,
            'resource_type' => 'User',
            'details' => json_encode([
                'new_status' => $status,
                'target_user' => $user->email
            ]),
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', "User {$user->name} has been {$status}.");
    }
}
