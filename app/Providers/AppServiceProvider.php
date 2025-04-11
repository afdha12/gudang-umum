<?php

namespace App\Providers;

use Spatie\Browsershot\Browsershot;
use App\View\Composers\UserComposer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\View\Composers\SidebarComposer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan View Composer untuk sidebar
        View::composer('layouts.nav.sidebar', SidebarComposer::class);

        // Bagikan variabel currentUser ke semua view
        View::composer('*', function ($view) {
            $view->with('currentUser', Auth::user());
        });

        if (env('PUPPETEER_EXECUTABLE_PATH')) {
            putenv('PUPPETEER_EXECUTABLE_PATH=' . env('PUPPETEER_EXECUTABLE_PATH'));
        }
    }
}
