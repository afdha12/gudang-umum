<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedirectIfNotFound
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (NotFoundHttpException $exception) {
            // Redirect ke dashboard sesuai role jika user login
            if (auth()->check()) {
                return redirect()->route(auth()->user()->role . '.dashboard');
            }
            
            // Jika tidak login, redirect ke halaman login
            return redirect()->route('login');
        }
    }
}
