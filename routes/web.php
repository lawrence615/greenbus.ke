<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\BookingController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/{city:slug}', [CityController::class, 'show'])->name('cities.show');

Route::get('/{city:slug}/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/{city:slug}/tours/{tour:slug}', [TourController::class, 'show'])->name('tours.show');

Route::get('/book/{city:slug}/{tour:slug}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/book/{city:slug}/{tour:slug}', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');
Route::get('/booking/{booking}/cancelled', [BookingController::class, 'cancel'])->name('bookings.cancel');
Route::get('/booking/{booking}/retry', [BookingController::class, 'retryPayment'])->name('bookings.retry');
