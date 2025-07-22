<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        // Use View::composer to share $user and $profile specifically with the 'admin.layout.navbar' view
        View::composer('layouts.navbar', function ($view) {
            $view->with('user', Auth::user());
        });

        View::composer('layouts.sidebar', function ($view) {
            $view->with('user', Auth::user());
        });

        $profile = \App\Models\Profile::first();
        View::share([
            'profile' => $profile,
        ]);
    }
}
