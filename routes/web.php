<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\BookingController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/{city:slug}', [CityController::class, 'show'])->name('cities.show');

Route::get('/{city:slug}/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/{city:slug}/tours/{tour:slug}', [TourController::class, 'show'])->name('tours.show');

Route::get('/book/{city:slug}/{tour:slug}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/book/{city:slug}/{tour:slug}', [BookingController::class, 'store'])->name('bookings.store');
