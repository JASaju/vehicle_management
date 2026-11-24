@extends('layouts.app')

@section('title', 'Parking')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-semibold">Parking</h1>

        <div class="flex gap-2">
            <a href="{{ route('parking.history') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">History</a>
            <a href="{{ route('parking-slots.index') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Manage slots</a>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
        @foreach ([
            ['Total slots', $stats['total'], 'text-gray-900'],
            ['Free', $stats['free'], 'text-green-600'],
            ['Occupied', $stats['occupied'], 'text-red-600'],
            ['Out of service', $stats['inactive'], 'text-gray-500'],
        ] as [$label, $value, $color])
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $label }}</div>
                <div class="mt-1 text-2xl font-semibold {{ $color }}">{{ $value }}</div>
            </div>
        @endforeach
    </div>

    @forelse ($floors as $floor => $zones)
        <section class="mb-8 rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-4 text-lg font-semibold">{{ $floor }}</h2>

            @foreach ($zones as $zone => $zoneSlots)
                <div class="mb-5 last:mb-0">
                    <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Row {{ $zone }}</div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
                        @foreach ($zoneSlots as $slot)
                            @php $record = $slot->currentRecord; @endphp

                            @if ($record)
                                {{-- Occupied --}}
                                <div class="rounded-lg border border-red-300 bg-red-50 p-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-bold text-red-700">{{ $slot->code }}</span>
                                        {!! \App\Support\Ui::badge('Occupied', 'red') !!}
                                    </div>

                                    @if ($record->vehicle)
                                        <div class="mt-2 text-sm font-semibold">{{ $record->vehicle->registration_no }}</div>
                                        <div class="text-xs text-gray-600">{{ $record->vehicle->brand }} {{ $record->vehicle->model }}</div>
                                        <div class="text-xs text-gray-600">Driver: {{ $record->vehicle->driver?->name ?? '—' }}</div>
                                    @else
                                        <div class="mt-2 text-sm font-semibold">Vehicle not identified</div>
                                        @if ($record->detected_plate)
                                            <div class="text-xs text-gray-600">Plate read: {{ $record->detected_plate }}</div>
                                        @endif
                                        @if (count($available))
                                            <form method="POST" action="{{ route('parking.identify', $slot) }}" class="mt-2 space-y-2">
                                                @csrf
                                                <select name="vehicle_id" required
                                                        class="w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="">Assign vehicle…</option>
                                                    @foreach ($available as $id => $label)
                                                        <option value="{{ $id }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                <button class="w-full rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700">Assign vehicle</button>
                                            </form>
                                        @endif
                                    @endif
                                    <div class="mt-1 text-xs text-gray-500">
                                        Since {{ $record->parked_at->format('d M, H:i') }}
                                        ({{ \App\Support\Ui::duration($record->parked_at) }})
                                    </div>

                                    @if (in_array(strtoupper($slot->code), config('parking_sensors.slots', []), true))
                                        <div class="mt-3 text-xs text-gray-500">
                                            Sensor: {{ $slot->sensor_occupied === null ? 'Waiting for first reading' : ($slot->sensor_occupied ? 'Occupied' : 'Free') }}
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('parking.release', $slot) }}" class="mt-3"
                                              onsubmit="return confirm('Release this vehicle from the slot?')">
                                            @csrf
                                            <button class="w-full rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700">Release</button>
                                        </form>
                                    @endif
                                </div>
                            @elseif (! $slot->is_active)
                                {{-- Out of service --}}
                                <div class="rounded-lg border border-gray-200 bg-gray-100 p-3 text-gray-400">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-bold">{{ $slot->code }}</span>
                                        {!! \App\Support\Ui::badge('Out of service', 'gray') !!}
                                    </div>
                                    <div class="mt-2 text-xs">Not available for parking.</div>
                                </div>
                            @else
                                {{-- Free --}}
                                <div class="rounded-lg border border-green-300 bg-green-50 p-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-bold text-green-700">{{ $slot->code }}</span>
                                        {!! \App\Support\Ui::badge('Free', 'green') !!}
                                    </div>

                                    @if (in_array(strtoupper($slot->code), config('parking_sensors.slots', []), true))
                                        <div class="mt-3 text-xs text-gray-500">
                                            Sensor: {{ $slot->sensor_occupied === null ? 'Waiting for first reading' : 'Free' }}. Parking is detected automatically.
                                        </div>
                                    @elseif (count($available))
                                        <form method="POST" action="{{ route('parking.park', $slot) }}" class="mt-3 space-y-2">
                                            @csrf
                                            <select name="vehicle_id" required
                                                    class="w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Select vehicle…</option>
                                                @foreach ($available as $id => $label)
                                                    <option value="{{ $id }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button class="w-full rounded-md bg-green-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-green-700">Park here</button>
                                        </form>
                                    @else
                                        <div class="mt-3 text-xs text-gray-500">No unparked vehicle available.</div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>
    @empty
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center text-sm text-gray-500">
            No parking slots yet.
            <a href="{{ route('parking-slots.index') }}" class="font-medium text-indigo-600 hover:underline">Create slots</a>
            (for example A1–A3 and B1–B2 on the Ground Floor).
        </div>
    @endforelse
@endsection

@if (count(config('parking_sensors.slots', [])))
    @push('scripts')
        <script>
            window.setInterval(() => {
                if (!document.querySelector('form:focus-within')) {
                    window.location.reload();
                }
            }, 10000);
        </script>
    @endpush
@endif
