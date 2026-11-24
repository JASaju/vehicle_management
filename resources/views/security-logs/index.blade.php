@extends('layouts.app')

@section('title', 'Security Logs')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Security Logs</h1>
        <p class="mt-1 text-sm text-gray-600">Read-only view of the latest 200 SIEM-ready security events.</p>
    </div>

    @if ($malformed_lines > 0)
        <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            {{ $malformed_lines }} malformed or truncated log line(s) were skipped.
        </div>
    @endif

    @if ($events === [])
        <div class="rounded-lg border border-gray-200 bg-white p-6 text-sm text-gray-600">
            No security events have been recorded yet.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Timestamp</th>
                        <th class="px-4 py-3">Level</th>
                        <th class="px-4 py-3">Event</th>
                        <th class="px-4 py-3">Outcome</th>
                        <th class="px-4 py-3">User ID</th>
                        <th class="px-4 py-3">Source IP</th>
                        <th class="px-4 py-3">Request</th>
                        <th class="px-4 py-3">Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($events as $event)
                        <tr class="align-top text-gray-700">
                            <td class="whitespace-nowrap px-4 py-3">{{ $event['timestamp'] }}</td>
                            <td class="px-4 py-3">{{ $event['level'] ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ $event['action'] ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $event['outcome'] ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $event['user_id'] ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $event['ip'] ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $event['method'] ?? '—' }} {{ $event['path'] ?? '' }}</td>
                            <td class="px-4 py-3">{{ $event['reason'] ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
