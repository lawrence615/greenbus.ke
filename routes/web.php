<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'role:super-admin|admin'])->prefix('console')->name('console.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');

    Route::resource('tours', AdminTourController::class);
    Route::patch('/tours/{tour}/toggle-status', [AdminTourController::class, 'toggleStatus'])->name('tours.toggle-status');

    Route::resource('users', AdminUserController::class);
});

// Customer routes
Route::middleware('auth')->prefix('account')->name('customer.')->group(function () {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
});

// Redirect old dashboard route
Route::middleware('auth')->get('/dashboard', function () {
    if (auth()->user()->hasRole(['super-admin', 'admin'])) {
        return redirect()->route('console.dashboard');
    }
    return redirect()->route('customer.dashboard');
})->name('dashboard');

Route::get('/{city:slug}', [CityController::class, 'show'])->name('cities.show');

Route::get('/{city:slug}/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/{city:slug}/tours/{tour:slug}', [TourController::class, 'show'])->name('tours.show');

Route::get('/book/{city:slug}/{tour:slug}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/book/{city:slug}/{tour:slug}', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');
Route::get('/booking/{booking}/cancelled', [BookingController::class, 'cancel'])->name('bookings.cancel');
Route::get('/booking/{booking}/retry', [BookingController::class, 'retryPayment'])->name('bookings.retry');
