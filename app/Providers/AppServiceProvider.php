<?php

namespace App\Providers;

use App\Events\BookingCreated;
use App\Events\PaymentSucceeded;
use App\Interfaces\Tour\MultimediaRepositoryInterface;
use App\Listeners\NotifyAdminsOfBookingPayment;
use App\Listeners\NotifyAdminsOfNewBooking;
use App\Listeners\SendBookingConfirmationOnPayment;
use App\Models\Location;
use App\Repositories\Tour\MultimediaRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MultimediaRepositoryInterface::class, MultimediaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant "admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Share active locations with all views using the app layout
        View::composer('layouts.app', function ($view) {
            $view->with('activeCities', Location::where('is_active', true)->orderBy('name')->get());
        });

        // Register event listeners
        Event::listen(BookingCreated::class, NotifyAdminsOfNewBooking::class);
        Event::listen(PaymentSucceeded::class, SendBookingConfirmationOnPayment::class);
        Event::listen(PaymentSucceeded::class, NotifyAdminsOfBookingPayment::class);
    }
}
