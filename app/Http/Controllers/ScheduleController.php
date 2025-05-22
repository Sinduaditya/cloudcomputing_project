<?php


namespace App\Http\Controllers;

use App\Models\ScheduledTask;
use App\Models\ActivityLog;
use App\Services\DownloadService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    protected $downloadService;
    protected $tokenService;

    public function __construct(DownloadService $downloadService, TokenService $tokenService)
    {
        $this->downloadService = $downloadService;
        $this->tokenService = $tokenService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of user's scheduled downloads
     */
    public function index()
    {
        $schedules = auth()->user()->scheduledTasks()
            ->orderBy('scheduled_for', 'desc')
            ->paginate(10);

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

        return view('schedule.create', compact('lowTokens'));
    }

    /**
     * Store a newly created scheduled download
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'format' => 'required|in:mp4,mp3',
            'quality' => 'required_if:format,mp4|in:1080p,720p,480p,360p',
            'scheduled_for' => 'required|date|after:+4 minutes'
        ], [
            'url.required' => 'URL video tidak boleh kosong',
            'url.url' => 'URL video tidak valid',
            'format.required' => 'Format tidak boleh kosong',
            'format.in' => 'Format harus mp4 atau mp3',
            'quality.required_if' => 'Kualitas harus dipilih untuk format video',
            'scheduled_for.required' => 'Waktu download tidak boleh kosong',
            'scheduled_for.date' => 'Format waktu download tidak valid',
            'scheduled_for.after' => 'Waktu download minimal 5 menit dari sekarang'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = auth()->user();

            // Validate URL and determine platform
            $url = $request->url;
            $platform = $this->downloadService->determinePlatform($url);

            if (!$platform) {
                return back()->withErrors(['url' => 'URL tidak valid atau platform tidak didukung. Kami mendukung YouTube, Instagram, TikTok, dan Facebook.'])
                    ->withInput();
            }

            // Validasi URL untuk platform tertentu
            try {
                // Jika memiliki method validateUrl di DownloadService
                if (method_exists($this->downloadService, 'validateUrl')) {
                    $this->downloadService->validateUrl($url, $platform);
                }
            } catch (\Exception $e) {
                return back()->withErrors(['url' => 'URL tidak valid: ' . $e->getMessage()])
                    ->withInput();
            }

            // Get video metadata untuk estimasi token jika memungkinkan
            $estimatedTokens = 0;
            if (method_exists($this->downloadService, 'getVideoMetadata') && method_exists($this->downloadService, 'calculateTokenCost')) {
                try {
                    // Optional: Pre-check token estimate
                    $metadata = $this->downloadService->getVideoMetadata($url, $platform);
                    $estimatedTokens = $this->downloadService->calculateTokenCost(
                        $platform,
                        $metadata['duration'] ?? 0,
                        $request->format,
                        $request->quality ?? '720p'
                    );

                    // Log metadata fetched successfully
                    Log::info("Successfully fetched metadata for scheduled download", [
                        'url' => $url,
                        'platform' => $platform,
                        'title' => $metadata['title'] ?? 'Unknown',
                        'duration' => $metadata['duration'] ?? 0,
                        'estimated_tokens' => $estimatedTokens
                    ]);
                } catch (\Exception $e) {
                    // Just log but don't stop the process
                    Log::warning("Could not estimate tokens for scheduled download: " . $e->getMessage(), [
                        'url' => $url,
                        'platform' => $platform
                    ]);
                }
            }

            // Create scheduled task
            $schedule = ScheduledTask::create([
                'user_id' => $user->id,
                'url' => $url,
                'format' => $request->format,
                'quality' => $request->format === 'mp3' ? null : $request->quality,
                'scheduled_for' => $request->scheduled_for,
                'status' => 'scheduled'
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
                    'scheduled_for' => $request->scheduled_for,
                    'estimated_tokens' => $estimatedTokens
                ]),
                'ip_address' => $request->ip()
            ]);

            // Format date for display
            $dateFormatted = date('d M Y H:i', strtotime($request->scheduled_for));

            $successMessage = "Download berhasil dijadwalkan untuk {$dateFormatted}";

            // Optional: Add token estimate to success message
            if ($estimatedTokens > 0) {
                $successMessage .= " (estimasi: {$estimatedTokens} token)";
            }

            return redirect()->route('schedules.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error("Schedule creation error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return back()->withErrors(['url' => 'Gagal menjadwalkan download: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Cancel a scheduled download
     */
    public function destroy($id)
    {
        try {
            $scheduledTask = ScheduledTask::findOrFail($id);

            // Check if user owns this scheduled task
            if ($scheduledTask->user_id != auth()->id()) {
                return redirect()->route('schedules.index')
                    ->with('error', 'Anda tidak memiliki akses untuk membatalkan jadwal ini.');
            }

            // Check if it can be cancelled (only if still scheduled and in the future)
            if ($scheduledTask->status !== 'scheduled') {
                return redirect()->route('schedules.index')
                    ->with('error', 'Jadwal tidak dapat dibatalkan karena sudah diproses.');
            }

            if ($scheduledTask->scheduled_for->isPast()) {
                return redirect()->route('schedules.index')
                    ->with('error', 'Jadwal tidak dapat dibatalkan karena waktunya telah lewat.');
            }

            // Log activity before deleting
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'schedule_cancelled',
                'resource_id' => $scheduledTask->id,
                'resource_type' => 'ScheduledTask',
                'details' => json_encode([
                    'url' => $scheduledTask->url,
                    'platform' => $scheduledTask->platform,
                    'format' => $scheduledTask->format,
                    'scheduled_for' => $scheduledTask->scheduled_for
                ]),
                'ip_address' => request()->ip()
            ]);

            // Delete the scheduled task
            $scheduledTask->delete();

            return redirect()->route('schedules.index')
                ->with('success', 'Jadwal download berhasil dibatalkan.');

        } catch (\Exception $e) {
            Log::error("Error cancelling scheduled task: " . $e->getMessage());

            return redirect()->route('schedules.index')
                ->with('error', 'Gagal membatalkan jadwal: ' . $e->getMessage());
        }
    }
}
