<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\schedule\index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-primary"><i class="fas fa-calendar-alt me-2"></i>Jadwal Download</h2>
                    <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Jadwalkan Baru
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @php
                    // Define platform icons here before they're used
$platformIcons = [
    'youtube' => '<i class="fab fa-youtube text-danger fa-lg"></i>',
    'instagram' => '<i class="fab fa-instagram text-purple fa-lg"></i>',
    'tiktok' => '<i class="fab fa-tiktok text-dark fa-lg"></i>',
    'facebook' => '<i class="fab fa-facebook text-primary fa-lg"></i>',
    'other' => '<i class="fas fa-link text-secondary fa-lg"></i>',
];

$upcomingSchedules = $schedules
    ->where('status', 'scheduled')
    ->where('scheduled_for', '>', now())
                        ->take(3);
                    $hasUpcoming = $upcomingSchedules->isNotEmpty();
                @endphp

                <!-- Upcoming Schedules Quick View -->
                @if ($hasUpcoming)
                    <div class="card border-0 shadow-sm mb-4 bg-primary bg-opacity-10">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3"><i class="fas fa-hourglass-half me-2 text-primary"></i>Jadwal Mendatang
                            </h5>
                            <div class="row">
                                @foreach ($upcomingSchedules as $upcoming)
                                    <div class="col-md-4 mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 fs-4">
                                                {!! $platformIcons[$upcoming->platform] ?? $platformIcons['other'] !!}
                                            </div>
                                            <div>
                                                <div class="small text-truncate" style="max-width: 200px;">
                                                    {{ $upcoming->url }}</div>
                                                <div class="small text-muted">
                                                    <i
                                                        class="far fa-clock me-1"></i>{{ $upcoming->scheduled_for->format('d M Y H:i') }}
                                                    <div class="small text-primary mt-1">
                                                        ({{ $upcoming->scheduled_for->diffForHumans() }})
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        @if ($schedules->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-calendar-alt fa-4x text-muted opacity-25"></i>
                                </div>
                                <h5 class="text-muted">Belum ada jadwal download</h5>
                                <p class="text-muted small mb-4">Jadwalkan download video atau audio untuk diproses nanti
                                </p>
                                <a href="{{ route('schedules.create') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Buat Jadwal Baru
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Platform</th>
                                            <th>URL</th>
                                            <th>Format</th>
                                            <th>Dijadwalkan</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($schedules as $schedule)
                                            <tr @if ($schedule->status == 'failed') class="table-danger bg-opacity-10" @endif>
                                                <td>
                                                    {!! $platformIcons[$schedule->platform] ?? $platformIcons['other'] !!}
                                                </td>
                                                <td style="max-width:220px;">
                                                    <div class="text-truncate">
                                                        <a href="{{ $schedule->url }}" target="_blank"
                                                            class="text-decoration-none">
                                                            {{ $schedule->url }}
                                                        </a>
                                                    </div>
                                                    @if ($schedule->download_id)
                                                        <div class="mt-1">
                                                            <a href="{{ route('downloads.show', $schedule->download_id) }}"
                                                                class="badge bg-info text-white text-decoration-none">
                                                                <i class="fas fa-file-download me-1"></i>Lihat Hasil
                                                            </a>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($schedule->format == 'mp4')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-video me-1"></i>MP4
                                                        </span>
                                                        @if ($schedule->quality)
                                                            <span
                                                                class="badge bg-secondary ms-1">{{ $schedule->quality }}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-music me-1"></i>MP3
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        <i
                                                            class="far fa-calendar-alt me-1"></i>{{ $schedule->scheduled_for->format('d M Y') }}
                                                    </div>
                                                    <div class="small text-muted">
                                                        <i
                                                            class="far fa-clock me-1"></i>{{ $schedule->scheduled_for->format('H:i') }}
                                                    </div>
                                                    @if ($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture())
                                                        <div class="small text-primary mt-1">
                                                            ({{ $schedule->scheduled_for->diffForHumans() }})
                                                        </div>
                                                    @elseif($schedule->status == 'scheduled' && $schedule->scheduled_for->isPast())
                                                        <div class="small text-warning mt-1">
                                                            <i class="fas fa-exclamation-circle me-1"></i>Sedang menunggu
                                                            proses
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $schedule->status_badge }}">
                                                        @switch($schedule->status)
                                                            @case('scheduled')
                                                                <i class="fas fa-clock me-1"></i>Menunggu
                                                            @break

                                                            @case('processing')
                                                                <i class="fas fa-spinner fa-spin me-1"></i>Diproses
                                                            @break

                                                            @case('completed')
                                                                <i class="fas fa-check-circle me-1"></i>Selesai
                                                            @break

                                                            @default
                                                                <i class="fas fa-times-circle me-1"></i>Gagal
                                                        @endswitch
                                                    </span>

                                                    @if ($schedule->error_message)
                                                        <div class="small text-danger mt-1" data-bs-toggle="tooltip"
                                                            title="{{ $schedule->error_message }}">
                                                            <i
                                                                class="fas fa-exclamation-circle me-1"></i>{{ \Illuminate\Support\Str::limit($schedule->error_message, 30) }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($schedule->status == 'scheduled' && $schedule->scheduled_for->isFuture())
                                                        <form action="{{ route('schedules.destroy', $schedule->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Anda yakin ingin membatalkan jadwal download ini?')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    @elseif($schedule->status == 'failed')
                                                        <a href="{{ route('schedules.create') }}?retry={{ $schedule->id }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-redo"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    @if ($schedules->hasPages())
                        <div class="card-footer bg-white py-3">
                            {{ $schedules->links() }}
                        </div>
                    @endif
                </div>

                <!-- Tips panel -->
                <div class="card mt-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2"></i>Tentang Jadwal Download
                        </h6>
                        <p class="small mb-0">
                            Fitur jadwal download memungkinkan Anda menyiapkan download untuk diproses secara otomatis pada
                            waktu yang ditentukan.
                            Token akan dipotong saat proses download dimulai, bukan saat Anda membuat jadwal.
                            Jadwal yang telah diproses tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Enable tooltips
            document.addEventListener('DOMContentLoaded', function() {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    @endpush
@endsection
