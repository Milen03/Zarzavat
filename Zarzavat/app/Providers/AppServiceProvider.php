<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\OrderService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->singleton(OrderService::class, function ($app) {
            return new OrderService();
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
