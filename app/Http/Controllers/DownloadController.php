<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadRequest;
use App\Models\Download;
use App\Services\DownloadService;
use App\Services\TokenService;
use Illuminate\Support\Facades\Log;
use Exception;

class DownloadController extends Controller
{
    protected $downloadService;
    protected $tokenService;

    public function __construct(DownloadService $downloadService, TokenService $tokenService)
    {
        $this->downloadService = $downloadService;
        $this->tokenService = $tokenService;
        $this->middleware('check.token.balance')->only(['store']);
    }

    public function index()
    {
        $downloads = auth()->user()->downloads()->orderBy('created_at', 'desc')->paginate(10);
        return view('download.history', compact('downloads'));
    }

    public function create()
    {
        return view('download.create');
    }

    public function store(DownloadRequest $request)
    {
        try {
            Log::info('Download request received', [
                'url' => $request->url,
                'format' => $request->format,
                'quality' => $request->quality,
            ]);

            $user = auth()->user();
            $url = $request->input('url');
            $format = $request->input('format', 'mp4');
            $quality = $request->input('quality', '720p');

            // Identifikasi platform dari URL untuk skipping metadata
            $platform = null;
            if (strpos($url, 'tiktok') !== false || strpos($url, 'vt.tiktok') !== false) {
                $platform = 'tiktok';
                // Untuk TikTok, kita lewati proses metadata
                $metadata = [
                    'platform' => 'tiktok',
                    'title' => 'TikTok Video',
                    'duration' => 30,
                    'token_cost' => 5,
                ];
            } else {
                // Untuk platform lain, gunakan proses normal
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
                        ->with('error', 'Error analyzing video: ' . $e->getMessage());
                }
                $platform = $metadata['platform'];
            }

            // Hitung biaya token
            $tokenCost = 5; // Default cost untuk TikTok
            if ($platform != 'tiktok') {
                $tokenCost = $this->downloadService->calculateTokenCost($metadata['platform'], $metadata['duration'], $format, $quality);
            }

            // Check token balance
            if ($user->token_balance < $tokenCost) {
                return redirect()
                    ->back()
                    ->with('error', 'Token tidak mencukupi. Diperlukan ' . $tokenCost . ' token.');
            }

            Log::info('Creating download record', [
                'user_id' => $user->id,
                'platform' => $platform,
                'token_cost' => $tokenCost,
            ]);

            // Create download record
            $download = new Download([
                'user_id' => $user->id,
                'url' => $url,
                'platform' => $platform,
                'format' => $format,
                'quality' => $quality,
                'title' => $metadata['title'] ?? 'Video',
                'duration' => $metadata['duration'] ?? 30,
                'token_cost' => $tokenCost,
                'status' => 'pending',
            ]);
            $download->save();

            // Deduct tokens from user's balance
            $this->tokenService->deductTokens($user, $tokenCost, 'download_cost', 'Cost for downloading: ' . ($metadata['title'] ?? 'Video'), $download->id);

            Log::info('Starting download process', ['download_id' => $download->id]);

            // Process download immediately (not in background)
            try {
                $result = $this->downloadService->processDownload($download);
                Log::info('Download completed', [
                    'download_id' => $download->id,
                    'result' => $result,
                ]);

                return redirect()->route('downloads.show', $download->id)->with('success', 'Download berhasil!');
            } catch (Exception $e) {
                Log::error('Error processing download', [
                    'download_id' => $download->id,
                    'error' => $e->getMessage(),
                ]);

                // Refund tokens if download failed
                $this->tokenService->refundTokens($user, $tokenCost, 'Download failed: ' . $e->getMessage(), $download->id);

                return redirect()
                    ->back()
                    ->with('error', 'Download gagal: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Log::error('Unhandled exception in download controller', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('download.show', compact('download'));
    }

    public function downloadFile($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        if (!file_exists($download->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        return response()->download($download->file_path, basename($download->file_path));
    }
}
