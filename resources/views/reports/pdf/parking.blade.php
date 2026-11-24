@extends('reports.pdf.layout')

@section('content')
    @php $ui = \App\Support\Ui::class; @endphp

    <h2>Current occupancy</h2>
    <table class="data">
        <thead>
        <tr>
            <th>Floor</th>
            <th>Slot</th>
            <th>State</th>
            <th>Vehicle</th>
            <th>Driver</th>
            <th>Parked since</th>
            <th>Duration</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($slots as $slot)
            @php $record = $slot->currentRecord; @endphp
            <tr class="{{ $loop->even ? 'alt' : '' }}">
                <td>{{ $slot->floor }}</td>
                <td><strong>{{ $slot->code }}</strong></td>
                <td class="{{ $record ? 'red' : ($slot->is_active ? '' : 'muted') }}">
                    {{ $record ? 'Occupied' : ($slot->is_active ? 'Free' : 'Out of service') }}
                </td>
                <td>{{ $record?->vehicle ? $record->vehicle->registration_no . ' (' . $record->vehicle->brand . ' ' . $record->vehicle->model . ')' : ($record?->detected_plate ?? ($record ? 'Unknown vehicle' : '—')) }}</td>
                <td>{{ $record?->vehicle->driver?->name ?? '—' }}</td>
                <td>{{ $record ? $record->parked_at->format('d M Y H:i') : '—' }}</td>
                <td>{{ $record ? $ui::duration($record->parked_at) : '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty">No parking slots defined.</td></tr>
        @endforelse
        </tbody>
    </table>

    <p class="summary">
        Slots: <strong>{{ $summary['total'] }}</strong> ·
        Occupied: <strong>{{ $summary['occupied'] }}</strong> ·
        Free: <strong>{{ $summary['free'] }}</strong> ·
        Out of service: <strong>{{ $summary['out_of_service'] }}</strong>
    </p>

    <h2>Parking history</h2>
    <table class="data">
        <thead>
        <tr>
            <th>#</th>
            <th>Vehicle</th>
            <th>Floor</th>
            <th>Slot</th>
            <th>Parked at</th>
            <th>Released at</th>
            <th>Duration</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($history as $row)
            <tr class="{{ $loop->even ? 'alt' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->vehicle?->registration_no ?? $row->detected_plate ?? 'Unknown vehicle' }}</td>
                <td>{{ $row->slot->floor }}</td>
                <td>{{ $row->slot->code }}</td>
                <td>{{ $row->parked_at->format('d M Y H:i') }}</td>
                <td>{{ $row->released_at ? $row->released_at->format('d M Y H:i') : 'Still parked' }}</td>
                <td>{{ $ui::duration($row->parked_at, $row->released_at) }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty">No parking history in this period.</td></tr>
        @endforelse
        </tbody>
    </table>

    @if ($history->count() === 500)
        <p class="summary muted">Showing the latest 500 entries. Narrow the date range to see older ones.</p>
    @endif
@endsection
