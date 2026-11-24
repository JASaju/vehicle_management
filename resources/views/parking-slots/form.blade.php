@extends('layouts.app')

@php $editing = $slot->exists; @endphp

@section('title', $editing ? 'Edit slot' : 'Add slot')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold">{{ $editing ? 'Edit slot · ' . $slot->code : 'Add parking slot' }}</h1>

    <form method="POST" action="{{ $editing ? route('parking-slots.update', $slot) : route('parking-slots.store') }}"
          class="max-w-2xl space-y-6 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="grid gap-4 md:grid-cols-3">
            <x-form-input name="floor" label="Floor" :value="$slot->floor" required />
            <x-form-input name="zone" label="Row (letter)" :value="$slot->zone" maxlength="3" required class="uppercase" />
            <x-form-input name="number" label="Number" type="number" :value="$slot->number" min="1" max="999" required />
        </div>

        <p class="text-xs text-gray-500">The slot code is the row letter plus the number, for example row A + 1 = <strong>A1</strong>.</p>

        @error('code')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div>
            <label class="flex items-center gap-2 text-sm">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600"
                       @checked(old('is_active', $slot->is_active ?? true))>
                In service (untick to block parking here)
            </label>

            @error('is_active')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <button class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ $editing ? 'Update' : 'Save' }}
            </button>
            <a href="{{ route('parking-slots.index') }}" class="rounded-md border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
@endsection
