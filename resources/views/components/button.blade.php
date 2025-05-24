<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\button.blade.php -->
@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button'])

@php
    $sizeClass = match($size) {
        'sm' => 'py-1 px-3 text-sm',
        'lg' => 'py-3 px-5 text-lg',
        default => 'py-2 px-4'
    };

    $variantClass = match($variant) {
        'secondary' => 'btn-secondary',
        'success' => 'btn-success',
        'danger' => 'btn-danger',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        default => ''
    };
@endphp

<button {{ $attributes->merge(['class' => "neo-btn {$sizeClass} {$variantClass}", 'type' => $type]) }}>
    {{ $slot }}
</button>

{{-- Usage example:
<x-button variant="primary" size="md" type="submit">Submit</x-button>
<x-button variant="secondary" class="ms-2">Cancel</x-button>
--}}
