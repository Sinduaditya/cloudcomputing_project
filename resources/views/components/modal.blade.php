<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\modal.blade.php -->
@props(['id', 'title', 'footer' => null, 'size' => 'md'])

@php
    $modalSizes = [
        'sm' => 'modal-sm',
        'md' => '',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
    ];
    $modalSize = $modalSizes[$size] ?? '';
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $modalSize }}" style="max-width: calc(100% - 20px);">
        <div class="modal-content" style="border: 3px solid #121212; border-radius: 12px; box-shadow: 8px 8px 0 rgba(0,0,0,0.35);">
            <div class="modal-header bg-primary-gradient text-white" style="border-bottom: 3px solid #121212;">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if($footer)
                <div class="modal-footer" style="border-top: 2px solid #121212;">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Usage example:
<x-modal id="confirmDelete" title="Confirm Delete" size="md">
    <p>Are you sure you want to delete this item?</p>
    <x-slot name="footer">
        <button type="button" class="neo-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="neo-btn">Delete</button>
    </x-slot>
</x-modal>
--}}
