@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold">Dashboard</h1>

    @php
        $cards = [
            ['Total vehicles', number_format($stats['total']), 'text-gray-900'],
            ['Active', number_format($stats['active']), 'text-green-600'],
            ['In maintenance', number_format($stats['in_maintenance']), 'text-yellow-600'],
            ['Active drivers', number_format($stats['drivers']), 'text-gray-900'],
            ['Parking free', $stats['slots_free'] . ' / ' . $stats['slots_total'], $stats['slots_free'] > 0 ? 'text-green-600' : 'text-red-600'],
            ['Fuel cost (30 days)', number_format($stats['fuel_cost'], 2), 'text-gray-900'],
            ['Maintenance cost (30 days)', number_format($stats['maintenance_cost'], 2), 'text-gray-900'],
        ];
    @endphp

    <div class="mb-8 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-7">
        @foreach ($cards as [$label, $value, $color])
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $label }}</div>
                <div class="mt-1 text-2xl font-semibold {{ $color }}">{{ $value }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-lg border border-gray-200 bg-white">
            <h2 class="border-b border-gray-200 px-4 py-3 font-semibold">Expired / expiring within 30 days</h2>

            @forelse ($alerts as $alert)
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 text-sm last:border-0">
                    <div>
                        <div class="font-medium">{{ $alert['subject'] }}</div>
                        <div class="text-xs text-gray-500">{{ $alert['what'] }}</div>
                    </div>
                    <div class="text-right">
                        {!! \App\Support\Ui::expiry($alert['date']) !!}
                    </div>
                </div>
            @empty
                <p class="px-4 py-6 text-sm text-gray-500">Nothing expiring soon. 🎉</p>
            @endforelse
        </section>

        <section class="rounded-lg border border-gray-200 bg-white">
            <h2 class="border-b border-gray-200 px-4 py-3 font-semibold">Scheduled services</h2>

            @forelse ($upcomingServices as $service)
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 text-sm last:border-0">
                    <div>
                        <div class="font-medium">{{ $service->vehicle->registration_no }}</div>
                        <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::headline($service->type) }} · {{ $service->workshop ?? '—' }}</div>
                    </div>
                    <div>{{ $service->service_date->format('d M Y') }}</div>
                </div>
            @empty
                <p class="px-4 py-6 text-sm text-gray-500">No scheduled services.</p>
            @endforelse
        </section>

        <section class="rounded-lg border border-gray-200 bg-white lg:col-span-2">
            <h2 class="border-b border-gray-200 px-4 py-3 font-semibold">Recent fuel entries</h2>

            @forelse ($recentFuel as $log)
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 text-sm last:border-0">
                    <div>
                        <div class="font-medium">{{ $log->vehicle->registration_no }}</div>
                        <div class="text-xs text-gray-500">{{ $log->station ?? '—' }}</div>
                    </div>
                    <div class="text-right">
                        <div>{{ number_format($log->liters, 2) }} L · {{ number_format($log->total_cost, 2) }}</div>
                        <div class="text-xs text-gray-500">{{ $log->fuel_date->format('d M Y') }}</div>
                    </div>
                </div>
            @empty
                <p class="px-4 py-6 text-sm text-gray-500">No fuel entries yet.</p>
            @endforelse
        </section>
    </div>
@endsection
