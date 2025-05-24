<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\empty-state.blade.php -->
@props(['title' => 'No Data Found', 'message' => 'There are no records to display.', 'icon' => 'fas fa-folder-open', 'action' => null, 'actionLink' => null, 'actionText' => 'Create New'])

<div class="text-center py-5 my-4" style="border: 3px dashed #121212; border-radius: 8px;">
    <div class="display-1 text-muted mb-4">
        <i class="{{ $icon }}"></i>
    </div>
    <h4>{{ $title }}</h4>
    <p class="text-muted mb-4">{{ $message }}</p>

    @if($action && $actionLink && $actionText)
        <a href="{{ $actionLink }}" class="neo-btn">
            <i class="fas fa-plus-circle me-2"></i> {{ $actionText }}
        </a>
    @endif
</div>

{{-- Usage example:
<x-empty-state
    title="No Downloads Yet"
    message="You haven't made any downloads yet. Start downloading content now!"
    icon="fas fa-download"
    action="true"
    actionLink="{{ route('downloads.create') }}"
    actionText="New Download"
/>
--}}
