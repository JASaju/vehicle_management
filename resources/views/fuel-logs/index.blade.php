@extends('layouts.app')

@section('title', 'Fuel Logs')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Fuel Logs</h1>
        <a href="{{ route('fuel-logs.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">+ Add fuel entry</a>
    </div>

    <livewire:fuel-log-table />
@endsection
