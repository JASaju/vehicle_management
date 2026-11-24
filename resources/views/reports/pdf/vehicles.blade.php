@extends('reports.pdf.layout')

@section('content')
    <table class="data">
        <thead>
        <tr>
            <th>#</th>
            <th>Reg. No</th>
            <th>Vehicle</th>
            <th>Type</th>
            <th>Driver</th>
            <th>Fuel</th>
            <th class="right">Odometer</th>
            <th>Insurance</th>
            <th>Tax Token</th>
            <th>Fitness</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($vehicles as $v)
            <tr class="{{ $loop->even ? 'alt' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $v->registration_no }}</td>
                <td>{{ $v->brand }} {{ $v->model }} ({{ $v->year }})</td>
                <td>{{ $v->type->name }}</td>
                <td>{{ $v->driver?->name ?? '—' }}</td>
                <td>{{ ucfirst($v->fuel_type) }}</td>
                <td class="right">{{ number_format($v->odometer) }} km</td>
                <td class="{{ \App\Support\Ui::expiryClass($v->insurance_expiry) }}">{!! \App\Support\Ui::date($v->insurance_expiry) !!}</td>
                <td class="{{ \App\Support\Ui::expiryClass($v->tax_token_expiry) }}">{!! \App\Support\Ui::date($v->tax_token_expiry) !!}</td>
                <td class="{{ \App\Support\Ui::expiryClass($v->fitness_expiry) }}">{!! \App\Support\Ui::date($v->fitness_expiry) !!}</td>
                <td>{{ \Illuminate\Support\Str::headline($v->status) }}</td>
            </tr>
        @empty
            <tr><td colspan="11" class="empty">No vehicles found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <p class="summary">Total vehicles: <strong>{{ $vehicles->count() }}</strong>.
        Red dates are expired, yellow dates expire within 30 days.</p>
@endsection
