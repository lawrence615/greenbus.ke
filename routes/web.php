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
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Auth\InviteController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Invite acceptance routes
Route::get('/invite/{token}', [InviteController::class, 'show'])->name('invite.accept');
Route::post('/invite/{token}', [InviteController::class, 'accept'])->name('invite.accept.store');

// Redirect old dashboard route
Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();
    $hasRole = $user->hasRole(['admin', 'manager']);
    
    if ($hasRole) {
        return redirect()->route('console.dashboard');
    }
    return redirect()->route('customer.dashboard');
})->name('dashboard');

// Customer routes
Route::middleware('auth')->prefix('account')->name('customer.')->group(function () {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
});

// Admin routes (admin and manager)
Route::middleware(['auth', 'role:admin|manager'])->prefix('console')->name('console.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::post('/bookings/{booking}/refund', [AdminBookingController::class, 'refund'])->name('bookings.refund');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');

    Route::get('/tours', [AdminTourController::class, 'index'])->name('tours.index');
    Route::get('/tours/create', [AdminTourController::class, 'create'])->name('tours.create');
    Route::post('/tours', [AdminTourController::class, 'store'])->name('tours.store');
    Route::get('/tours/{tour:slug}', [AdminTourController::class, 'show'])->name('tours.show');
    Route::get('/tours/{tour:slug}/edit', [AdminTourController::class, 'edit'])->name('tours.edit');
    Route::put('/tours/{tour:slug}', [AdminTourController::class, 'update'])->name('tours.update');
    Route::delete('/tours/{tour:slug}', [AdminTourController::class, 'destroy'])->name('tours.destroy');
    Route::patch('/tours/{tour:slug}/toggle-status', [AdminTourController::class, 'toggleStatus'])->name('tours.toggle-status');

    // Testimonials management
    Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [AdminTestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [AdminTestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [AdminTestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [AdminTestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');
    Route::patch('/testimonials/{testimonial}/toggle-status', [AdminTestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');

    // FAQs management
    Route::get('/faqs', [AdminFaqController::class, 'index'])->name('faqs.index');
    Route::get('/faqs/create', [AdminFaqController::class, 'create'])->name('faqs.create');
    Route::post('/faqs', [AdminFaqController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{faq}/edit', [AdminFaqController::class, 'edit'])->name('faqs.edit');
    Route::put('/faqs/{faq}', [AdminFaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [AdminFaqController::class, 'destroy'])->name('faqs.destroy');
    Route::patch('/faqs/{faq}/toggle-status', [AdminFaqController::class, 'toggleStatus'])->name('faqs.toggle-status');
});

// User management routes (admin only)
Route::middleware(['auth', 'role:admin'])->prefix('console')->name('console.')->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/resend-invite', [AdminUserController::class, 'resendInvite'])->name('users.resend-invite');
});

// Booking routes
Route::get('/book/{city:slug}/{tour:slug}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/book/{city:slug}/{tour:slug}', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');
Route::get('/booking/{booking}/cancelled', [BookingController::class, 'cancel'])->name('bookings.cancel');
Route::get('/booking/{booking}/retry', [BookingController::class, 'retryPayment'])->name('bookings.retry');

// City/Tour routes (must be last - catches /{slug} patterns)
Route::get('/{city:slug}', [CityController::class, 'show'])->name('cities.show');
Route::get('/{city:slug}/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/{city:slug}/tours/{tour:slug}', [TourController::class, 'show'])->name('tours.show');
