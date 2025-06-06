<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ClusteringService;

class ClusteringServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ClusteringService::class, function ($app) {
            return new ClusteringService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}