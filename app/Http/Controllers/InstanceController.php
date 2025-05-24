<?php


namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\ActivityLog;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstanceController extends Controller
{
    protected $cloudinaryService;

    /**
     * Create a new controller instance
     *
     * @param CloudinaryService $cloudinaryService
     */
    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
        $this->middleware('auth');
    }

    /**
     * Display download instance actions page
     */
    public function show(Download $download)
    {
        // Check if the user owns this download
        if ($download->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return redirect()->route('downloads.index')
                ->with('error', 'You do not have permission to access this download.');
        }

        return view('instance.actions', compact('download'));
    }

    /**
     * Get a secure download URL
     */
    public function getDownloadUrl(Download $download)
    {
        // Check if the user owns this download
        if ($download->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Check if download is completed
        if ($download->status !== 'completed') {
            return response()->json(['error' => 'Download is not complete'], 400);
        }

        try {
            // Log this access
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'download_access',
                'resource_id' => $download->id,
                'resource_type' => 'Download',
                'ip_address' => request()->ip(),
            ]);

            // If using Cloudinary, get a signed URL with short expiry
            if ($download->cloudinary_id) {
                $downloadUrl = $this->cloudinaryService->getSignedUrl(
                    $download->cloudinary_id,
                    $download->format === 'mp3' ? 'video' : 'video', // Cloudinary uses video type for both
                    3600 // 1 hour expiry
                );

                return response()->json([
                    'url' => $downloadUrl,
                    'expires' => now()->addHour()->toIso8601String(),
                ]);
            }

            // If using local storage
            if ($download->storage_url) {
                return response()->json([
                    'url' => $download->storage_url,
                    'expires' => null, // No expiry for direct URLs
                ]);
            }

            return response()->json(['error' => 'No download URL available'], 404);

        } catch (\Exception $e) {
            Log::error('Error generating download URL', [
                'download_id' => $download->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Failed to generate download URL: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Request deletion of a download
     */
    public function requestDeletion(Download $download)
    {
        // Check if the user owns this download
        if ($download->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return back()->with('error', 'You do not have permission to delete this download.');
        }

        try {
            // If using Cloudinary, delete the file
            if ($download->cloudinary_id) {
                $this->cloudinaryService->deleteFile(
                    $download->cloudinary_id,
                    $download->format === 'mp3' ? 'video' : 'video'
                );
            }

            // Update download status
            $download->status = 'deleted';
            $download->storage_url = null;
            $download->cloudinary_id = null;
            $download->save();

            // Log the deletion
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'download_deleted',
                'resource_id' => $download->id,
                'resource_type' => 'Download',
                'ip_address' => request()->ip(),
            ]);

            return redirect()->route('downloads.index')
                ->with('success', 'Download has been deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting download', [
                'download_id' => $download->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to delete download: ' . $e->getMessage());
        }
    }

    /**
     * Get download information for sharing
     */
    public function getShareInfo(Download $download)
    {
        // Check if the user owns this download
        if ($download->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Check if download is completed
        if ($download->status !== 'completed') {
            return response()->json(['error' => 'Download is not complete'], 400);
        }

        // Get thumbnail if available (for videos)
        $thumbnail = null;
        if ($download->cloudinary_id && $download->format === 'mp4') {
            try {
                $thumbnail = $this->cloudinaryService->getVideoThumbnail($download->cloudinary_id);
            } catch (\Exception $e) {
                Log::warning('Could not generate thumbnail', [
                    'download_id' => $download->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'id' => $download->id,
            'title' => $download->title,
            'format' => $download->format,
            'duration' => $download->duration,
            'thumbnail' => $thumbnail,
            'platform' => $download->platform,
            'created_at' => $download->created_at->toIso8601String(),
            'file_size' => $download->file_size,
            'human_file_size' => $download->human_file_size,
        ]);
    }
}
