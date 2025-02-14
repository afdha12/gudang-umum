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
        // Kirim data role ke semua view yang menyertakan 'nav.sidebar'
        View::composer('nav.sidebar', function ($view) {
            $role = Auth::check() ? Auth::user()->role : null;
            $view->with('role', $role);
        });
    }
}
