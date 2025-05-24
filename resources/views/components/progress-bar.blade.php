<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\progress-bar.blade.php -->
@props(['value' => 0, 'max' => 100, 'label' => null, 'animated' => false, 'striped' => false, 'variant' => 'primary'])

@php
    $percentage = ($value / $max) * 100;
    $variantClass = 'bg-' . $variant;
    $stripedClass = $striped ? 'progress-bar-striped' : '';
    $animatedClass = $animated ? 'progress-bar-animated' : '';
@endphp

<div class="mb-3">
    @if($label)
        <div class="d-flex justify-content-between mb-1">
            <span class="fw-bold">{{ $label }}</span>
            <span>{{ $percentage }}%</span>
        </div>
    @endif

    <div class="progress" style="height: 15px; border: 2px solid #121212; box-shadow: 3px 3px 0 rgba(0,0,0,0.25);">
        <div
            class="progress-bar {{ $variantClass }} {{ $stripedClass }} {{ $animatedClass }}"
            role="progressbar"
            style="width: {{ $percentage }}%;"
            aria-valuenow="{{ $value }}"
            aria-valuemin="0"
            aria-valuemax="{{ $max }}"
        >
            @if(!$label)
                {{ $percentage }}%
            @endif
        </div>
    </div>
</div>

{{-- Usage example:
<x-progress-bar value="75" label="Download Progress" animated striped />
--}}
