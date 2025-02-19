<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Jika user belum mengganti password, arahkan ke halaman ganti password
        if ($user && !$user->password_changed && !$request->is('change-password')) {
            return redirect()->route('change-password.edit', $user->id)->with('warning', 'Silakan ganti password terlebih dahulu.');
        }

        return $next($request);
    }
}
