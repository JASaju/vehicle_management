@extends('layouts.app')

@php $editing = $maintenance->exists; @endphp

@section('title', $editing ? 'Edit maintenance' : 'Add maintenance')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold">{{ $editing ? 'Edit maintenance record' : 'Add maintenance record' }}</h1>

    <form method="POST" action="{{ $editing ? route('maintenances.update', $maintenance) : route('maintenances.store') }}"
          class="space-y-6 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="grid gap-4 md:grid-cols-3">
            <x-form-select name="vehicle_id" label="Vehicle" :options="$vehicles" :value="$maintenance->vehicle_id" placeholder="Select vehicle" required />
            <x-form-select name="type" label="Type" :options="$types" :value="$maintenance->type" required />
            <x-form-select name="status" label="Status" :options="$statuses" :value="$maintenance->status" required />

            <x-form-input name="service_date" label="Service date" type="date" :value="$maintenance->service_date?->format('Y-m-d')" required />
            <x-form-input name="next_service_date" label="Next service date" type="date" :value="$maintenance->next_service_date?->format('Y-m-d')" />
            <x-form-input name="odometer" label="Odometer (km)" type="number" :value="$maintenance->odometer" />

            <x-form-input name="cost" label="Cost" type="number" step="0.01" :value="$maintenance->cost ?? 0" required />
            <x-form-input name="workshop" label="Workshop" :value="$maintenance->workshop" />
            <x-form-input name="description" label="Description" :value="$maintenance->description" />
        </div>

        <p class="text-xs text-gray-500">
            Saving a job with status "In progress" moves the vehicle to <em>In maintenance</em>;
            when no job is in progress any more it returns to <em>Active</em>.
        </p>

        <div class="flex gap-3">
            <button class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ $editing ? 'Update' : 'Save' }}
            </button>
            <a href="{{ route('maintenances.index') }}" class="rounded-md border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
@endsection
