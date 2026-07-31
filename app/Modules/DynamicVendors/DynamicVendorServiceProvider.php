<?php

namespace App\Modules\DynamicVendors;

use App\Modules\DynamicVendors\Repositories\DynamicVendorRepository;
use App\Modules\DynamicVendors\Repositories\DynamicVendorRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class DynamicVendorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DynamicVendorRepositoryInterface::class, DynamicVendorRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'dynamic-vendors');
    }
}
