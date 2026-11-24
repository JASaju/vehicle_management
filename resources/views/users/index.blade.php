@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Users</h1>
        <a href="{{ route('users.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">+ Add user</a>
    </div>

    <livewire:user-table />
@endsection
