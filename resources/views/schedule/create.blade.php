<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\schedule\create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold text-primary"><i class="fas fa-calendar-alt me-2"></i>Jadwalkan Download</h2>
                <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            @if(isset($lowTokens) && $lowTokens)
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i> Saldo token Anda rendah. Pastikan untuk mengisi token sebelum waktu jadwal tiba.
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-primary text-white p-4">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-clock me-2"></i>Atur Jadwal</h4>
                    <p class="mb-0 mt-2">Download akan diproses otomatis pada waktu yang ditentukan</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('schedules.store') }}" id="schedule-form">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-medium">URL Video <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-link text-primary"></i>
                                </span>
                                <input type="url" name="url" class="form-control"
                                    placeholder="https://youtube.com/watch?v=..." required value="{{ old('url') }}">
                            </div>
                            <div class="form-text">
                                <span class="text-muted">Masukkan URL dari:</span>
                                <span class="badge bg-danger me-1"><i class="fab fa-youtube me-1"></i>YouTube</span>
                                <span class="badge bg-purple me-1"><i class="fab fa-instagram me-1"></i>Instagram</span>
                                <span class="badge bg-dark me-1"><i class="fab fa-tiktok me-1"></i>TikTok</span>
                                <span class="badge bg-primary"><i class="fab fa-facebook me-1"></i>Facebook</span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Format <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="format-mp4" value="mp4"
                                            {{ old('format', 'mp4') == 'mp4' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="format-mp4">
                                            <i class="fas fa-film me-1 text-danger"></i> MP4 (Video)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format" id="format-mp3" value="mp3"
                                            {{ old('format') == 'mp3' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="format-mp3">
                                            <i class="fas fa-music me-1 text-info"></i> MP3 (Audio)
                                        </label>
                                    </div>
                                </div>
                                <div class="form-text">MP3 biasanya membutuhkan token lebih sedikit</div>
                            </div>

                            <div class="col-md-6" id="quality-container">
                                <label class="form-label fw-medium">Kualitas Video</label>
                                <select name="quality" class="form-select" id="quality-select">
                                    <option value="1080p" {{ old('quality') == '1080p' ? 'selected' : '' }}>1080p (Full HD)</option>
                                    <option value="720p" {{ old('quality', '720p') == '720p' ? 'selected' : '' }}>720p (HD)</option>
                                    <option value="480p" {{ old('quality') == '480p' ? 'selected' : '' }}>480p (SD)</option>
                                    <option value="360p" {{ old('quality') == '360p' ? 'selected' : '' }}>360p (Low)</option>
                                </select>
                                <div class="form-text">Kualitas lebih rendah = token lebih sedikit</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Waktu Download <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </span>
                                <input type="datetime-local" name="scheduled_for" class="form-control" required
                                    min="{{ now()->addMinutes(5)->format('Y-m-d\TH:i') }}"
                                    value="{{ old('scheduled_for', now()->addHour()->format('Y-m-d\TH:i')) }}">
                            </div>
                            <div class="form-text">Pilih waktu untuk menjalankan download (minimal 5 menit dari sekarang)</div>
                        </div>

                        <div class="alert alert-info d-flex align-items-center p-3 mb-4">
                            <i class="fas fa-info-circle fa-lg me-3"></i>
                            <div>
                                <p class="mb-0 small">Token akan dipotong saat waktu download tiba. Pastikan saldo token Anda cukup pada waktu tersebut.</p>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 text-white">
                                <i class="fas fa-calendar-check me-2"></i>Jadwalkan Download
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><i class="fas fa-lightbulb text-warning me-2"></i>Tips</h5>
                    <ul class="small mb-0">
                        <li>Jadwalkan download pada waktu off-peak untuk performa lebih baik</li>
                        <li>Video dengan durasi lebih panjang membutuhkan lebih banyak token</li>
                        <li>Kualitas video lebih rendah menghasilkan file yang lebih kecil</li>
                        <li>Hasil download tersedia di halaman "Download Saya" setelah selesai</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle quality field when format changes
    document.addEventListener('DOMContentLoaded', function() {
        const formatRadios = document.querySelectorAll('input[name="format"]');
        const qualityContainer = document.getElementById('quality-container');

        function toggleQualityField() {
            const selectedFormat = document.querySelector('input[name="format"]:checked').value;
            if (selectedFormat === 'mp3') {
                qualityContainer.style.opacity = '0.5';
                qualityContainer.style.pointerEvents = 'none';
            } else {
                qualityContainer.style.opacity = '1';
                qualityContainer.style.pointerEvents = 'auto';
            }
        }

        // Initial state
        toggleQualityField();

        // Listen for changes
        formatRadios.forEach(radio => {
            radio.addEventListener('change', toggleQualityField);
        });
    });
</script>
@endpush
@endsection
