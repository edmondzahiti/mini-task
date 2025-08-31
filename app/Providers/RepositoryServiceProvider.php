<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ServiceProviderRepository;
use App\Repositories\CategoryRepository;
use App\Services\ServiceProviderService;
use App\Services\CategoryService;
use App\Models\ServiceProvider as ServiceProviderModel;
use App\Models\Category as CategoryModel;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(ServiceProviderRepository::class, function ($app) {
            return new ServiceProviderRepository(new ServiceProviderModel());
        });

        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepository(new CategoryModel());
        });

        // Bind services
        $this->app->bind(ServiceProviderService::class, function ($app) {
            return new ServiceProviderService(
                $app->make(ServiceProviderRepository::class)
            );
        });

        $this->app->bind(CategoryService::class, function ($app) {
            return new CategoryService(
                $app->make(CategoryRepository::class)
            );
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
