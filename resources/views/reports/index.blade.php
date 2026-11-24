@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    @php
        $input = 'w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500';
        $label = 'mb-1 block text-xs font-medium text-gray-600';
    @endphp

    <h1 class="mb-1 text-2xl font-semibold">Reports</h1>
    <p class="mb-6 text-sm text-gray-500">Every report opens as a PDF in a new tab. Use "Download" to save the file instead. Leave a filter empty to include everything.</p>

    <div class="grid gap-6 md:grid-cols-2">

        {{-- Vehicle list --}}
        <form method="GET" action="{{ route('reports.vehicles') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Vehicle list</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="{{ $label }}">Status</label>
                    <select name="status" class="{{ $input }}">
                        <option value="">All</option>
                        @foreach ($vehicleStatuses as $value => $text) <option value="{{ $value }}">{{ $text }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $label }}">Type</label>
                    <select name="vehicle_type_id" class="{{ $input }}">
                        <option value="">All</option>
                        @foreach ($types as $value => $text) <option value="{{ $value }}">{{ $text }}</option> @endforeach
                    </select>
                </div>
            </div>
            @include('reports._buttons')
        </form>

        {{-- Driver list --}}
        <form method="GET" action="{{ route('reports.drivers') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Driver list</h2>
            <div>
                <label class="{{ $label }}">Status</label>
                <select name="active" class="{{ $input }}">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            @include('reports._buttons')
        </form>

        {{-- Maintenance --}}
        <form method="GET" action="{{ route('reports.maintenance') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Maintenance report</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                <div><label class="{{ $label }}">From</label><input type="date" name="from" class="{{ $input }}"></div>
                <div><label class="{{ $label }}">To</label><input type="date" name="to" class="{{ $input }}"></div>
                <div>
                    <label class="{{ $label }}">Vehicle</label>
                    <select name="vehicle_id" class="{{ $input }}">
                        <option value="">All</option>
                        @foreach ($vehicles as $value => $text) <option value="{{ $value }}">{{ $text }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="{{ $label }}">Status</label>
                    <select name="status" class="{{ $input }}">
                        <option value="">All</option>
                        @foreach ($maintenanceStatuses as $value => $text) <option value="{{ $value }}">{{ $text }}</option> @endforeach
                    </select>
                </div>
            </div>
            @include('reports._buttons')
        </form>

        {{-- Fuel --}}
        <form method="GET" action="{{ route('reports.fuel') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Fuel report</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                <div><label class="{{ $label }}">From</label><input type="date" name="from" class="{{ $input }}"></div>
                <div><label class="{{ $label }}">To</label><input type="date" name="to" class="{{ $input }}"></div>
                <div class="sm:col-span-2">
                    <label class="{{ $label }}">Vehicle</label>
                    <select name="vehicle_id" class="{{ $input }}">
                        <option value="">All</option>
                        @foreach ($vehicles as $value => $text) <option value="{{ $value }}">{{ $text }}</option> @endforeach
                    </select>
                </div>
            </div>
            @include('reports._buttons')
        </form>

        {{-- Expiry --}}
        <form method="GET" action="{{ route('reports.expiry') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Expiry report</h2>
            <p class="mb-3 text-xs text-gray-500">Insurance, tax token, fitness certificate and driving licence dates that are expired or about to expire.</p>
            <div>
                <label class="{{ $label }}">Expiring within (days)</label>
                <input type="number" name="days" value="30" min="0" max="365" class="{{ $input }}">
            </div>
            @include('reports._buttons')
        </form>

        {{-- Cost summary --}}
        <form method="GET" action="{{ route('reports.costs') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Cost summary by vehicle</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                <div><label class="{{ $label }}">From</label><input type="date" name="from" class="{{ $input }}"></div>
                <div><label class="{{ $label }}">To</label><input type="date" name="to" class="{{ $input }}"></div>
            </div>
            @include('reports._buttons')
        </form>

        {{-- Parking --}}
        <form method="GET" action="{{ route('reports.parking') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-semibold">Parking report</h2>
            <p class="mb-3 text-xs text-gray-500">Who is parked in which slot right now, plus the parking history for the dates below.</p>
            <div class="grid gap-3 sm:grid-cols-2">
                <div><label class="{{ $label }}">History from</label><input type="date" name="from" class="{{ $input }}"></div>
                <div><label class="{{ $label }}">History to</label><input type="date" name="to" class="{{ $input }}"></div>
            </div>
            @include('reports._buttons')
        </form>

        {{-- Single vehicle history --}}
        <form method="GET" action="" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-gray-200 bg-white p-5 md:col-span-2"
              onsubmit="this.action = '{{ url('reports/vehicle') }}/' + this.vehicle.value; return this.vehicle.value !== '';">
            <h2 class="mb-3 font-semibold">Single vehicle history</h2>
            <div>
                <label class="{{ $label }}">Vehicle</label>
                <select name="vehicle" class="{{ $input }}" required>
                    <option value="">Select vehicle</option>
                    @foreach ($vehicles as $value => $text) <option value="{{ $value }}">{{ $text }}</option> @endforeach
                </select>
            </div>
            @include('reports._buttons')
        </form>
    </div>
@endsection
