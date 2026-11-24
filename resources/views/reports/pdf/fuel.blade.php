@extends('reports.pdf.layout')

@section('content')
    <table class="data">
        <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Vehicle</th>
            <th>Driver</th>
            <th>Station</th>
            <th class="right">Odometer</th>
            <th class="right">Liters</th>
            <th class="right">Price / L</th>
            <th class="right">Total</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($logs as $log)
            <tr class="{{ $loop->even ? 'alt' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td>{!! \App\Support\Ui::date($log->fuel_date) !!}</td>
                <td>{{ $log->vehicle->registration_no }}</td>
                <td>{{ $log->driver?->name ?? '—' }}</td>
                <td>{{ $log->station ?? '—' }}</td>
                <td class="right">{{ $log->odometer !== null ? number_format($log->odometer) . ' km' : '—' }}</td>
                <td class="right">{{ number_format((float) $log->liters, 2) }}</td>
                <td class="right">{{ \App\Support\Ui::money($log->price_per_liter) }}</td>
                <td class="right">{{ \App\Support\Ui::money($log->total_cost) }}</td>
            </tr>
        @empty
            <tr><td colspan="9" class="empty">No fuel records found.</td></tr>
        @endforelse
        </tbody>
        @if ($logs->isNotEmpty())
            <tfoot>
            <tr>
                <td colspan="6" class="right">Total ({{ $logs->count() }} entries)</td>
                <td class="right">{{ number_format((float) $totalLiters, 2) }}</td>
                <td></td>
                <td class="right">{{ \App\Support\Ui::money($totalCost) }}</td>
            </tr>
            </tfoot>
        @endif
    </table>
@endsection
