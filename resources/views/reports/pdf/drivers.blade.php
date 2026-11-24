@extends('reports.pdf.layout')

@section('content')
    <table class="data">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>License No</th>
            <th>License Expiry</th>
            <th class="right">Vehicles</th>
            <th>Address</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($drivers as $d)
            <tr class="{{ $loop->even ? 'alt' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->name }}</td>
                <td>{{ $d->phone }}</td>
                <td>{{ $d->email ?? '—' }}</td>
                <td>{{ $d->license_number }}</td>
                <td class="{{ \App\Support\Ui::expiryClass($d->license_expiry) }}">{!! \App\Support\Ui::date($d->license_expiry) !!}</td>
                <td class="right">{{ $d->vehicles_count }}</td>
                <td>{{ $d->address ?? '—' }}</td>
                <td>{{ $d->is_active ? 'Active' : 'Inactive' }}</td>
            </tr>
        @empty
            <tr><td colspan="9" class="empty">No drivers found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <p class="summary">Total drivers: <strong>{{ $drivers->count() }}</strong>.</p>
@endsection
