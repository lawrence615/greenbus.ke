<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\CityRepositoryInterface;
use App\Repositories\CityRepository;
use App\Interfaces\TourRepositoryInterface;
use App\Repositories\TourRepository;
use App\Interfaces\BookingRepositoryInterface;
use App\Repositories\BookingRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(TourRepositoryInterface::class, TourRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
