@props(['name', 'label', 'options' => [], 'value' => null, 'required' => false, 'placeholder' => null])

<div>
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-gray-700">
        {{ $label }} @if ($required)<span class="text-red-500">*</span>@endif
    </label>

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @required($required)
        {{ $attributes->merge(['class' => 'w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500']) }}
    >
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
