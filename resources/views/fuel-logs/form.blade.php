@extends('layouts.app')

@php $editing = $fuelLog->exists; @endphp

@section('title', $editing ? 'Edit fuel entry' : 'Add fuel entry')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold">{{ $editing ? 'Edit fuel entry' : 'Add fuel entry' }}</h1>

    <form method="POST" action="{{ $editing ? route('fuel-logs.update', $fuelLog) : route('fuel-logs.store') }}"
          class="space-y-6 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="grid gap-4 md:grid-cols-3">
            <x-form-select name="vehicle_id" label="Vehicle" :options="$vehicles" :value="$fuelLog->vehicle_id" placeholder="Select vehicle" required />
            <x-form-select name="driver_id" label="Driver" :options="$drivers" :value="$fuelLog->driver_id" placeholder="— None —" />
            <x-form-input name="fuel_date" label="Date" type="date" :value="$fuelLog->fuel_date?->format('Y-m-d')" required />

            <x-form-input name="liters" label="Liters" type="number" step="0.01" :value="$fuelLog->liters" required />
            <x-form-input name="price_per_liter" label="Price per liter" type="number" step="0.01" :value="$fuelLog->price_per_liter" required />
            <x-form-input name="odometer" label="Odometer (km)" type="number" :value="$fuelLog->odometer" />

            <x-form-input name="station" label="Fuel station" :value="$fuelLog->station" class="md:col-span-1" />
        </div>

        <p class="text-xs text-gray-500">Total cost is calculated automatically (liters × price per liter).</p>

        <div class="flex gap-3">
            <button class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ $editing ? 'Update' : 'Save' }}
            </button>
            <a href="{{ route('fuel-logs.index') }}" class="rounded-md border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
@endsection
