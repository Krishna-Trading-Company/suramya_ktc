<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $permissionsJson = auth()->check() ? (auth()->user()->role->permission ?? '{}') : '{}';
            $permission = json_decode($permissionsJson, true);
            $view->with('permission', $permission);
        });
    }
}
