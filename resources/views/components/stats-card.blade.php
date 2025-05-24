<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\stats-card.blade.php -->
@props(['value', 'label', 'icon', 'color' => 'primary', 'link' => null])

<div class="stats-card h-100">
    <div class="stat-icon">
        <i class="{{ $icon }}" style="color: var(--{{ $color }})"></i>
    </div>
    <div class="stat-value">{{ $value }}</div>
    <div class="stat-label">{{ $label }}</div>

    @if($link)
        <div class="mt-3">
            <a href="{{ $link }}" class="text-decoration-none fw-bold">
                View Details <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    @endif
</div>

{{-- Usage example:
<x-stats-card value="1,234" label="Total Downloads" icon="fas fa-download" color="primary" link="{{ route('downloads.index') }}" />
--}}
