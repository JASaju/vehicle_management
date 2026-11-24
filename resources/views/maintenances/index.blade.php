@extends('layouts.app')

@section('title', 'Maintenance')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Maintenance</h1>
        <a href="{{ route('maintenances.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">+ Add record</a>
    </div>

    <livewire:maintenance-table />
@endsection
