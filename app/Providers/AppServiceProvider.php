<?php

namespace App\Providers;

use App\Events\BookingCreated;
use App\Events\PaymentSucceeded;
use App\Listeners\NotifyAdminsOfBookingPayment;
use App\Listeners\NotifyAdminsOfNewBooking;
use App\Listeners\SendBookingConfirmationOnPayment;
use App\Models\City;
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
        //
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

        // Share active cities with all views using the app layout
        View::composer('layouts.app', function ($view) {
            $view->with('activeCities', City::where('is_active', true)->orderBy('name')->get());
        });

        // Register event listeners
        Event::listen(BookingCreated::class, NotifyAdminsOfNewBooking::class);
        Event::listen(PaymentSucceeded::class, SendBookingConfirmationOnPayment::class);
        Event::listen(PaymentSucceeded::class, NotifyAdminsOfBookingPayment::class);
    }
}
