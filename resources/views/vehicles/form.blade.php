@extends('layouts.app')

@php $editing = $vehicle->exists; @endphp

@section('title', $editing ? 'Edit vehicle' : 'Add vehicle')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold">{{ $editing ? 'Edit vehicle · ' . $vehicle->registration_no : 'Add vehicle' }}</h1>

    <form method="POST" action="{{ $editing ? route('vehicles.update', $vehicle) : route('vehicles.store') }}"
          class="space-y-6 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="grid gap-4 md:grid-cols-3">
            <x-form-input name="registration_no" label="Registration no" :value="$vehicle->registration_no" required />
            <x-form-select name="vehicle_type_id" label="Vehicle type" :options="$types" :value="$vehicle->vehicle_type_id" placeholder="Select type" required />
            <x-form-select name="driver_id" label="Assigned driver" :options="$drivers" :value="$vehicle->driver_id" placeholder="— Unassigned —" />

            <x-form-input name="brand" label="Brand" :value="$vehicle->brand" required />
            <x-form-input name="model" label="Model" :value="$vehicle->model" required />
            <x-form-input name="year" label="Year" type="number" :value="$vehicle->year" required />

            <x-form-input name="color" label="Color" :value="$vehicle->color" />
            <x-form-input name="chassis_no" label="Chassis no" :value="$vehicle->chassis_no" />
            <x-form-input name="engine_no" label="Engine no" :value="$vehicle->engine_no" />

            <x-form-select name="fuel_type" label="Fuel type" :options="$fuelTypes" :value="$vehicle->fuel_type" required />
            <x-form-input name="seat_capacity" label="Seat capacity" type="number" :value="$vehicle->seat_capacity ?? 4" required />
            <x-form-input name="odometer" label="Odometer (km)" type="number" :value="$vehicle->odometer ?? 0" required />

            <x-form-input name="purchase_date" label="Purchase date" type="date" :value="$vehicle->purchase_date?->format('Y-m-d')" />
            <x-form-input name="purchase_price" label="Purchase price" type="number" step="0.01" :value="$vehicle->purchase_price" />
            <x-form-select name="status" label="Status" :options="$statuses" :value="$vehicle->status" required />

            <x-form-input name="insurance_expiry" label="Insurance expiry" type="date" :value="$vehicle->insurance_expiry?->format('Y-m-d')" />
            <x-form-input name="tax_token_expiry" label="Tax token expiry" type="date" :value="$vehicle->tax_token_expiry?->format('Y-m-d')" />
            <x-form-input name="fitness_expiry" label="Fitness expiry" type="date" :value="$vehicle->fitness_expiry?->format('Y-m-d')" />
        </div>

        <x-form-textarea name="notes" label="Notes" :value="$vehicle->notes" />

        <div class="flex gap-3">
            <button class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ $editing ? 'Update' : 'Save' }}
            </button>
            <a href="{{ route('vehicles.index') }}" class="rounded-md border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
@endsection
