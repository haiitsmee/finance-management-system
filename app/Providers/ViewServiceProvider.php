<?php

namespace App\Providers;

use App\Models\Business;
use Auth;
use Illuminate\Support\ServiceProvider;
use View;

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
        View::composer('components.sidebar', function ($view) {

            if (Auth::check()) {
                $businesses = Business::select('id', 'name', 'slug','is_visible')->get();
            }

            $view->with('businesses', $businesses);
        });

        View::composer('components.admin-sidebar', function ($view) {

            if (Auth::check()) {
                $businesses = Auth::user()
                    ->businesses()
                    ->select('businesses.id', 'businesses.slug', 'businesses.name')
                    ->get();
            }

            $view->with('businesses', $businesses);
        });
    }
}
