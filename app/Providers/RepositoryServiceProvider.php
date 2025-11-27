<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\TourRepositoryInterface;
use App\Repositories\TourRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TourRepositoryInterface::class, TourRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
