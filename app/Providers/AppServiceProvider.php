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

        // Share the profile data with all views
        // This assumes you have a Profile model and it retrieves the first profile
        // Adjust the logic as necessary to fit your application's needs
        // disable the line below if you don't have a Profile model for the first time install and run the migration
        // \App\Models\Profile::first() will return null if no profile exists, so
        $profile = \App\Models\Profile::first();
        View::share([
            'profile' => $profile,
        ]);
    }
}
