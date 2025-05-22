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
        $user = Auth::user();

        // Cek jika user belum ganti password
        if ($user && !$user->password_changed) {
            // Abaikan jika user sedang akses/change password atau logout
            if (!$request->is('change-password*') && !$request->is('logout')) {
                return redirect()->route('change-password.edit', $user->id)
                    ->with('warning', 'Silakan ganti password terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
