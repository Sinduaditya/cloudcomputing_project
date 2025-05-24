<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\form-textarea.blade.php -->
@props(['disabled' => false, 'name', 'label' => null, 'value' => null, 'placeholder' => null, 'rows' => 3])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label fw-bold">{{ $label }}</label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'neo-form-control']) }}
    >{{ $value ?? old($name) }}</textarea>

    @error($name)
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

{{-- Usage example:
<x-form-textarea name="description" label="Description" placeholder="Enter description" rows="5" />
--}}
