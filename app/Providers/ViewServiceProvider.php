<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $role = Auth::check() ? Auth::user()->role : null;

            // Pastikan hanya mengirim jika user login
            if (Auth::check()) {
                $view->with('role', $role);
            }
        });


    }
}
