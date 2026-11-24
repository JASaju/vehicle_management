<?php

namespace App\Http\Controllers;

use App\Support\SecurityEventLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request, SecurityEventLogger $securityEvents): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            // Only checked when a new password is being set.
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $passwordChanged = ! empty($data['password']);
        $attributes = ['name' => $data['name'], 'email' => $data['email']];

        if ($passwordChanged) {
            $attributes['password'] = Hash::make($data['password']);
        }

        $user->update($attributes);
        $securityEvents->record('identity.profile_updated', 'success', $user, [
            'target_user_id' => $user->getKey(),
            'password_changed' => $passwordChanged,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Profile updated.');
    }
}
