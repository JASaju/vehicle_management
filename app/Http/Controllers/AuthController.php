<?php

namespace App\Http\Controllers;

use App\Support\SecurityEventLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request, SecurityEventLogger $securityEvents): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 5 attempts per minute per email + IP.
        $throttleKey = Str::lower($credentials['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $securityEvents->record('authentication.login', 'failure', labels: ['reason' => 'rate_limited']);

            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Try again in '.RateLimiter::availableIn($throttleKey).' seconds.',
            ]);
        }

        // Disabled users (is_active = 0) can never log in.
        $attempt = Auth::attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password'], 'is_active' => true],
            $request->boolean('remember')
        );

        if (! $attempt) {
            RateLimiter::hit($throttleKey, 60);
            $securityEvents->record('authentication.login', 'failure', labels: ['reason' => 'invalid_credentials']);

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records, or the account is disabled.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        $securityEvents->record('authentication.login', 'success', $request->user());

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request, SecurityEventLogger $securityEvents): RedirectResponse
    {
        $securityEvents->record('authentication.logout', 'success', $request->user());
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
