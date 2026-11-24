@extends('reports.pdf.layout')

@section('content')
    <table class="data">
        <thead>
        <tr>
            <th>#</th>
            <th>Type</th>
            <th>Vehicle / Driver</th>
            <th>Document</th>
            <th>Expiry Date</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($rows as $row)
            @php
                $daysLeft = $row['days_left'];
                $class = $daysLeft < 0 ? 'red' : 'yellow';
            @endphp
            <tr class="{{ $loop->even ? 'alt' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row['kind'] }}</td>
                <td>{{ $row['subject'] }}</td>
                <td>{{ $row['document'] }}</td>
                <td>{!! \App\Support\Ui::date($row['date']) !!}</td>
                <td class="{{ $class }}">
                    @if ($daysLeft < 0)
                        Expired {{ abs($daysLeft) }} {{ \Illuminate\Support\Str::plural('day', abs($daysLeft)) }} ago
                    @elseif ($daysLeft === 0)
                        Expires today
                    @else
                        {{ $daysLeft }} {{ \Illuminate\Support\Str::plural('day', $daysLeft) }} left
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="empty">Nothing is expired or expiring in this period.</td></tr>
        @endforelse
        </tbody>
    </table>

    <p class="summary">
        Expired: <strong>{{ $rows->where('days_left', '<', 0)->count() }}</strong> ·
        Expiring soon: <strong>{{ $rows->where('days_left', '>=', 0)->count() }}</strong>.
        Inactive and sold vehicles, and inactive drivers, are not included.
    </p>
@endsection
