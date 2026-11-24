<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') · Vehicle Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>
<body class="min-h-screen bg-gray-50 text-gray-900">

<nav class="border-b border-gray-200 bg-white" aria-label="Primary navigation">
    @php
        $links = [
            ['Dashboard', 'dashboard', 'dashboard'],
            ['Vehicles', 'vehicles.index', 'vehicles.*'],
            ['Drivers', 'drivers.index', 'drivers.*'],
            ['Maintenance', 'maintenances.index', 'maintenances.*'],
            ['Fuel Logs', 'fuel-logs.index', 'fuel-logs.*'],
            ['Parking', 'parking.index', 'parking*'],
            ['Reports', 'reports.index', 'reports.*'],
        ];

        if (auth()->user()?->isAdmin()) {
            $links[] = ['Users', 'users.index', 'users.*'];
            $links[] = ['Security Logs', 'security-logs.index', 'security-logs.*'];
        }

        $mobileLinks = [...$links, ['Profile', 'profile.edit', 'profile.*']];
    @endphp

    <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3">
        <a href="{{ route('dashboard') }}" class="shrink-0 text-lg font-semibold text-indigo-600">🚗 Vehicle Manager</a>

        <details class="relative ml-auto xl:hidden">
            <summary class="flex cursor-pointer list-none items-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 0 1 1-1h12a1 1 0 1 1 0 2H4a1 1 0 0 1-1-1Zm0 5a1 1 0 0 1 1-1h12a1 1 0 1 1 0 2H4a1 1 0 0 1-1-1Zm0 5a1 1 0 0 1 1-1h12a1 1 0 1 1 0 2H4a1 1 0 0 1-1-1Z" clip-rule="evenodd" />
                </svg>
                Menu
            </summary>
            <div class="absolute right-0 z-20 mt-2 flex w-56 flex-col gap-1 rounded-lg border border-gray-200 bg-white p-2 shadow-lg">
                @foreach ($mobileLinks as [$label, $route, $pattern])
                    <a href="{{ route($route) }}"
                       class="rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs($pattern) ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        {{ $label }}
                    </a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 pt-1">
                    @csrf
                    <button class="w-full rounded-md px-3 py-2 text-left text-sm font-medium text-gray-700 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </details>

        <div class="hidden min-w-0 flex-1 items-center justify-center gap-1 text-sm xl:flex">
            @foreach ($links as [$label, $route, $pattern])
                <a href="{{ route($route) }}"
                   class="whitespace-nowrap rounded-md px-2.5 py-1.5 font-medium {{ request()->routeIs($pattern) ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="ml-auto hidden shrink-0 items-center gap-3 text-sm xl:flex">
            <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-900">
                {{ auth()->user()->name }}
                <span class="text-xs text-gray-400">({{ auth()->user()->role }})</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-md border border-gray-300 px-3 py-1.5 font-medium text-gray-700 hover:bg-gray-50">Logout</button>
            </form>
        </div>

    </div>
</nav>

<main class="mx-auto max-w-7xl px-4 py-6">
    @if (session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

@stack('scripts')
@livewireScripts

</body>
</html>
