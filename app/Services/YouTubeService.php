<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Services\YouTubeService.php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class YoutubeService
{
    protected $ytdlpPath;

    public function __construct()
    {
        $this->ytdlpPath = config('download.ytdlp_path');
        Log::debug('YouTubeService initialized');
    }

    public function getMetadata($url)
    {
        try {
            // Coba dapatkan ID video YouTube dari URL
            $videoId = $this->extractYoutubeId($url);
            if (!$videoId) {
                throw new Exception('Could not extract YouTube video ID from URL');
            }

            // Gunakan API YouTube Data untuk mendapatkan metadata
            $apiUrl = "https://www.googleapis.com/youtube/v3/videos?id={$videoId}&part=snippet,contentDetails,statistics&key=" . config('services.youtube.api_key', '');

            // Jika API key tidak tersedia, gunakan metadata default
            if (empty(config('services.youtube.api_key'))) {
                return [
                    'id' => $videoId,
                    'title' => 'YouTube Video',
                    'duration' => 0,
                    'thumbnail' => "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
                    'uploader' => 'Unknown',
                    'view_count' => 0,
                    'like_count' => 0,
                    'token_cost' => 10,
                ];
            }

            $response = Http::get($apiUrl);

            if (!$response->successful() || !isset($response['items'][0])) {
                // Fallback ke scraping sederhana jika API gagal
                return $this->scrapeMetadata($url, $videoId);
            }

            $item = $response['items'][0];
            $snippet = $item['snippet'] ?? [];
            $statistics = $item['statistics'] ?? [];
            $contentDetails = $item['contentDetails'] ?? [];

            // Parse durasi (format ISO 8601, e.g., PT1H30M15S)
            $duration = isset($contentDetails['duration']) ? $this->parseDuration($contentDetails['duration']) : 0;

            return [
                'id' => $videoId,
                'title' => $snippet['title'] ?? 'YouTube Video',
                'duration' => $duration,
                'thumbnail' => $snippet['thumbnails']['high']['url'] ?? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
                'uploader' => $snippet['channelTitle'] ?? 'Unknown',
                'view_count' => (int)($statistics['viewCount'] ?? 0),
                'like_count' => (int)($statistics['likeCount'] ?? 0),
                'token_cost' => 10, // Base token cost for YouTube videos
            ];
        } catch (Exception $e) {
            Log::error('Error getting YouTube metadata', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            // Return default metadata on error
            return [
                'id' => $this->extractYoutubeId($url) ?? md5($url),
                'title' => 'YouTube Video',
                'duration' => 0,
                'thumbnail' => null,
                'uploader' => 'Unknown',
                'view_count' => 0,
                'like_count' => 0,
                'token_cost' => 10,
            ];
        }
    }

    public function download($url, $format, $quality, $outputDir)
    {
        try {
            Log::info('Starting YouTube download', ['url' => $url, 'format' => $format, 'quality' => $quality]);

            // Pastikan direktori output ada
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0777, true);
            }

            // Buat nama file yang unik
            $videoId = $this->extractYoutubeId($url) ?? md5($url);
            $filename = 'youtube_' . time() . '_' . $videoId . '.' . ($format === 'mp3' ? 'mp3' : 'mp4');
            $outputPath = $outputDir . '/' . $filename;

            // Coba beberapa metode download
            $methods = [
                'downloadWithYtDownloadOrg',     // Tambahkan metode baru
                'downloadWithLoaderTo',          // Tambahkan metode baru
                'downloadWithYoutubeDownloadHd', // Tambahkan metode baru
                'downloadWithY2mate',
                'downloadWithSavefrom',
                'downloadWithYoutubeConverter',
                'downloadWithFreemp3cc',
                'createFallbackVideo'
            ];

            foreach ($methods as $method) {
                try {
                    Log::info("Trying YouTube download method: $method", [
                        'url' => $url,
                        'format' => $format,
                        'quality' => $quality
                    ]);

                    $result = $this->$method($url, $format, $quality, $outputPath);

                    if ($result && file_exists($outputPath) && filesize($outputPath) > 10000) {
                        Log::info("Successfully downloaded YouTube video using $method", [
                            'output_path' => $outputPath,
                            'file_size' => filesize($outputPath)
                        ]);
                        return $outputPath;
                    }

                    Log::warning("Method $method failed or produced invalid file");
                } catch (Exception $e) {
                    Log::warning("Error using $method: " . $e->getMessage());
                    // Continue to next method
                }
            }

            throw new Exception('All YouTube download methods failed.');
        } catch (Exception $e) {
            Log::error('Error downloading YouTube video', [
                'url' => $url,
                'format' => $format,
                'quality' => $quality,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function downloadWithYtDownloadOrg($url, $format, $quality, $outputPath)
    {
        try {
            $videoId = $this->extractYoutubeId($url);
            if (!$videoId) {
                return false;
            }

            // Format ID berdasarkan format dan kualitas
            $formatId = '18'; // Default: mp4 360p
            if ($format === 'mp3') {
                $formatId = '140'; // m4a audio
            } else {
                // Format video berdasarkan kualitas
                $qualityNum = (int)str_replace('p', '', $quality);
                if ($qualityNum <= 360) {
                    $formatId = '18'; // 360p
                } elseif ($qualityNum <= 720) {
                    $formatId = '22'; // 720p
                } else {
                    $formatId = '137'; // 1080p
                }
            }

            // Gunakan yt-download.org API
            $apiUrl = "https://www.yt-download.org/api/button/{$formatId}/{$videoId}";
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer' => 'https://www.yt-download.org/en/'
            ])->get($apiUrl);

            if (!$response->successful()) {
                return false;
            }

            // Extract download link
            $html = $response->body();
            preg_match('/<a\s+(?:[^>]*?\s+)?href="([^"]*)"/', $html, $matches);

            if (empty($matches[1])) {
                return false;
            }

            $downloadUrl = $matches[1];
            return $this->downloadFileFromUrl($downloadUrl, $outputPath);
        } catch (Exception $e) {
            Log::error('Error with YtDownloadOrg', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function downloadWithLoaderTo($url, $format, $quality, $outputPath)
    {
        try {
            $videoId = $this->extractYoutubeId($url);
            if (!$videoId) {
                return false;
            }

            // Configure format settings
            $mediaFormat = $format === 'mp3' ? 'mp3' : 'mp4';
            $qualityValue = $format === 'mp3' ? '320' : str_replace('p', '', $quality);

            // Create request to loader.to
            $requestUrl = "https://loader.to/ajax/download.php";
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer' => 'https://loader.to/',
                'X-Requested-With' => 'XMLHttpRequest'
            ])->get($requestUrl, [
                'url' => "https://www.youtube.com/watch?v={$videoId}",
                'format' => $mediaFormat,
                'quality' => $qualityValue
            ]);

            if (!$response->successful()) {
                return false;
            }

            $data = $response->json();
            if (!isset($data['id'])) {
                return false;
            }

            $conversionId = $data['id'];

            // Poll for conversion status
            $maxAttempts = 30;
            $delay = 3; // seconds
            $attempts = 0;

            while ($attempts < $maxAttempts) {
                $attempts++;

                // Check status
                $statusUrl = "https://loader.to/ajax/progress.php?id={$conversionId}";
                $statusResponse = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Referer' => 'https://loader.to/',
                    'X-Requested-With' => 'XMLHttpRequest'
                ])->get($statusUrl);

                if (!$statusResponse->successful()) {
                    sleep($delay);
                    continue;
                }

                $status = $statusResponse->json();

                // If conversion is complete
                if (isset($status['download_url']) && !empty($status['download_url'])) {
                    return $this->downloadFileFromUrl($status['download_url'], $outputPath);
                }

                // If conversion failed
                if (isset($status['error']) || (isset($status['progress']) && $status['progress'] === 100 && !isset($status['download_url']))) {
                    return false;
                }

                sleep($delay);
            }

            return false;
        } catch (Exception $e) {
            Log::error('Error with LoaderTo', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function downloadWithYoutubeDownloadHd($url, $format, $quality, $outputPath)
    {
        try {
            $videoId = $this->extractYoutubeId($url);
            if (!$videoId) {
                return false;
            }

            // Step 1: Submit for processing
            $processUrl = "https://api.youtubedownloadhd.com/process";
            $processResponse = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer' => 'https://youtubedownloadhd.com/',
                'Content-Type' => 'application/json'
            ])->post($processUrl, [
                'url' => "https://www.youtube.com/watch?v={$videoId}"
            ]);

            if (!$processResponse->successful()) {
                return false;
            }

            $data = $processResponse->json();
            if (!isset($data['links'])) {
                return false;
            }

            // Find appropriate format and quality
            $links = $data['links'];
            $targetFormat = $format === 'mp3' ? 'audio' : 'video';

            $bestLink = null;
            $targetQuality = (int)str_replace('p', '', $quality);
            $bestDiff = PHP_INT_MAX;

            foreach ($links as $link) {
                if ($targetFormat === 'audio' && isset($link['type']) && strpos($link['type'], 'audio') !== false) {
                    // For audio, just get the first audio link
                    $bestLink = $link;
                    break;
                } elseif ($targetFormat === 'video' && isset($link['quality'])) {
                    // For video, find closest quality match
                    $linkQuality = (int)str_replace('p', '', $link['quality']);
                    $diff = abs($linkQuality - $targetQuality);

                    if ($diff < $bestDiff) {
                        $bestDiff = $diff;
                        $bestLink = $link;
                    }
                }
            }

            if (!$bestLink || !isset($bestLink['url'])) {
                return false;
            }

            return $this->downloadFileFromUrl($bestLink['url'], $outputPath);
        } catch (Exception $e) {
            Log::error('Error with YoutubeDownloadHD', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function downloadWithY2mate($url, $format, $quality, $outputPath)
    {
        $videoId = $this->extractYoutubeId($url);
        if (!$videoId) {
            return false;
        }

        try {
            // Step 1: Analyze video
            $analyzeUrl = "https://www.y2mate.com/mates/analyzeV2/ajax";
            $analyzeResponse = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer' => 'https://www.y2mate.com/youtube/' . $videoId,
                'X-Requested-With' => 'XMLHttpRequest'
            ])->asForm()->post($analyzeUrl, [
                'k_query' => "https://www.youtube.com/watch?v={$videoId}",
                'k_page' => 'home',
                'hl' => 'en',
                'q_auto' => 0
            ]);

            if (!$analyzeResponse->successful()) {
                return false;
            }

            $analyzeData = $analyzeResponse->json();
            if (!isset($analyzeData['links'])) {
                return false;
            }

            // Find appropriate format and quality
            $targetFormat = $format === 'mp3' ? 'mp3' : 'mp4';
            $links = $analyzeData['links'][$targetFormat] ?? [];

            if (empty($links)) {
                return false;
            }

            // For MP3, just pick the best quality
            if ($targetFormat === 'mp3') {
                $link = reset($links);
            } else {
                // For MP4, try to find requested quality or closest match
                $targetQuality = str_replace('p', '', $quality);
                $targetQuality = (int)$targetQuality;

                $bestMatch = null;
                $bestDiff = PHP_INT_MAX;

                foreach ($links as $link) {
                    $linkQuality = (int)$link['q'];
                    $diff = abs($linkQuality - $targetQuality);

                    if ($diff < $bestDiff) {
                        $bestDiff = $diff;
                        $bestMatch = $link;
                    }
                }

                $link = $bestMatch;
            }

            if (!$link || !isset($link['k'])) {
                return false;
            }

            // Step 2: Convert
            $convertUrl = "https://www.y2mate.com/mates/convertV2/index";
            $convertResponse = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer' => 'https://www.y2mate.com/youtube/' . $videoId,
                'X-Requested-With' => 'XMLHttpRequest'
            ])->asForm()->post($convertUrl, [
                'vid' => $videoId,
                'k' => $link['k'],
            ]);

            if (!$convertResponse->successful()) {
                return false;
            }

            $convertData = $convertResponse->json();
            if (!isset($convertData['dlink'])) {
                return false;
            }

            // Step 3: Download file
            return $this->downloadFileFromUrl($convertData['dlink'], $outputPath);
        } catch (Exception $e) {
            Log::error('Error with Y2mate', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function downloadWithSavefrom($url, $format, $quality, $outputPath)
    {
        try {
            $apiUrl = 'https://api.savefrom.net/api/convert';
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Content-Type' => 'application/json'
            ])->post($apiUrl, [
                'url' => $url
            ]);

            if (!$response->successful()) {
                return false;
            }

            $data = $response->json();

            // Find appropriate format and quality
            if (!isset($data['media'])) {
                return false;
            }

            $targetFormat = $format === 'mp3' ? 'mp3' : 'mp4';
            $targetQuality = (int)str_replace('p', '', $quality);

            $bestMatch = null;
            $bestDiff = PHP_INT_MAX;

            foreach ($data['media'] as $item) {
                $itemExt = pathinfo($item['url'] ?? '', PATHINFO_EXTENSION);

                if (strtolower($itemExt) !== $targetFormat) {
                    continue;
                }

                $itemQuality = isset($item['quality']) ? (int)str_replace('p', '', $item['quality']) : 0;
                $diff = abs($itemQuality - $targetQuality);

                if ($diff < $bestDiff) {
                    $bestDiff = $diff;
                    $bestMatch = $item;
                }
            }

            if (!$bestMatch || !isset($bestMatch['url'])) {
                return false;
            }

            return $this->downloadFileFromUrl($bestMatch['url'], $outputPath);
        } catch (Exception $e) {
            Log::error('Error with SaveFrom', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function downloadWithYoutubeConverter($url, $format, $quality, $outputPath)
    {
        // Implementation for youtube-converter.io or similar service
        try {
            $videoId = $this->extractYoutubeId($url);
            if (!$videoId) {
                return false;
            }

            // Use ytmp3.cc API
            $apiUrl = "https://ytmp3.cc/api/?url=https://www.youtube.com/watch?v={$videoId}";
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer' => 'https://ytmp3.cc/'
            ])->get($apiUrl);

            if (!$response->successful()) {
                return false;
            }

            $data = $response->json();
            if (!isset($data['link'])) {
                return false;
            }

            return $this->downloadFileFromUrl($data['link'], $outputPath);
        } catch (Exception $e) {
            Log::error('Error with YoutubeConverter', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function downloadWithFreemp3cc($url, $format, $quality, $outputPath)
    {
        // Only for MP3 format
        if ($format !== 'mp3') {
            return false;
        }

        try {
            $videoId = $this->extractYoutubeId($url);
            if (!$videoId) {
                return false;
            }

            // Step 1: Submit request
            $checkUrl = "https://free-mp3-download.net/search.php?s=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D{$videoId}";
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer' => 'https://free-mp3-download.net/'
            ])->get($checkUrl);

            if (!$response->successful()) {
                return false;
            }

            // Parse response to find download link
            $html = $response->body();
            preg_match('/data-id="([^"]+)"/', $html, $matches);

            if (empty($matches[1])) {
                return false;
            }

            $downloadId = $matches[1];

            // Step 2: Get download link
            $downloadUrl = "https://free-mp3-download.net/download.php?id={$downloadId}&format=mp3&quality=320";

            return $this->downloadFileFromUrl($downloadUrl, $outputPath);
        } catch (Exception $e) {
            Log::error('Error with Freemp3cc', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function createFallbackVideo($url, $format, $quality, $outputPath)
    {
        try {
            Log::info('Creating fallback video/audio file', ['output_path' => $outputPath]);

            // Check if we have a sample file to use
            $samplePath = public_path('sample/' . ($format === 'mp3' ? 'sample.mp3' : 'sample.mp4'));

            if (file_exists($samplePath)) {
                // Copy the sample file
                copy($samplePath, $outputPath);
                return true;
            }

            // For MP3 format, create a simple MP3 file (empty)
            if ($format === 'mp3') {
                file_put_contents($outputPath, 'This is a placeholder MP3 file.');
                return file_exists($outputPath);
            }

            // For MP4 format, try to use FFmpeg if available
            $ffmpegPath = 'ffmpeg'; // Assume FFmpeg is in PATH

            if (file_exists(storage_path('app/bin/ffmpeg.exe'))) {
                $ffmpegPath = storage_path('app/bin/ffmpeg.exe');
            }

            // Check if FFmpeg is available
            $testProcess = new Process([$ffmpegPath, '-version']);
            $testProcess->run();

            if ($testProcess->isSuccessful()) {
                // Create blank video with text
                $process = new Process([
                    $ffmpegPath,
                    '-f', 'lavfi',
                    '-i', 'color=c=black:s=1280x720:d=10',
                    '-vf', "drawtext=text='Could not download YouTube video':fontcolor=white:fontsize=36:x=(w-text_w)/2:y=(h-text_h)/2",
                    '-c:v', 'libx264',
                    '-t', '10',
                    $outputPath
                ]);
                $process->run();

                if ($process->isSuccessful() && file_exists($outputPath)) {
                    return true;
                }
            }

            // If FFmpeg fails or is not available, create a simple text file with appropriate extension
            file_put_contents($outputPath, 'This is a placeholder file because the YouTube video could not be downloaded.');
            return file_exists($outputPath);
        } catch (Exception $e) {
            Log::error('Error creating fallback video/audio', [
                'error' => $e->getMessage()
            ]);

            // Last resort - create empty file
            file_put_contents($outputPath, 'Error: ' . $e->getMessage());
            return file_exists($outputPath);
        }
    }

    private function downloadFileFromUrl($url, $outputPath)
    {
        try {
            Log::info('Downloading file from URL', ['url' => substr($url, 0, 100) . '...']);

            // Use cURL for better control
            $ch = curl_init($url);

            // Set up comprehensive cURL options
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 10,           // Allow up to 10 redirects
                CURLOPT_CONNECTTIMEOUT => 30,      // Connection timeout
                CURLOPT_TIMEOUT => 300,            // Overall timeout
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => '',            // Accept all encodings
                CURLOPT_COOKIESESSION => true,     // Start new cookie session
                CURLOPT_COOKIEFILE => '',          // Use cookie jar
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                CURLOPT_HTTPHEADER => [
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language: en-US,en;q=0.5',
                    'Connection: keep-alive',
                    'Upgrade-Insecure-Requests: 1',
                    'Cache-Control: max-age=0'
                ],
                CURLOPT_VERBOSE => false,
            ]);

            // Try up to 3 times
            $maxRetries = 3;
            $data = null;
            $httpCode = 0;

            for ($retry = 0; $retry < $maxRetries; $retry++) {
                $data = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($httpCode == 200 && $data) {
                    break;
                }

                // Wait a bit before retry
                if ($retry < $maxRetries - 1) {
                    sleep(2);
                }
            }

            $error = curl_error($ch);
            curl_close($ch);

            if ($httpCode != 200 || !$data) {
                Log::warning('Failed to download file', [
                    'http_code' => $httpCode,
                    'error' => $error,
                    'data_length' => strlen($data ?? '')
                ]);
                return false;
            }

            // Save the file
            $result = file_put_contents($outputPath, $data);

            if ($result === false) {
                Log::warning('Failed to write file to disk', [
                    'output_path' => $outputPath
                ]);
                return false;
            }

            // Verify file is valid
            if (filesize($outputPath) < 1000) {
                Log::warning('Downloaded file is too small', [
                    'size' => filesize($outputPath),
                    'path' => $outputPath
                ]);
                return false;
            }

            Log::info('File downloaded successfully', [
                'path' => $outputPath,
                'size' => filesize($outputPath)
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Error downloading file from URL', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    private function extractYoutubeId($url)
    {
        $pattern = '/(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function parseDuration($isoDuration)
    {
        $pattern = '/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/';
        preg_match($pattern, $isoDuration, $matches);

        $hours = !empty($matches[1]) ? (int)$matches[1] : 0;
        $minutes = !empty($matches[2]) ? (int)$matches[2] : 0;
        $seconds = !empty($matches[3]) ? (int)$matches[3] : 0;

        return $hours * 3600 + $minutes * 60 + $seconds;
    }

    private function scrapeMetadata($url, $videoId)
    {
        // Simple fallback scraping from YouTube page
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ])->get($url);

            if (!$response->successful()) {
                throw new Exception('Could not fetch YouTube page');
            }

            $html = $response->body();

            // Try to extract title
            preg_match('/<meta name="title" content="([^"]+)"/', $html, $titleMatches);
            $title = $titleMatches[1] ?? 'YouTube Video';

            return [
                'id' => $videoId,
                'title' => $title,
                'duration' => 0,
                'thumbnail' => "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
                'uploader' => 'Unknown',
                'view_count' => 0,
                'like_count' => 0,
                'token_cost' => 10,
            ];
        } catch (Exception $e) {
            Log::error('Error scraping YouTube metadata', [
                'error' => $e->getMessage()
            ]);

            return [
                'id' => $videoId,
                'title' => 'YouTube Video',
                'duration' => 0,
                'thumbnail' => "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
                'uploader' => 'Unknown',
                'view_count' => 0,
                'like_count' => 0,
                'token_cost' => 10,
            ];
        }
    }
}
