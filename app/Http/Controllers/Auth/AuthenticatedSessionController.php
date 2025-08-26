<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->role . '.dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi pengguna
        $request->authenticate();
        $request->session()->regenerate();

        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Pastikan user yang login memiliki role yang valid
        if (!$user || !isset($user->role)) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun tidak memiliki role yang valid.');
        }

        // Memeriksa apakah password yang dimasukkan pengguna sesuai dengan hash di database
        if (Hash::check('Hermina32', $user->password)) {
            return redirect()->route('change-password.edit', $user->id); // Arahkan ke halaman ganti password
        }

        // Redirect ke halaman yang sesuai berdasarkan peran
        return redirect()->intended(route($user->role . '.dashboard'));
            // ->with('success', 'Selamat datang, ' . $user->name . '!');
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

    // protected function redirectTo($user)
    // {
    //     return match ($user->role) {
    //         'admin' => route('admin.dashboard'),
    //         'user' => route('user.dashboard'),
    //         'manager' => route('manager.dashboard'),
    //         'coo' => route('coo.dashboard'),
    //         default => '/',
    //     };
    // }
}
