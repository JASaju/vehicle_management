@extends('layouts.app')

@section('title', 'Drivers')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Drivers</h1>
        <a href="{{ route('drivers.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">+ Add driver</a>
    </div>

    <livewire:driver-table />
@endsection
