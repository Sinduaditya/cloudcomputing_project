@extends('layouts.app')

@section('content')
<div class="container mt-4" style="max-width:500px;">
    <h3>New Download</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <p>Current Token Balance: <strong>{{ auth()->user()->token_balance }}</strong></p>
        </div>
    </div>

    <form method="POST" action="{{ route('downloads.store') }}" id="downloadForm">
        @csrf
        <div class="mb-3">
            <label>Video URL</label>
            <input type="url" name="url" class="form-control" required value="{{ old('url') }}"
                placeholder="Paste YouTube, TikTok or Instagram URL">
            <small class="text-muted">We support YouTube, TikTok, and Instagram links</small>
        </div>

        <div class="mb-3">
            <label>Format</label>
            <select name="format" class="form-select" id="format-select">
                <option value="mp4" {{ old('format') == 'mp4' ? 'selected' : '' }}>MP4 (Video)</option>
                <option value="mp3" {{ old('format') == 'mp3' ? 'selected' : '' }}>MP3 (Audio only)</option>
            </select>
        </div>

        <div class="mb-3" id="quality-container">
            <label>Quality</label>
            <select name="quality" class="form-select">
                <option value="1080p" {{ old('quality') == '1080p' ? 'selected' : '' }}>1080p (HD)</option>
                <option value="720p" {{ old('quality') == '720p' ? 'selected' : '' }}>720p (HD)</option>
                <option value="480p" {{ old('quality') == '480p' ? 'selected' : '' }}>480p (SD)</option>
                <option value="360p" {{ old('quality') == '360p' ? 'selected' : '' }}>360p (SD)</option>
            </select>
            <small class="text-muted">Higher quality requires more tokens</small>
        </div>

        <button type="submit" class="btn btn-success w-100" id="downloadBtn">Download Now</button>

        <div id="loadingIndicator" class="text-center mt-3" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memproses download, mohon tunggu...</p>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formatSelect = document.getElementById('format-select');
    const qualityContainer = document.getElementById('quality-container');
    const downloadForm = document.getElementById('downloadForm');
    const downloadBtn = document.getElementById('downloadBtn');
    const loadingIndicator = document.getElementById('loadingIndicator');

    // Hide quality if MP3 is selected
    function updateQualityVisibility() {
        if (formatSelect.value === 'mp3') {
            qualityContainer.style.display = 'none';
        } else {
            qualityContainer.style.display = 'block';
        }
    }

    // Initial check
    updateQualityVisibility();

    // Listen for changes
    formatSelect.addEventListener('change', updateQualityVisibility);

    // Show loading indicator on form submit
    downloadForm.addEventListener('submit', function(e) {
        // Validate form
        if (downloadForm.checkValidity()) {
            downloadBtn.disabled = true;
            downloadBtn.textContent = "Processing...";
            loadingIndicator.style.display = 'block';

            // Log to console for debugging
            console.log('Form submitted, processing download...');
        }
    });
});
</script>
@endsection
