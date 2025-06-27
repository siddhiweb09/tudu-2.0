<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        View::composer('layouts.frame', function ($view) {
            $user = Auth::user();
            $view->with([
                'user' => $user,
            ]);
        });

        View::composer('layouts.innerFrame', function ($view) {
            $user = Auth::user();
            $view->with([
                'user' => $user,
            ]);
        });

        View::composer('layouts.personalTaskFrame', function ($view) {
            $user = Auth::user();
            $view->with([
                'user' => $user,
            ]);
        });

    }
}
