@extends('reports.pdf.layout')

@section('content')
    <table class="data">
        <thead>
        <tr>
            <th>#</th>
            <th>Service Date</th>
            <th>Vehicle</th>
            <th>Type</th>
            <th>Description</th>
            <th>Workshop</th>
            <th class="right">Odometer</th>
            <th>Next Service</th>
            <th>Status</th>
            <th class="right">Cost</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($records as $m)
            <tr class="{{ $loop->even ? 'alt' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td>{!! \App\Support\Ui::date($m->service_date) !!}</td>
                <td>{{ $m->vehicle->registration_no }}</td>
                <td>{{ \Illuminate\Support\Str::headline($m->type) }}</td>
                <td>{{ $m->description ?? '—' }}</td>
                <td>{{ $m->workshop ?? '—' }}</td>
                <td class="right">{{ $m->odometer !== null ? number_format($m->odometer) . ' km' : '—' }}</td>
                <td>{!! \App\Support\Ui::date($m->next_service_date) !!}</td>
                <td>{{ \Illuminate\Support\Str::headline($m->status) }}</td>
                <td class="right">{{ \App\Support\Ui::money($m->cost) }}</td>
            </tr>
        @empty
            <tr><td colspan="10" class="empty">No maintenance records found.</td></tr>
        @endforelse
        </tbody>
        @if ($records->isNotEmpty())
            <tfoot>
            <tr>
                <td colspan="9" class="right">Total cost ({{ $records->count() }} records)</td>
                <td class="right">{{ \App\Support\Ui::money($totalCost) }}</td>
            </tr>
            </tfoot>
        @endif
    </table>
@endsection
