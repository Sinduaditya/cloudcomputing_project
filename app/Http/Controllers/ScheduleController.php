<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\ScheduledTask;
use App\Models\ActivityLog;
use App\Services\DownloadService;
use App\Services\TokenService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    protected $downloadService;
    protected $tokenService;

    public function __construct(DownloadService $downloadService, TokenService $tokenService)
    {
        $this->downloadService = $downloadService;
        $this->tokenService = $tokenService;
        $this->middleware('auth');
        $this->middleware('tokens')->only(['store']);
    }

    /**
     * Display a listing of user's scheduled downloads
     */
    public function index(Request $request)
    {
        $query = auth()->user()->scheduledTasks();

        // Filter by status if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by date range if provided
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->where('scheduled_for', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->where('scheduled_for', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Default order by scheduled_for (closest first)
        $schedules = $query->orderBy('scheduled_for', 'asc')->paginate(10);

        return view('schedule.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new scheduled download
     */
    public function create()
    {
        // Check token balance to show warning if needed
        $user = auth()->user();
        $lowTokens = $user->token_balance < 20;

        // Get suggested times (next 24 hours in 2-hour increments)
        $suggestedTimes = [];
        $startTime = Carbon::now()->addMinutes(30)->startOfHour();
        for ($i = 0; $i < 12; $i++) {
            $time = $startTime->copy()->addHours($i * 2);
            $suggestedTimes[] = $time->format('Y-m-d H:i:s');
        }

        return view('schedule.create', compact('lowTokens', 'suggestedTimes'));
    }

    /**
     * Store a newly created scheduled download
     */
    public function store(ScheduleRequest $request)
    {
        try {
            $user = auth()->user();

            // Analyze URL to get metadata and determine platform
            $url = $request->url;
            try {
                $metadata = $this->downloadService->analyze($url);
                $platform = $metadata['platform'];
            } catch (\Exception $e) {
                Log::warning('Could not analyze URL for scheduled download: ' . $e->getMessage(), [
                    'url' => $url,
                ]);

                // Try to determine platform from URL
                $platform = $this->downloadService->determinePlatform($url);

                if (!$platform) {
                    return back()
                        ->withErrors(['url' => 'URL tidak valid atau platform tidak didukung. Kami mendukung YouTube, Instagram, TikTok, dan Facebook.'])
                        ->withInput();
                }

                $metadata = [
                    'platform' => $platform,
                    'title' => $this->getDefaultTitle($platform),
                    'duration' => 0,
                ];
            }

            // Estimate token cost
            $estimatedTokens = 0;
            try {
                $estimatedTokens = $this->downloadService->calculateTokenCost($platform, $metadata['duration'] ?? 0, $request->format, $request->quality ?? '720p');

                Log::info('Estimated tokens for scheduled download', [
                    'url' => $url,
                    'platform' => $platform,
                    'title' => $metadata['title'] ?? 'Unknown',
                    'duration' => $metadata['duration'] ?? 0,
                    'estimated_tokens' => $estimatedTokens,
                ]);
            } catch (\Exception $e) {
                Log::warning('Could not estimate tokens for scheduled download: ' . $e->getMessage(), [
                    'url' => $url,
                    'platform' => $platform,
                ]);
                // Set minimum token estimate
                $estimatedTokens = 5;
            }

            // Create scheduled task
            $schedule = ScheduledTask::create([
                'user_id' => $user->id,
                'url' => $url,
                'format' => $request->format,
                'quality' => $request->format === 'mp3' ? null : $request->quality,
                'platform' => $platform,
                'scheduled_for' => $request->scheduled_for,
                'status' => ScheduledTask::STATUS_SCHEDULED,
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'schedule_created',
                'resource_id' => $schedule->id,
                'resource_type' => 'ScheduledTask',
                'details' => json_encode([
                    'platform' => $platform,
                    'format' => $request->format,
                    'quality' => $request->quality ?? null,
                    'scheduled_for' => $request->scheduled_for,
                    'estimated_tokens' => $estimatedTokens,
                    'title' => $metadata['title'] ?? 'Unknown',
                ]),
                'ip_address' => $request->ip(),
            ]);

            // Format date for display
            $dateFormatted = Carbon::parse($request->scheduled_for)->format('d M Y H:i');
            $successMessage = "Download berhasil dijadwalkan untuk {$dateFormatted}";

            // Add token estimate to success message
            if ($estimatedTokens > 0) {
                $successMessage .= " (estimasi: {$estimatedTokens} token)";
            }

            return redirect()->route('schedules.index')->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Schedule creation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            return back()
                ->withErrors(['url' => 'Gagal menjadwalkan download: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified scheduled task
     */
    public function show($id)
    {
        $schedule = ScheduledTask::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Get related download if exists
        $download = null;
        if ($schedule->download_id) {
            $download = $schedule->download;
        }

        return view('schedule.show', compact('schedule', 'download'));
    }

    /**
     * Show form to reschedule a task
     */
    public function edit($id)
    {
        $schedule = ScheduledTask::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', ScheduledTask::STATUS_SCHEDULED)
            ->firstOrFail();

        // Get suggested times (next 24 hours in 2-hour increments)
        $suggestedTimes = [];
        $startTime = Carbon::now()->addMinutes(30)->startOfHour();
        for ($i = 0; $i < 12; $i++) {
            $time = $startTime->copy()->addHours($i * 2);
            $suggestedTimes[] = $time->format('Y-m-d H:i:s');
        }

        return view('schedule.edit', compact('schedule', 'suggestedTimes'));
    }

    /**
     * Update a scheduled task (reschedule)
     */
    public function update(ScheduleRequest $request, $id)
    {
        try {
            $schedule = ScheduledTask::where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', ScheduledTask::STATUS_SCHEDULED)
                ->firstOrFail();

            // Check if we're only updating the schedule time
            if ($request->has('scheduled_for') && $request->scheduled_for != $schedule->scheduled_for && !$schedule->scheduled_for->isPast()) {
                // Update scheduled time
                $oldTime = $schedule->scheduled_for->format('d M Y H:i');
                $schedule->scheduled_for = $request->scheduled_for;
                $schedule->save();

                // Log activity
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'schedule_updated',
                    'resource_id' => $schedule->id,
                    'resource_type' => 'ScheduledTask',
                    'details' => json_encode([
                        'old_time' => $oldTime,
                        'new_time' => Carbon::parse($request->scheduled_for)->format('d M Y H:i'),
                    ]),
                    'ip_address' => $request->ip(),
                ]);

                return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
            }

            return redirect()->route('schedules.index')->with('error', 'Tidak dapat mengubah jadwal yang sudah lewat waktu atau sedang diproses.');
        } catch (\Exception $e) {
            Log::error('Schedule update error: ' . $e->getMessage(), [
                'schedule_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['scheduled_for' => 'Gagal mengubah jadwal: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancel a scheduled download
     */
    public function destroy($id)
    {
        try {
            $scheduledTask = ScheduledTask::where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', ScheduledTask::STATUS_SCHEDULED)
                ->firstOrFail();

            // Check if it can be cancelled (must be in the future)
            if ($scheduledTask->scheduled_for->isPast()) {
                return redirect()->route('schedules.index')->with('error', 'Jadwal tidak dapat dibatalkan karena waktunya telah lewat.');
            }

            // Log activity before updating
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'schedule_cancelled',
                'resource_id' => $scheduledTask->id,
                'resource_type' => 'ScheduledTask',
                'details' => json_encode([
                    'url' => $scheduledTask->url,
                    'platform' => $scheduledTask->platform,
                    'format' => $scheduledTask->format,
                    'scheduled_for' => $scheduledTask->scheduled_for->format('Y-m-d H:i:s'),
                ]),
                'ip_address' => request()->ip(),
            ]);

            // Update status rather than delete for better record keeping
            $scheduledTask->status = ScheduledTask::STATUS_CANCELLED;
            $scheduledTask->save();

            return redirect()->route('schedules.index')->with('success', 'Jadwal download berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Error cancelling scheduled task: ' . $e->getMessage());

            return redirect()
                ->route('schedules.index')
                ->with('error', 'Gagal membatalkan jadwal: ' . $e->getMessage());
        }
    }

    public function pause($id)
    {
        $schedule = ScheduledTask::findOrFail($id);
        $schedule->status = 'paused';
        $schedule->save();

        return redirect()->back()->with('success', 'Download paused');
    }

    public function resume($id)
    {
        $schedule = ScheduledTask::findOrFail($id);
        if ($schedule->status === 'paused') {
            $schedule->status = 'pending';
            $schedule->save();
            return redirect()->back()->with('success', 'Download resumed');
        }
        return redirect()->back()->with('error', 'Download not paused');
    }

    /**
     * Get default title based on platform
     */
    private function getDefaultTitle($platform)
    {
        $titles = [
            'youtube' => 'YouTube Video',
            'tiktok' => 'TikTok Video',
            'instagram' => 'Instagram Media',
            'facebook' => 'Facebook Video',
        ];

        return $titles[$platform] ?? 'Media Content';
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('schedules', []);
        if (empty($ids)) {
            return redirect()->route('schedules.index')->with('error', 'No schedules selected.');
        }

        $deleted = 0;
        foreach ($ids as $id) {
            $schedule = \App\Models\ScheduledTask::where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', \App\Models\ScheduledTask::STATUS_SCHEDULED)
                ->first();

            if ($schedule) {
                // Log activity before updating
                \App\Models\ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'schedule_bulk_deleted',
                    'resource_id' => $schedule->id,
                    'resource_type' => 'ScheduledTask',
                    'details' => json_encode([
                        'url' => $schedule->url,
                        'platform' => $schedule->platform,
                        'format' => $schedule->format,
                        'scheduled_for' => $schedule->scheduled_for->format('Y-m-d H:i:s'),
                    ]),
                    'ip_address' => $request->ip(),
                ]);
                // Mark as cancelled instead of deleting
                $schedule->status = \App\Models\ScheduledTask::STATUS_CANCELLED;
                $schedule->save();
                $deleted++;
            }
        }

        return redirect()
            ->route('schedules.index')
            ->with('success', "$deleted schedule(s) cancelled successfully.");
    }
}
