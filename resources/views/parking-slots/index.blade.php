@extends('layouts.app')

@section('title', 'Parking slots')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-semibold">Parking slots</h1>

        <div class="flex gap-2">
            <a href="{{ route('parking.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">← Back to map</a>
            <a href="{{ route('parking-slots.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">+ Add slot</a>
        </div>
    </div>

    {{-- Create A1..A3 / B1..B2 in one go --}}
    <form method="POST" action="{{ route('parking-slots.bulk') }}"
          class="mb-6 rounded-lg border border-gray-200 bg-white p-4">
        @csrf
        <h2 class="mb-1 font-semibold">Add several slots at once</h2>
        <p class="mb-3 text-xs text-gray-500">
            Example: floor "Ground Floor", row A, from 1 to 3 creates A1, A2, A3. Do it again with row B, 1 to 2 for B1, B2.
            Slots that already exist are skipped.
        </p>

        <div class="grid gap-3 sm:grid-cols-5">
            <div class="sm:col-span-2">
                <label class="mb-1 block text-xs font-medium text-gray-600">Floor</label>
                <input type="text" name="floor" value="{{ old('floor', 'Ground Floor') }}" required
                       class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('floor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-600">Row (letter)</label>
                <input type="text" name="zone" value="{{ old('zone') }}" maxlength="3" required placeholder="A"
                       class="w-full rounded-md border-gray-300 text-sm uppercase shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('zone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-600">From</label>
                <input type="number" name="from" value="{{ old('from', 1) }}" min="1" max="999" required
                       class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('from') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-gray-600">To</label>
                <input type="number" name="to" value="{{ old('to', 3) }}" min="1" max="999" required
                       class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('to') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <button class="mt-3 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Create slots</button>
    </form>

    <livewire:parking-slot-table />
@endsection
