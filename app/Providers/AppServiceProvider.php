<?php

namespace App\Providers;

use App\Service\OrderServiceProfile;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OrderServiceProfile::class, function ($app) {
            return new OrderServiceProfile();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
