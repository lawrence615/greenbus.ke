<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\LocationRepositoryInterface;
use App\Repositories\LocationRepository;
use App\Interfaces\TourRepositoryInterface;
use App\Repositories\TourRepository;
use App\Interfaces\BookingRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Interfaces\TestimonialRepositoryInterface;
use App\Repositories\TestimonialRepository;
use App\Interfaces\FaqRepositoryInterface;
use App\Repositories\FaqRepository;
use App\Interfaces\TourCategoryRepositoryInterface;
use App\Repositories\TourCategoryRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(TourRepositoryInterface::class, TourRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(TestimonialRepositoryInterface::class, TestimonialRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(TourCategoryRepositoryInterface::class, TourCategoryRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
