@extends('reports.pdf.layout')

@section('content')
    @php $ui = \App\Support\Ui::class; @endphp

    <h2>Details</h2>
    <table class="info">
        <tr>
            <td class="k">Registration no</td><td>{{ $vehicle->registration_no }}</td>
            <td class="k">Type</td><td>{{ $vehicle->type->name }}</td>
        </tr>
        <tr>
            <td class="k">Brand / model</td><td>{{ $vehicle->brand }} {{ $vehicle->model }}</td>
            <td class="k">Year / color</td><td>{{ $vehicle->year }} / {{ $vehicle->color ?? '—' }}</td>
        </tr>
        <tr>
            <td class="k">Chassis no</td><td>{{ $vehicle->chassis_no ?? '—' }}</td>
            <td class="k">Engine no</td><td>{{ $vehicle->engine_no ?? '—' }}</td>
        </tr>
        <tr>
            <td class="k">Fuel type</td><td>{{ ucfirst($vehicle->fuel_type) }}</td>
            <td class="k">Seats</td><td>{{ $vehicle->seat_capacity }}</td>
        </tr>
        <tr>
            <td class="k">Odometer</td><td>{{ number_format($vehicle->odometer) }} km</td>
            <td class="k">Status</td><td>{{ \Illuminate\Support\Str::headline($vehicle->status) }}</td>
        </tr>
        <tr>
            <td class="k">Driver</td><td>{{ $vehicle->driver?->name ?? '—' }}</td>
            <td class="k">Purchased</td>
            <td>{!! $ui::date($vehicle->purchase_date) !!}
                @if ($vehicle->purchase_price) · {{ $ui::money($vehicle->purchase_price) }} @endif</td>
        </tr>
        <tr>
            <td class="k">Insurance expiry</td><td class="{{ $ui::expiryClass($vehicle->insurance_expiry) }}">{!! $ui::date($vehicle->insurance_expiry) !!}</td>
            <td class="k">Tax token expiry</td><td class="{{ $ui::expiryClass($vehicle->tax_token_expiry) }}">{!! $ui::date($vehicle->tax_token_expiry) !!}</td>
        </tr>
        <tr>
            <td class="k">Fitness expiry</td><td class="{{ $ui::expiryClass($vehicle->fitness_expiry) }}">{!! $ui::date($vehicle->fitness_expiry) !!}</td>
            <td class="k">Notes</td><td>{{ $vehicle->notes ?? '—' }}</td>
        </tr>
    </table>

    <h2>Maintenance history</h2>
    <table class="data">
        <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Description</th>
            <th>Workshop</th>
            <th>Status</th>
            <th class="right">Cost</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($maintenances as $m)
            <tr class="{{ $loop->even ? 'alt' : '' }}">
                <td>{!! $ui::date($m->service_date) !!}</td>
                <td>{{ \Illuminate\Support\Str::headline($m->type) }}</td>
                <td>{{ $m->description ?? '—' }}</td>
                <td>{{ $m->workshop ?? '—' }}</td>
                <td>{{ \Illuminate\Support\Str::headline($m->status) }}</td>
                <td class="right">{{ $ui::money($m->cost) }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="empty">No maintenance records.</td></tr>
        @endforelse
        </tbody>
        @if ($maintenances->isNotEmpty())
            <tfoot>
            <tr><td colspan="5" class="right">Total</td><td class="right">{{ $ui::money($maintenanceTotal) }}</td></tr>
            </tfoot>
        @endif
    </table>

    <h2>Fuel history</h2>
    <table class="data">
        <thead>
        <tr>
            <th>Date</th>
            <th>Driver</th>
            <th>Station</th>
            <th class="right">Liters</th>
            <th class="right">Price / L</th>
            <th class="right">Total</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($fuelLogs as $log)
            <tr class="{{ $loop->even ? 'alt' : '' }}">
                <td>{!! $ui::date($log->fuel_date) !!}</td>
                <td>{{ $log->driver?->name ?? '—' }}</td>
                <td>{{ $log->station ?? '—' }}</td>
                <td class="right">{{ number_format((float) $log->liters, 2) }}</td>
                <td class="right">{{ $ui::money($log->price_per_liter) }}</td>
                <td class="right">{{ $ui::money($log->total_cost) }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="empty">No fuel records.</td></tr>
        @endforelse
        </tbody>
        @if ($fuelLogs->isNotEmpty())
            <tfoot>
            <tr>
                <td colspan="3" class="right">Total</td>
                <td class="right">{{ number_format((float) $fuelLiters, 2) }}</td>
                <td></td>
                <td class="right">{{ $ui::money($fuelTotal) }}</td>
            </tr>
            </tfoot>
        @endif
    </table>

    <p class="summary">Running cost (fuel + maintenance): <strong>{{ $ui::money($fuelTotal + $maintenanceTotal) }}</strong></p>
@endsection
