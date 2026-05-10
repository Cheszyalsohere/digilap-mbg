<?php

namespace App\Http\Controllers;

use App\Concerns\LogsActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    use LogsActivity;

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [], [
            'username' => 'username',
            'password' => 'password',
        ]);

        $key = 'login.' . Str::lower($credentials['username']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withInput($request->only('username'))
                ->with('login_lockout', true)
                ->withErrors([
                    'username' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
                ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($key, 300);
            $remaining = 5 - RateLimiter::attempts($key);

            if ($remaining > 0) {
                return back()
                    ->withInput($request->only('username'))
                    ->with('login_remaining', $remaining)
                    ->withErrors([
                        'username' => "Username atau password salah. Sisa percobaan: {$remaining}x",
                    ]);
            }

            return back()
                ->withInput($request->only('username'))
                ->with('login_lockout', true)
                ->withErrors([
                    'username' => 'Terlalu banyak percobaan login. Coba lagi dalam 5 menit.',
                ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        $this->logActivity('Login ke sistem');

        return $this->redirectByRole();
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->logActivity('Logout dari sistem');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole(): RedirectResponse
    {
        $user = Auth::user();
        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'sppg'  => redirect()->intended(route('sppg.dashboard')),
            default => redirect()->intended(route('siswa.dashboard')),
        };
    }
}
