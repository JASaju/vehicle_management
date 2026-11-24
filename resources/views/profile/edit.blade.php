@extends('layouts.app')

@section('title', 'My profile')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold">My profile</h1>

    <form method="POST" action="{{ route('profile.update') }}"
          class="max-w-2xl space-y-6 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @method('PUT')

        <div class="grid gap-4 md:grid-cols-2">
            <x-form-input name="name" label="Full name" :value="$user->name" required />
            <x-form-input name="email" label="Email" type="email" :value="$user->email" required />
        </div>

        <div class="border-t border-gray-100 pt-4">
            <h2 class="mb-1 font-semibold">Change password</h2>
            <p class="mb-3 text-xs text-gray-500">Leave these blank to keep your current password.</p>

            <div class="grid gap-4 md:grid-cols-3">
                <x-form-input name="current_password" label="Current password" type="password" autocomplete="current-password" />
                <x-form-input name="password" label="New password" type="password" autocomplete="new-password" />
                <x-form-input name="password_confirmation" label="Confirm new password" type="password" autocomplete="new-password" />
            </div>
        </div>

        <p class="text-xs text-gray-500">Role: <strong>{{ ucfirst($user->role) }}</strong></p>

        <button class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save changes</button>
    </form>
@endsection
