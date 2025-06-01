<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Services\DownloadService.php

namespace App\Services;

use App\Models\Download;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class DownloadService
{
    /**
     * Service instances for each platform
     */
    protected $youtubeService;
    protected $tiktokService;
    protected $instagramService;
    protected $cloudinaryService;

    /**
     * Constructor
     */
    public function __construct(
        YoutubeService $youtubeService,
        TiktokService $tiktokService,
        InstagramService $instagramService,
        CloudinaryService $cloudinaryService
    ) {
        $this->youtubeService = $youtubeService;
        $this->tiktokService = $tiktokService;
        $this->instagramService = $instagramService;
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Analyze URL to determine platform, metadata, and token cost
     *
     * @param string $url
     * @return array
     */
    public function analyze($url)
    {
        try {
            // Determine the platform from URL
            $platform = $this->determinePlatform($url);

            // Get metadata for the video
            $metadata = $this->getVideoMetadata($url, $platform);

            // Add platform to metadata
            $metadata['platform'] = $platform;

            return $metadata;
        } catch (Exception $e) {
            Log::error('Error analyzing URL', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Determine the platform from URL
     *
     * @param string $url
     * @return string
     */
    public function determinePlatform($url)
    {
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            return 'youtube';
        } elseif (strpos($url, 'tiktok.com') !== false || strpos($url, 'vt.tiktok.com') !== false) {
            return 'tiktok';
        } elseif (strpos($url, 'instagram.com') !== false) {
            return 'instagram';
        }

        throw new Exception('Unsupported platform. We only support YouTube, TikTok, and Instagram.');
    }

    /**
     * Get video metadata based on platform
     *
     * @param string $url
     * @param string $platform
     * @return array
     */
    public function getVideoMetadata($url, $platform)
    {
        try {
            switch ($platform) {
                case 'youtube':
                    return $this->youtubeService->getMetadata($url);
                case 'tiktok':
                    return $this->tiktokService->getMetadata($url);
                case 'instagram':
                    return $this->instagramService->getMetadata($url);
                default:
                    throw new Exception('Unsupported platform');
            }
        } catch (Exception $e) {
            Log::error('Error getting video metadata', [
                'url' => $url,
                'platform' => $platform,
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Could not retrieve video information: ' . $e->getMessage());
        }
    }

    /**
     * Calculate metered usage in MB for a download
     *
     * @param int $fileSizeBytes
     * @return float
     */
    public function calculateUsageMB($fileSizeBytes)
    {
        return round($fileSizeBytes / (1024 * 1024), 2);
    }

    /**
     * Calculate token cost based on MB and pricing model
     *
     * @param float $usageMB
     * @return int
     */
    public function calculateTokenCostByMB($usageMB)
    {
        $mbPerToken = config('download.mb_per_token', 10);
        $minTokens = config('download.min_tokens', 1);

        $tokens = ceil($usageMB / $mbPerToken);
        return max($tokens, $minTokens);
    }

    /**
     * Calculate token cost based on platform, duration, format and quality
     *
     * @param string $platform
     * @param int $duration
     * @param string $format
     * @param string $quality
     * @return int
     */
    public function calculateTokenCost($platform, $duration, $format, $quality)
    {
        // Base cost by platform
        $baseCosts = [
            'youtube' => 10,
            'tiktok' => 5,
            'instagram' => 8,
        ];

        $baseTokens = $baseCosts[$platform] ?? 10;

        // Adjust for duration (1 token per 30 seconds)
        $durationTokens = ceil($duration / 30);

        // Adjust for format and quality
        $formatMultiplier = $format === 'mp3' ? 0.5 : 1;

        $qualityMultiplier = 1;
        if ($format === 'mp4') {
            switch ($quality) {
                case '1080p':
                    $qualityMultiplier = 2;
                    break;
                case '720p':
                    $qualityMultiplier = 1.5;
                    break;
                case '480p':
                    $qualityMultiplier = 1;
                    break;
                case '360p':
                    $qualityMultiplier = 0.8;
                    break;
                default:
                    $qualityMultiplier = 1;
            }
        }

        // Calculate final cost
        $totalTokens = ceil(($baseTokens + $durationTokens) * $formatMultiplier * $qualityMultiplier);

        // Ensure minimum cost
        return max(5, $totalTokens);
    }

    /**
     * Process download
     *
     * @param Download $download
     * @return array
     */
    public function processDownload(Download $download)
    {
        try {
            $tempDir = storage_path('app/downloads/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0777, true);
            }

            Log::info('Processing download', [
                'download_id' => $download->id,
                'platform' => $download->platform,
                'url' => $download->url,
                'format' => $download->format,
                'quality' => $download->quality,
            ]);

            $service = $this->getServiceForPlatform($download->platform);

            // Update download status
            $download->status = 'downloading';
            $download->save();

            // Download the file locally first
            $localFilePath = $service->download($download->url, $download->format, $download->quality, $tempDir);

            if (!file_exists($localFilePath)) {
                throw new Exception("Downloaded file not found at path: {$localFilePath}");
            }

            // Get file size
            $fileSize = filesize($localFilePath);
            $download->file_size = $fileSize;
            $download->save();

            // Decide storage strategy based on file size or config
            $useCloudinary = $this->shouldUseCloudinary($fileSize);

            if ($useCloudinary) {
                $result = $this->saveFileToCloudinary($download, $localFilePath);
            } else {
                $result = $this->saveFileToPublicStorage($download, $localFilePath);
            }

            Log::info('Download completed successfully', [
                'download_id' => $download->id,
                'file_size' => $fileSize,
                'storage_provider' => $useCloudinary ? 'cloudinary' : 'local',
                'url' => $result['url']
            ]);

            return [
                'status' => 'success',
                'file_size' => $fileSize,
                'format' => $download->format,
                'title' => $download->title,
                'url' => $result['url'],
                'storage_provider' => $useCloudinary ? 'cloudinary' : 'local'
            ];

        } catch (Exception $e) {
            Log::error('Download processing failed', [
                'error' => $e->getMessage(),
                'download_id' => $download->id,
                'trace' => $e->getTraceAsString(),
            ]);

            $download->status = 'failed';
            $download->error_message = $e->getMessage();
            $download->save();

            throw $e;
        }
    }

    protected function shouldUseCloudinary($fileSize)
    {
        // Use Cloudinary for files larger than 50MB or if configured to always use it
        $cloudinaryThreshold = config('download.cloudinary_threshold_mb', 50) * 1024 * 1024;
        $alwaysUseCloudinary = config('download.always_use_cloudinary', false);

        return $alwaysUseCloudinary || $fileSize > $cloudinaryThreshold;
    }

     protected function saveFileToCloudinary(Download $download, $tempFilePath)
    {
        try {
            Log::info('Uploading to Cloudinary', [
                'download_id' => $download->id,
                'tempFilePath' => $tempFilePath,
            ]);

            // Update status to uploading
            $download->status = 'uploading';
            $download->save();

            // Upload to Cloudinary
            $uploadResult = $this->cloudinaryService->uploadFile($tempFilePath, [
                'public_id' => "download_{$download->id}_{$download->user_id}",
                'tags' => ['download', "user_{$download->user_id}", $download->platform]
            ]);

            if (!$uploadResult['success']) {
                throw new Exception("Cloudinary upload failed: " . $uploadResult['error']);
            }

            // Update download record
            $download->update([
                'status' => 'completed',
                'cloudinary_public_id' => $uploadResult['public_id'],
                'cloudinary_url' => $uploadResult['secure_url'],
                'storage_url' => $uploadResult['secure_url'],
                'storage_provider' => 'cloudinary',
                'completed_at' => now()
            ]);

            // Delete local temp file
            @unlink($tempFilePath);

            Log::info('File uploaded to Cloudinary successfully', [
                'download_id' => $download->id,
                'cloudinary_url' => $uploadResult['secure_url'],
                'file_size' => $uploadResult['bytes']
            ]);

            return [
                'url' => $uploadResult['secure_url'],
                'provider' => 'cloudinary'
            ];

        } catch (Exception $e) {
            Log::error('Error uploading to Cloudinary', [
                'download_id' => $download->id,
                'error' => $e->getMessage(),
            ]);

            // Fallback to local storage
            Log::info('Falling back to local storage', ['download_id' => $download->id]);
            return $this->saveFileToPublicStorage($download, $tempFilePath);
        }
    }

    /**
     * Get download service for a specific platform
     *
     * @param string $platform
     * @return YoutubeService|TiktokService|InstagramService
     */
    public function getServiceForPlatform($platform)
    {
        switch ($platform) {
            case 'youtube':
                return $this->youtubeService;
            case 'tiktok':
                return $this->tiktokService;
            case 'instagram':
                return $this->instagramService;
            default:
                throw new Exception('Unsupported platform: ' . $platform);
        }
    }

    /**
     * Save file directly to public storage (no storing status)
     *
     * @param Download $download
     * @param string $tempFilePath
     * @return array with path and url
     */
    protected function saveFileToPublicStorage(Download $download, $tempFilePath)
    {
        try {
            Log::info('Saving file to public storage', [
                'download_id' => $download->id,
                'tempFilePath' => $tempFilePath,
            ]);

            // Determine file extension
            $extension = pathinfo($tempFilePath, PATHINFO_EXTENSION);
            if (empty($extension)) {
                $extension = $download->format; // Use format as fallback
            }

            // Create a simple filename
            $filename = 'download_' . $download->id . '_' . time() . '.' . $extension;

            // Store in public disk
            $storagePath = 'downloads/' . $download->user_id;

            // Create directory if it doesn't exist
            if (!Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->makeDirectory($storagePath);
            }

            // Full relative path within the public disk
            $relativePath = $storagePath . '/' . $filename;

            // Copy file to storage
            $fileContents = file_get_contents($tempFilePath);
            Storage::disk('public')->put($relativePath, $fileContents);

            // Verify storage was successful
            if (!Storage::disk('public')->exists($relativePath)) {
                throw new Exception("Failed to store file in public storage");
            }

            // Get the full system path for the file
            $fullStoragePath = storage_path('app/public/' . $relativePath);

            // Get public URL
            $storageUrl = asset('storage/' . $relativePath);

            Log::info('File saved successfully', [
                'relativePath' => $relativePath,
                'fullPath' => $fullStoragePath,
                'publicUrl' => $storageUrl
            ]);

            // Update download record - directly to completed status
            $download->file_path = $fullStoragePath;
            $download->storage_path = 'public/' . $relativePath;
            $download->storage_url = $storageUrl;
            $download->storage_provider = 'local';
            $download->status = 'completed';
            $download->completed_at = now();
            $download->save();

            // Delete temporary file
            try {
                if (file_exists($tempFilePath)) {
                    unlink($tempFilePath);
                }
            } catch (Exception $e) {
                // Just log but don't fail if we can't delete temp file
                Log::warning("Couldn't delete temp file but continuing", [
                    'tempFile' => $tempFilePath,
                    'error' => $e->getMessage()
                ]);
            }

            return [
                'path' => $relativePath,
                'url' => $storageUrl,
            ];
        } catch (Exception $e) {
            Log::error('Error saving file to public storage', [
                'download_id' => $download->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Check if file exists despite error
            if (!empty($download->storage_url)) {
                Log::info('File seems to exist despite error, using existing URL', [
                    'download_id' => $download->id,
                    'storage_url' => $download->storage_url
                ]);

                // If we have a storage URL, mark as completed
                $download->status = 'completed';
                $download->completed_at = now();
                $download->save();

                return [
                    'path' => $download->storage_path ?? '',
                    'url' => $download->storage_url,
                ];
            }

            $download->status = 'failed';
            $download->error_message = 'Failed to store file: ' . $e->getMessage();
            $download->save();

            throw $e;
        }
    }
}
