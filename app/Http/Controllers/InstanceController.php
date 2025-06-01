<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Controllers\InstanceController.php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\ActivityLog;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstanceController extends Controller
{
    protected $cloudinaryService;

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
        if ($download->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        if ($download->status !== 'completed') {
            return response()->json(['error' => 'Download is not complete'], 400);
        }

        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'download_access',
                'resource_id' => $download->id,
                'resource_type' => 'Download',
                'ip_address' => request()->ip(),
            ]);

            // If using Cloudinary
            if ($download->isStoredInCloudinary()) {
                return response()->json([
                    'url' => $download->cloudinary_url,
                    'expires' => null, // Cloudinary URLs don't expire by default
                    'provider' => 'cloudinary'
                ]);
            }

            // If using local storage
            if ($download->storage_url) {
                return response()->json([
                    'url' => $download->storage_url,
                    'expires' => null,
                    'provider' => 'local'
                ]);
            }

            return response()->json(['error' => 'No download URL available'], 404);

        } catch (\Exception $e) {
            Log::error('Error generating download URL', [
                'download_id' => $download->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Failed to generate download URL'], 500);
        }
    }

    /**
     * Request deletion of a download
     */
    public function requestDeletion(Download $download)
    {
        if ($download->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return back()->with('error', 'You do not have permission to delete this download.');
        }

        try {
            // If using Cloudinary, delete the file from cloud
            if ($download->isStoredInCloudinary()) {
                $deleteResult = $this->cloudinaryService->deleteFile(
                    $download->cloudinary_public_id,
                    'video' // Use video type for both video and audio
                );

                if (!$deleteResult['success']) {
                    Log::warning('Failed to delete from Cloudinary', [
                        'download_id' => $download->id,
                        'error' => $deleteResult['error']
                    ]);
                }
            }

            // If using local storage, delete local file
            if ($download->file_path && file_exists($download->file_path)) {
                @unlink($download->file_path);
            }

            // Update download status
            $download->update([
                'status' => 'deleted',
                'storage_url' => null,
                'cloudinary_public_id' => null,
                'cloudinary_url' => null,
                'file_path' => null
            ]);

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
        if ($download->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        if ($download->status !== 'completed') {
            return response()->json(['error' => 'Download is not complete'], 400);
        }

        $thumbnail = null;
        if ($download->isStoredInCloudinary() && $download->format === 'mp4') {
            try {
                $thumbnail = $this->cloudinaryService->getVideoThumbnail($download->cloudinary_public_id);
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
            'storage_provider' => $download->storage_provider,
        ]);
    }
}
