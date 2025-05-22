<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Services\DownloadService.php

namespace App\Services;

use App\Models\Download;
use Cloudinary\Cloudinary;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
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
    protected $cloudinary;

    /**
     * Constructor
     */
    public function __construct(YoutubeService $youtubeService, TiktokService $tiktokService, InstagramService $instagramService, Cloudinary $cloudinary)
    {
        $this->youtubeService = $youtubeService;
        $this->tiktokService = $tiktokService;
        $this->instagramService = $instagramService;
        $this->cloudinary = $cloudinary;
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
     * Process download and upload to cloud storage
     *
     * @param Download $download
     * @return array
     */

    public function processDownload(Download $download)
    {
        try {
            // Create temp directory if it doesn't exist
            $tempDir = config('download.temp_path', storage_path('app/downloads/temp'));
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

            // Get service based on platform
            switch ($download->platform) {
                case 'youtube':
                    $service = $this->youtubeService;
                    break;
                case 'tiktok':
                    $service = $this->tiktokService;
                    break;
                case 'instagram':
                    $service = $this->instagramService;
                    break;
                default:
                    throw new Exception('Unsupported platform: ' . $download->platform);
            }

            // Update download status
            $download->status = 'downloading';
            $download->save();

            // Download the file locally
            $localFilePath = $service->download($download->url, $download->format, $download->quality, $tempDir);

            if (!file_exists($localFilePath)) {
                throw new Exception("Downloaded file not found at path: {$localFilePath}");
            }

            // Get file size
            $fileSize = filesize($localFilePath);
            $download->file_size = $fileSize;
            $download->status = 'completed';
            $download->file_path = $localFilePath; // Simpan path lokal
            $download->completed_at = now();
            $download->save();

            Log::info('Download completed successfully', [
                'download_id' => $download->id,
                'file_path' => $localFilePath,
                'file_size' => $fileSize,
            ]);

            return [
                'status' => 'success',
                'file_path' => $localFilePath,
                'file_size' => $fileSize,
                'format' => $download->format,
                'title' => $download->title,
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
     * Execute yt-dlp command with fallbacks and better error handling
     * Helper method for platform services
     *
     * @param array $command
     * @param string $workingDir
     * @param int $timeout
     * @return string
     */
    public function executeYtDlpCommand(array $command, string $workingDir, int $timeout = 60)
    {
        // Try with default config
        $ytdlpPath = config('download.ytdlp_path', storage_path('app/bin/yt-dlp.exe'));

        // Log debug info
        Log::debug('Executing yt-dlp command', [
            'command' => $ytdlpPath . ' ' . implode(' ', $command),
            'working_dir' => $workingDir,
        ]);

        try {
            // First attempt - use configured path
            if (strpos($ytdlpPath, ' ') !== false) {
                $parts = explode(' ', $ytdlpPath);
                $process = new Process(array_merge($parts, $command));
            } else {
                $process = new Process(array_merge([$ytdlpPath], $command));
            }

            $process->setWorkingDirectory($workingDir);
            $process->setTimeout($timeout);
            $process->run();

            if ($process->isSuccessful()) {
                return $process->getOutput();
            }

            // If first attempt failed, try fallback to executable directly
            Log::warning('First yt-dlp attempt failed, trying standalone executable', [
                'exit_code' => $process->getExitCode(),
                'error_output' => $process->getErrorOutput(),
            ]);

            $exePath = storage_path('app/bin/yt-dlp.exe');
            $process = new Process(array_merge([$exePath], $command));
            $process->setWorkingDirectory($workingDir);
            $process->setTimeout($timeout);
            $process->run();

            if ($process->isSuccessful()) {
                return $process->getOutput();
            }

            // All attempts failed, throw error with details
            throw new Exception('yt-dlp execution failed: ' . $process->getErrorOutput());
        } catch (Exception $e) {
            Log::error('Error executing yt-dlp', [
                'error' => $e->getMessage(),
                'command' => $ytdlpPath . ' ' . implode(' ', $command),
            ]);
            throw $e;
        }
    }
}
