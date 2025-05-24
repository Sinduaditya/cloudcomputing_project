<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Controllers\DownloadController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadRequest;
use App\Jobs\ProcessDownloadJob;
use App\Models\Download;
use App\Models\ActivityLog;
use App\Services\DownloadService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class DownloadController extends Controller
{
    protected $downloadService;
    protected $tokenService;

    public function __construct(
        DownloadService $downloadService,
        TokenService $tokenService
    ) {
        $this->downloadService = $downloadService;
        $this->tokenService = $tokenService;
        $this->middleware('auth');
        $this->middleware('tokens')->only(['store']);
    }

    /**
     * Display a listing of the user's downloads
     */
    public function index(Request $request)
    {
        $query = auth()->user()->downloads();

        // Filter by status if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by platform if provided
        if ($request->has('platform') && !empty($request->platform)) {
            $query->where('platform', $request->platform);
        }

        // Order by latest by default
        $downloads = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get platform stats for filter dropdown
        $platforms = auth()->user()->downloads()
            ->selectRaw('platform, count(*) as count')
            ->groupBy('platform')
            ->get();

        return view('download.history', compact('downloads', 'platforms'));
    }

    /**
     * Show the form for creating a new download
     */
    public function create()
    {
        // Check token balance to show warning if needed
        $user = auth()->user();
        $lowTokens = $user->token_balance < 20;

        return view('download.create', compact('lowTokens'));
    }

    /**
     * Store a newly created download
     */
    public function store(DownloadRequest $request)
    {
        try {
            Log::info('Download request received', [
                'url' => $request->url,
                'format' => $request->format,
                'quality' => $request->quality,
                'user_id' => auth()->id()
            ]);

            $user = auth()->user();
            $url = $request->input('url');
            $format = $request->input('format', 'mp4');
            $quality = $request->input('quality', '720p');

            // Analyze URL to get metadata and determine platform
            Log::info('Analyzing URL', ['url' => $url]);
            try {
                $metadata = $this->downloadService->analyze($url);
            } catch (Exception $e) {
                Log::error('Error analyzing URL', [
                    'error' => $e->getMessage(),
                    'url' => $url,
                ]);
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Error analyzing video: ' . $e->getMessage());
            }

            // Calculate token cost
            $tokenCost = $this->downloadService->calculateTokenCost(
                $metadata['platform'],
                $metadata['duration'] ?? 30,
                $format,
                $quality
            );

            // Create download record
            $download = Download::create([
                'user_id' => $user->id,
                'url' => $url,
                'platform' => $metadata['platform'],
                'format' => $format,
                'quality' => $quality,
                'title' => $metadata['title'] ?? 'Untitled Video',
                'duration' => $metadata['duration'] ?? 0,
                'token_cost' => $tokenCost,
                'status' => 'pending',
            ]);

            // Record activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'download_create',
                'resource_id' => $download->id,
                'resource_type' => 'Download',
                'details' => json_encode([
                    'url' => $url,
                    'platform' => $metadata['platform'],
                    'format' => $format,
                    'quality' => $quality,
                    'token_cost' => $tokenCost
                ]),
                'ip_address' => $request->ip()
            ]);

            // Deduct tokens from user's balance
            $this->tokenService->deductTokens(
                $user,
                $tokenCost,
                'download_cost',
                'Download: ' . ($metadata['title'] ?? 'Video'),
                $download->id
            );

            // Dispatch job to process download in background
            ProcessDownloadJob::dispatch($download)->onQueue('downloads');

            return redirect()->route('downloads.show', $download->id)
                ->with('success', 'Download sedang diproses. Anda akan menerima notifikasi ketika selesai.');

        } catch (Exception $e) {
            Log::error('Unhandled exception in download controller', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified download
     */
    public function show($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('download.show', compact('download'));
    }

    /**
     * Get download status (for AJAX polling)
     */
    public function status($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Auto-fix storing status if needed
        if ($download->status === 'storing' && $download->storage_url) {
            $download->status = 'completed';
            $download->completed_at = $download->completed_at ?? now();
            $download->save();
        }

        return response()->json([
            'id' => $download->id,
            'status' => $download->status,
            'progress' => $download->progress ?? 0,
            'error_message' => $download->error_message,
            'storage_url' => $download->isComplete() ? $download->storage_url : null,
            'completed_at' => $download->completed_at ? $download->completed_at->format('Y-m-d H:i:s') : null,
            'direct_download_url' => $download->isComplete() ? route('downloads.file', $download->id) : null,
        ]);
    }

    /**
     * Get secure download URL
     */
    public function getDownloadUrl($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Auto-fix storing status if needed
        if ($download->status === 'storing' && $download->storage_url) {
            $download->status = 'completed';
            $download->completed_at = $download->completed_at ?? now();
            $download->save();
        }

        try {
            // For local storage
            if ($download->file_path && file_exists($download->file_path)) {
                $url = route('downloads.file', $download->id);
                return response()->json(['url' => $url]);
            }

            // For direct storage URLs
            if ($download->storage_url) {
                return response()->json(['url' => $download->storage_url]);
            }

            return response()->json(['error' => 'File not found'], 404);

        } catch (Exception $e) {
            Log::error('Error generating download URL', [
                'download_id' => $download->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Failed to generate download URL'], 500);
        }
    }

    /**
     * Download file directly (for local storage)
     */
    public function downloadFile($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Auto-fix storing status if needed
        if ($download->status === 'storing' && $download->storage_url) {
            $download->status = 'completed';
            $download->completed_at = $download->completed_at ?? now();
            $download->save();
        }

        if (!$download->file_path || !file_exists($download->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan pada penyimpanan lokal');
        }

        // Log download access
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'download_access',
            'resource_id' => $download->id,
            'resource_type' => 'Download',
            'ip_address' => request()->ip(),
        ]);

        $filename = $download->title ?? basename($download->file_path);
        $extension = $download->format ?? pathinfo($download->file_path, PATHINFO_EXTENSION);

        // Use sanitized filename with proper extension
        $safeFilename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename) . '.' . $extension;

        return response()->download($download->file_path, $safeFilename);
    }

    /**
     * Retry a failed download
     */
    public function retry($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['failed', 'cancelled'])
            ->firstOrFail();

        // Update download status
        $download->status = 'pending';
        $download->error_message = null;
        $download->save();

        // Log retry attempt
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'download_retry',
            'resource_id' => $download->id,
            'resource_type' => 'Download',
            'ip_address' => request()->ip(),
        ]);

        // Dispatch job to process download
        ProcessDownloadJob::dispatch($download);

        return redirect()->route('downloads.show', $download->id)
            ->with('success', 'Download sedang diproses ulang.');
    }

    /**
     * Cancel a pending download
     */
    public function cancel($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'downloading', 'uploading'])
            ->firstOrFail();

        // Update download status
        $download->status = 'cancelled';
        $download->save();

        // Refund tokens
        $this->tokenService->refundTokens(
            auth()->user(),
            $download->token_cost,
            'Refund for cancelled download: ' . $download->title,
            $download->id
        );

        // Log cancellation
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'download_cancelled',
            'resource_id' => $download->id,
            'resource_type' => 'Download',
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('downloads.index')
            ->with('success', 'Download dibatalkan dan token dikembalikan.');
    }
}
