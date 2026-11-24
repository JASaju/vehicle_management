@extends('layouts.app')

@php
    $editing = $user->exists;
    $isSelf = $editing && $user->id === auth()->id();
@endphp

@section('title', $editing ? 'Edit user' : 'Add user')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold">{{ $editing ? 'Edit user · ' . $user->name : 'Add user' }}</h1>

    <form method="POST" action="{{ $editing ? route('users.update', $user) : route('users.store') }}"
          class="max-w-2xl space-y-6 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="grid gap-4 md:grid-cols-2">
            <x-form-input name="name" label="Full name" :value="$user->name" required />
            <x-form-input name="email" label="Email" type="email" :value="$user->email" required />

            <div>
                <x-form-select name="role" label="Role" :options="$roles" :value="$user->role" required :disabled="$isSelf" />
                {{-- A disabled select is not submitted, so send the current role explicitly. --}}
                @if ($isSelf)
                    <input type="hidden" name="role" value="{{ $user->role }}">
                @endif
            </div>

            <div></div>

            <x-form-input name="password" :label="$editing ? 'New password (leave blank to keep)' : 'Password'" type="password" :required="! $editing" autocomplete="new-password" />
            <x-form-input name="password_confirmation" label="Confirm password" type="password" autocomplete="new-password" />
        </div>

        <div>
            <label class="flex items-center gap-2 text-sm">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600"
                       @checked(old('is_active', $user->is_active ?? true)) @disabled($isSelf)>
                Account active (disabled users cannot log in)
            </label>

            @if ($isSelf)
                <p class="mt-1 text-xs text-gray-500">You cannot change your own role or disable your own account.</p>
            @endif
        </div>

        <div class="flex gap-3">
            <button class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                {{ $editing ? 'Update' : 'Save' }}
            </button>
            <a href="{{ route('users.index') }}" class="rounded-md border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
@endsection
