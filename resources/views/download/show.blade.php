<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\download\show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Download Details</h2>

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

    <div class="card mb-4">
        <div class="card-header">
            <h5>{{ $download->title }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Status:</strong>
                        <span class="badge bg-{{ $download->status === 'completed' ? 'success' : ($download->status === 'failed' ? 'danger' : 'info') }}">
                            {{ ucfirst($download->status) }}
                        </span>
                    </p>
                    <p><strong>Platform:</strong> {{ ucfirst($download->platform) }}</p>
                    <p><strong>Format:</strong> {{ strtoupper($download->format) }}</p>
                    <p><strong>Quality:</strong> {{ $download->quality }}</p>
                    <p><strong>Token Cost:</strong> {{ $download->token_cost }} tokens</p>
                    <p><strong>Requested:</strong> {{ $download->created_at->format('d M Y, H:i:s') }}</p>

                    @if($download->completed_at)
                        <p><strong>Completed:</strong> {{ $download->completed_at->format('d M Y, H:i:s') }}</p>
                    @endif

                    @if($download->file_size)
                        <p><strong>File Size:</strong> {{ number_format($download->file_size / 1024 / 1024, 2) }} MB</p>
                    @endif
                </div>
                <div class="col-md-4">
                    @if($download->status === 'completed')
                        @if(strpos($download->file_path, 'http') === 0)
                            <!-- For cloud storage -->
                            <a href="{{ $download->storage_url }}" class="btn btn-primary w-100 mb-2" target="_blank">
                                <i class="fas fa-cloud-download-alt"></i> Download File
                            </a>
                        @else
                            <!-- For local storage -->
                            <a href="{{ route('downloads.download', $download->id) }}" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        @endif
                    @elseif($download->status === 'failed')
                        <div class="alert alert-danger">
                            <strong>Error:</strong> {{ $download->error_message }}
                        </div>
                    @else
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Processing your download...</p>
                        <script>
                            // Reload page every 5 seconds to check status
                            setTimeout(function() {
                                window.location.reload();
                            }, 5000);
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('downloads.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> New Download
    </a>

    <a href="{{ route('downloads.index') }}" class="btn btn-secondary">
        <i class="fas fa-history"></i> Download History
    </a>
</div>
@endsection
