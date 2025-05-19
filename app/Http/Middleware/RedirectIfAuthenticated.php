<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Jika password belum diganti, arahkan ke halaman ganti password
            if (!$user->password_changed) {
                return redirect()->route('change-password.edit', $user->id);
            }

            // Jika password sudah diganti, arahkan ke dashboard sesuai role
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'manager' => redirect()->route('manager.dashboard'),
                'user' => redirect()->route('user.dashboard'),
                'coo' => redirect()->route('coo.dashboard'),
                default => redirect('/'),
            };
        }

        return $next($request);
    }
}