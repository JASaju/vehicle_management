@extends('layouts.app')

@php $editing = $driver->exists; @endphp

@section('title', $editing ? 'Edit driver' : 'Add driver')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold">{{ $editing ? 'Edit driver · ' . $driver->name : 'Add driver' }}</h1>

    <form method="POST" action="{{ $editing ? route('drivers.update', $driver) : route('drivers.store') }}"
          class="space-y-6 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="grid gap-4 md:grid-cols-2">
            <x-form-input name="name" label="Full name" :value="$driver->name" required />
            <x-form-input name="phone" label="Phone" :value="$driver->phone" required />
            <x-form-input name="email" label="Email" type="email" :value="$driver->email" />
            <x-form-input name="address" label="Address" :value="$driver->address" />
            <x-form-input name="license_number" label="License number" :value="$driver->license_number" required />
            <x-form-input name="license_expiry" label="License expiry" type="date" :value="$driver->license_expiry?->format('Y-m-d')" required />
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600"
                   @checked(old('is_active', $driver->is_active ?? true))>
            Active driver
        </label>

        <div class="flex gap-3">
            <button class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ $editing ? 'Update' : 'Save' }}
            </button>
            <a href="{{ route('drivers.index') }}" class="rounded-md border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
@endsection
