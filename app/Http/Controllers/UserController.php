<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\SecurityEventLogger;
use App\Support\Ui;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index');
    }

    public function create(): View
    {
        return view('users.form', $this->formData(new User(['role' => 'staff', 'is_active' => true])));
    }

    public function store(Request $request, SecurityEventLogger $securityEvents): RedirectResponse
    {
        $data = $request->validate($this->rules());

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $securityEvents->record('identity.user_created', 'success', $request->user(), [
            'target_user_id' => $user->getKey(),
            'assigned_role' => $user->role,
            'new_active' => $user->is_active,
        ]);

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user): View
    {
        return view('users.form', $this->formData($user));
    }

    public function update(Request $request, User $user, SecurityEventLogger $securityEvents): RedirectResponse
    {
        $data = $request->validate($this->rules($user));
        $previousRole = $user->role;
        $previousActive = (bool) $user->is_active;
        $passwordChanged = ! empty($data['password']);

        $attributes = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        // An admin cannot demote or disable their own account (would lock everyone out).
        if ($user->id === $request->user()->id) {
            $attributes['role'] = $user->role;
            $attributes['is_active'] = true;
        }

        if (! empty($data['password'])) {
            $attributes['password'] = Hash::make($data['password']);
        }

        $user->update($attributes);

        $securityEvents->record('identity.user_updated', 'success', $request->user(), [
            'target_user_id' => $user->getKey(),
            'previous_role' => $previousRole,
            'new_role' => $user->role,
            'previous_active' => $previousActive,
            'new_active' => $user->is_active,
            'password_changed' => $passwordChanged,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    private function formData(User $user): array
    {
        return [
            'user' => $user,
            'roles' => Ui::options(User::ROLES),
        ];
    }

    private function rules(?User $user = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'role' => ['required', Rule::in(User::ROLES)],
            // Required when creating; optional (leave blank to keep) when editing.
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(8)],
        ];
    }
}
