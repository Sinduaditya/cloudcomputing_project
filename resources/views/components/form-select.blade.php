<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\form-select.blade.php -->
@props(['name', 'label' => null, 'options' => [], 'selected' => null, 'placeholder' => null])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label fw-bold">{{ $label }}</label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'neo-form-control']) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
    </select>

    @error($name)
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

{{-- Usage example:
<x-form-select name="country" label="Country" :options="$countries" selected="US" placeholder="Select a country" />
--}}
