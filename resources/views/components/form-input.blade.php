@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false])

<div>
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-gray-700">
        {{ $label }} @if ($required)<span class="text-red-500">*</span>@endif
    </label>

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @required($required)
        {{ $attributes->merge(['class' => 'w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500']) }}
    >

    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
