@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Vehicles</h1>
        <a href="{{ route('vehicles.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">+ Add vehicle</a>
    </div>

    <livewire:vehicle-table />
@endsection
