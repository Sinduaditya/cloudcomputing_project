<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\components\form-input.blade.php -->
@props(['disabled' => false, 'name', 'label' => null, 'type' => 'text', 'value' => null, 'placeholder' => null])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label fw-bold">{{ $label }}</label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value ?? old($name) }}"
        placeholder="{{ $placeholder }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'neo-form-control']) }}
    >

    @error($name)
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

{{-- Usage example:
<x-form-input name="email" label="Email Address" type="email" placeholder="Enter email" required />
<x-form-input name="password" label="Password" type="password" />
--}}
