@extends('reports.pdf.layout')

@section('content')
    <table class="data">
        <thead>
        <tr>
            <th>#</th>
            <th>Reg. No</th>
            <th>Vehicle</th>
            <th class="right">Fuel (L)</th>
            <th class="right">Fuel Cost</th>
            <th class="right">Maintenance Cost</th>
            <th class="right">Total Cost</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($vehicles as $v)
            <tr class="{{ $loop->even ? 'alt' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $v->registration_no }}</td>
                <td>{{ $v->brand }} {{ $v->model }}</td>
                <td class="right">{{ number_format($v->fuel_liters, 2) }}</td>
                <td class="right">{{ \App\Support\Ui::money($v->fuel_cost) }}</td>
                <td class="right">{{ \App\Support\Ui::money($v->maintenance_cost) }}</td>
                <td class="right">{{ \App\Support\Ui::money($v->total_cost) }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty">No vehicles found.</td></tr>
        @endforelse
        </tbody>
        @if ($vehicles->isNotEmpty())
            <tfoot>
            <tr>
                <td colspan="3" class="right">Grand total</td>
                <td class="right">{{ number_format($totals['liters'], 2) }}</td>
                <td class="right">{{ \App\Support\Ui::money($totals['fuel']) }}</td>
                <td class="right">{{ \App\Support\Ui::money($totals['maintenance']) }}</td>
                <td class="right">{{ \App\Support\Ui::money($totals['all']) }}</td>
            </tr>
            </tfoot>
        @endif
    </table>
@endsection
