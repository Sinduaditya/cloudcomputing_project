<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\loading-spinner.blade.php -->
@props(['text' => 'Loading...', 'size' => 'md'])

@php
    $spinnerSize = match($size) {
        'sm' => 'spinner-border-sm',
        'lg' => 'spinner-border spinner-border-lg',
        default => 'spinner-border'
    };
@endphp

<div {{ $attributes->merge(['class' => 'd-flex align-items-center justify-content-center']) }}>
    <div class="{{ $spinnerSize }} text-primary me-2" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <span>{{ $text }}</span>
</div>

{{-- Usage example:
<x-loading-spinner text="Processing..." />
<x-loading-spinner text="Loading data" size="lg" class="my-5" />
--}}
