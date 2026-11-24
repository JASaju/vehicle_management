<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Sign in') · Vehicle Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-50 px-4 text-gray-900">
    <div class="w-full max-w-sm">
        <div class="mb-6 text-center text-2xl font-semibold text-indigo-600">🚗 Vehicle Manager</div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            @yield('content')
        </div>
    </div>
</body>
</html>
