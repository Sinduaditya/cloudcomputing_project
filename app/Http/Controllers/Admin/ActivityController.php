<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filter by user
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->has('action') && !empty($request->action)) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by resource type
        if ($request->has('resource_type') && !empty($request->resource_type)) {
            $query->where('resource_type', $request->resource_type);
        }

        // Filter by IP address
        if ($request->has('ip_address') && !empty($request->ip_address)) {
            $query->where('ip_address', $request->ip_address);
        }

        // Order by created_at descending by default
        $query->orderBy('created_at', 'desc');

        $activities = $query->paginate(50)->withQueryString();

        // Get unique action types for filter dropdown
        $actionTypes = ActivityLog::select('action')
            ->distinct()
            ->pluck('action')
            ->sort()
            ->toArray();

        // Get unique resource types for filter dropdown
        $resourceTypes = ActivityLog::select('resource_type')
            ->whereNotNull('resource_type')
            ->distinct()
            ->pluck('resource_type')
            ->sort()
            ->toArray();

        // Get users for filter dropdown
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('admin.activities.index', compact(
            'activities',
            'actionTypes',
            'resourceTypes',
            'users'
        ));
    }

    /**
     * Display activity details
     */
    public function show($id)
    {
        $activity = ActivityLog::with('user')->findOrFail($id);

        // Format details for display
        $details = json_decode($activity->details, true) ?? [];

        return view('admin.activities.show', compact('activity', 'details'));
    }

    /**
     * Export activities as CSV
     */
    public function export(Request $request)
    {
        try {
            $query = ActivityLog::with('user');

            // Apply filters (same as index method)
            if ($request->has('user_id') && !empty($request->user_id)) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('action') && !empty($request->action)) {
                $query->where('action', $request->action);
            }

            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Order by created_at
            $query->orderBy('created_at', 'desc');

            // Get activities (limit to reasonable number for export)
            $activities = $query->limit(5000)->get();

            // Create CSV file
            $filename = 'activity_logs_' . date('Y-m-d_His') . '.csv';
            $path = storage_path('app/exports/' . $filename);

            // Ensure directory exists
            if (!file_exists(storage_path('app/exports'))) {
                mkdir(storage_path('app/exports'), 0755, true);
            }

            // Write CSV
            $file = fopen($path, 'w');

            // Add headers
            fputcsv($file, [
                'ID',
                'User ID',
                'User Name',
                'Action',
                'Resource Type',
                'Resource ID',
                'IP Address',
                'Details',
                'Created At'
            ]);

            // Add data rows
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->user_id,
                    $activity->user ? $activity->user->name : 'Unknown',
                    $activity->action,
                    $activity->resource_type ?? 'None',
                    $activity->resource_id ?? 'None',
                    $activity->ip_address,
                    $activity->details,
                    $activity->created_at
                ]);
            }

            fclose($file);

            // Log the export
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'admin_activity_export',
                'details' => json_encode([
                    'filter_user_id' => $request->user_id,
                    'filter_action' => $request->action,
                    'filter_date_from' => $request->date_from,
                    'filter_date_to' => $request->date_to,
                    'record_count' => count($activities),
                    'filename' => $filename
                ]),
                'ip_address' => $request->ip()
            ]);

            // Provide download
            return response()->download($path, $filename, [
                'Content-Type' => 'text/csv',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Failed to export activity logs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Failed to export activity logs: ' . $e->getMessage());
        }
    }

    /**
     * Clear old activity logs (older than X days)
     */
    public function clear(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:7|max:365',
        ]);

        try {
            $days = $request->days;
            $cutoffDate = Carbon::now()->subDays($days);

            // Get count of logs to be deleted
            $count = ActivityLog::where('created_at', '<', $cutoffDate)->count();

            if ($count === 0) {
                return back()->with('info', 'No activity logs found older than ' . $days . ' days.');
            }

            // Delete logs older than specified days
            ActivityLog::where('created_at', '<', $cutoffDate)->delete();

            // Log the clear action
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'admin_activity_clear',
                'details' => json_encode([
                    'days' => $days,
                    'cutoff_date' => $cutoffDate->format('Y-m-d'),
                    'records_deleted' => $count
                ]),
                'ip_address' => $request->ip()
            ]);

            return back()->with('success', 'Successfully cleared ' . $count . ' activity logs older than ' . $days . ' days.');

        } catch (\Exception $e) {
            Log::error('Failed to clear activity logs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Failed to clear activity logs: ' . $e->getMessage());
        }
    }

    /**
     * Get activity statistics
     */
    public function statistics()
    {
        // Activity by day (last 30 days)
        $activityByDay = ActivityLog::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Format dates for chart
        $dates = [];
        $counts = [];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(29 - $i)->format('Y-m-d');
            $dates[] = Carbon::parse($date)->format('d M');
            $counts[] = $activityByDay[$date] ?? 0;
        }

        // Activity by action type
        $activityByType = ActivityLog::select('action', DB::raw('COUNT(*) as count'))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Activity by user
        $activityByUser = ActivityLog::select('user_id', DB::raw('COUNT(*) as count'))
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Activity by IP address
        $activityByIp = ActivityLog::select('ip_address', DB::raw('COUNT(*) as count'))
            ->groupBy('ip_address')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.activities.statistics', compact(
            'dates',
            'counts',
            'activityByType',
            'activityByUser',
            'activityByIp'
        ));
    }

    public function exportPdf()
    {
        $query = ActivityLog::with('user')
            ->whereNotIn('action', ['page_view']);
    
        // Filter berdasarkan tanggal
        if (request('from_date')) {
            $query->whereDate('created_at', '>=', request('from_date'));
        }
        if (request('to_date')) {
            $query->whereDate('created_at', '<=', request('to_date'));
        }
    
        // Sorting
        $sort = request('sort', 'created_at_desc');
        if ($sort === 'created_at_asc') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
    
        $activities = $query->get();
    
        $admin = auth()->user();
    
        // Data untuk PDF
        $exportData = [
            'admin' => $admin, // BARU: Info admin yang export
            'activities' => $activities,
            'from_date' => request('from_date'),
            'to_date' => request('to_date'),
            'total_activities' => $activities->count(),
            'generated_at' => now()
        ];
    
        $pdf = Pdf::loadView('admin.activities.adminactivity_pdf', $exportData);

        $filename = 'admin_activity_export_' . now()->format('Y-m-d_H-i-s') . '.pdf';


        return $pdf->download($filename);
    }


}
