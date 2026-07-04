<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Cek apakah akun dinonaktifkan
        if (! auth()->user()->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akses ditolak! Akun Anda telah dinonaktifkan oleh Super Admin.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Redirect spesifik berdasarkan Role
        $role = auth()->user()->role;
        
        if ($role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Jika user biasa
        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}