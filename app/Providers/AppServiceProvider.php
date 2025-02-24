<?php

namespace App\Providers;

use App\Models\BaseDnsRecord;
use App\Models\DnsRecord;
use App\Models\HetznerDnsRecord;
use App\Services\DigitalOceanService;
use App\Services\HetznerService;
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
            if (config('services.provider') === 'hetzner') {
                $this->app->bind(CloudServiceProviderServiceInterface::class, HetznerService::class);
            } else {
                $this->app->bind(CloudServiceProviderServiceInterface::class, DigitalOceanService::class);
            }
        } else {
            $this->app->bind(CloudServiceProviderServiceInterface::class, CloudServiceProviderServiceMock::class);
        }

        $this->app->bind(BaseDnsRecord::class, function ($app) {
            return config('services.provider') === 'hetzner' ? new HetznerDnsRecord() : new DnsRecord();
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
