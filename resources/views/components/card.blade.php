<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\card.blade.php -->
<div class="neo-card mb-4">
    @isset($header)
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $header }}</h5>
        @isset($headerActions)
            <div>{{ $headerActions }}</div>
        @endisset
    </div>
    @endisset
    <div class="card-body">
        {{ $slot }}
    </div>
    @isset($footer)
    <div class="card-footer">
        {{ $footer }}
    </div>
    @endisset
</div>

{{-- Usage example:
<x-card header="My Card Title">
    <p>Card content goes here</p>
    <x-slot name="headerActions">
        <button class="btn btn-sm neo-btn">Action</button>
    </x-slot>
    <x-slot name="footer">
        Card footer
    </x-slot>
</x-card>
--}}
