<?php

namespace App\Providers;

use App\Service\OrderServiceProfile;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

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
        // Ensure public/storage symlink exists in all environments
        try {
            $publicStorage = public_path('storage');
            if (!is_link($publicStorage) && !file_exists($publicStorage)) {
                Artisan::call('storage:link');
            }
        } catch (\Throwable $e) {
            // no-op in case of restricted permissions
        }
    }
}
