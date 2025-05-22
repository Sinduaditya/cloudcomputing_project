{{-- filepath: resources/views/download/history.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary">Riwayat Download</h2>
                <a href="{{ route('downloads.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="fas fa-plus-circle me-2"></i>Download Baru
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchTable" class="form-control border-start-0 bg-light" placeholder="Cari download...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="downloadTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">#</th>
                                    <th class="py-3">Platform</th>
                                    <th class="py-3">Judul</th>
                                    <th class="py-3">Format</th>
                                    <th class="py-3">Kualitas</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Token</th>
                                    <th class="py-3">Tanggal</th>
                                    <th class="py-3 text-center">File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($downloads as $i => $download)
                                <tr>
                                    <td>{{ $downloads->firstItem() + $i }}</td>
                                    <td>
                                        @php
                                            $platformColors = [
                                                'youtube' => 'danger',
                                                'instagram' => 'purple',
                                                'tiktok' => 'dark',
                                                'facebook' => 'primary',
                                                'twitter' => 'info',
                                                'default' => 'secondary'
                                            ];
                                            $color = $platformColors[$download->platform] ?? $platformColors['default'];
                                        @endphp
                                        <span class="badge bg-{{ $color }} text-uppercase">{{ $download->platform }}</span>
                                    </td>
                                    <td style="max-width:200px;">
                                        <div class="text-truncate fw-medium">{{ $download->title ?? '-' }}</div>
                                        <div class="text-muted small text-truncate">
                                            <a href="{{ $download->url }}" target="_blank" class="text-decoration-none">{{ $download->url }}</a>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ strtoupper($download->format) }}</span></td>
                                    <td>{{ $download->quality ?? '-' }}</td>
                                    <td>
                                        @if($download->status == 'completed')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Selesai
                                            </span>
                                        @elseif($download->status == 'failed')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Gagal
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-spinner fa-spin me-1"></i>Proses
                                            </span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info text-dark">{{ $download->token_cost }}</span></td>
                                    <td>
                                        <div class="small">
                                            <i class="far fa-calendar-alt me-1"></i>{{ $download->created_at->format('d M Y') }}
                                            <br>
                                            <i class="far fa-clock me-1"></i>{{ $download->created_at->format('H:i') }}
                                            @if($download->completed_at)
                                                <div class="text-success small mt-1">
                                                    <i class="fas fa-check-circle me-1"></i>Selesai: {{ $download->completed_at->format('d M Y H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($download->status == 'completed' && $download->file_path && file_exists($download->file_path))
                                            <a href="{{ route('downloads.download', $download->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-download me-1"></i>Download
                                            </a>
                                        @elseif($download->status == 'failed')
                                            <span class="text-danger" data-bs-toggle="tooltip" title="{{ $download->error_message ?? 'Download gagal' }}">
                                                <i class="fas fa-exclamation-circle me-1"></i>Gagal
                                            </span>
                                        @else
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" alt="No data" width="80" class="mb-3 opacity-50">
                                        <p class="text-muted">Belum ada riwayat download.</p>
                                        <a href="{{ route('downloads.create') }}" class="btn btn-sm btn-primary">Mulai Download Pertama Anda</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($downloads->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $downloads->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple table search functionality
    $(document).ready(function(){
        $("#searchTable").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#downloadTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });
</script>
@endpush
@endsection
