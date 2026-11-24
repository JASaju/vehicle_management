@extends('layouts.app')

@section('title', 'Parking history')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Parking history</h1>
        <a href="{{ route('parking.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">← Back to map</a>
    </div>

    <livewire:parking-history-table />
@endsection
