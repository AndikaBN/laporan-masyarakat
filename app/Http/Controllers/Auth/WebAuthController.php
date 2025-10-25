<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class WebAuthController extends Controller
{
    /**
     * Show login form.
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Handle login attempt with rate limiting.
     */
    public function login(Request $request)
    {
        $key = 'login_' . $request->ip();

        // Check rate limit: 5 attempts per minute
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => "Too many login attempts. Please try again in {$seconds} seconds."]);
        }

        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            // Check if user is admin (super_admin or agency_admin)
            if (!$user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Invalid credentials.']);
            }

            // Role-based redirect
            if ($user->isSuperAdmin()) {
                return redirect()->route('super.dashboard');
            }

            if ($user->isAgencyAdmin()) {
                return redirect()->route('agency.dashboard');
            }
        }

        // Increment failed attempt
        RateLimiter::hit($key, 60);

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Invalid credentials.']);
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show')->with('status', 'You have been logged out.');
    }
}
