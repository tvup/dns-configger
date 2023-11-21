<?php

namespace App\Providers;

use App\Services\DigitalOceanService;
use App\Services\Interfaces\CloudServiceProviderServiceInterface;
use App\Services\Mocks\CloudServiceProviderServiceMock;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (app()->environment('local', 'production', 'staging')) {
            $this->app->bind(CloudServiceProviderServiceInterface::class, DigitalOceanService::class);
        } else {
            $this->app->bind(CloudServiceProviderServiceInterface::class, CloudServiceProviderServiceMock::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
