<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\status-badge.blade.php -->
@props(['status'])

@php
    $statusClasses = [
        'pending' => 'bg-warning',
        'processing' => 'bg-info',
        'completed' => 'bg-success',
        'failed' => 'bg-danger',
        'cancelled' => 'bg-secondary',
        'scheduled' => 'bg-primary',
        'expired' => 'bg-dark',
        'active' => 'bg-success',
        'inactive' => 'bg-secondary',
        'downloading' => 'bg-info',
        'uploading' => 'bg-info',
        'storing' => 'bg-info',
    ];

    $statusIcons = [
        'pending' => 'fas fa-clock',
        'processing' => 'fas fa-cog fa-spin',
        'completed' => 'fas fa-check',
        'failed' => 'fas fa-exclamation-triangle',
        'cancelled' => 'fas fa-ban',
        'scheduled' => 'fas fa-calendar-alt',
        'expired' => 'fas fa-calendar-times',
        'active' => 'fas fa-check-circle',
        'inactive' => 'fas fa-times-circle',
        'downloading' => 'fas fa-download',
        'uploading' => 'fas fa-upload',
        'storing' => 'fas fa-save fa-spin',
    ];

    $badgeClass = $statusClasses[strtolower($status)] ?? 'bg-secondary';
    $iconClass = $statusIcons[strtolower($status)] ?? 'fas fa-question';
@endphp

<span
    {{ $attributes->merge(['class' => "badge $badgeClass"]) }}
    style="border: 2px solid #121212; border-radius: 6px; padding: 5px 10px;"
>
    <i class="{{ $iconClass }} me-1"></i> {{ ucfirst($status) }}
</span>

{{-- Usage example:
<x-status-badge status="completed" />
<x-status-badge status="pending" class="fs-6" />
--}}
