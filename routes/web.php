<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\Guest\AuthController;
use App\Http\Controllers\Guest\Auth\InviteController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\Tour\MainController as AdminMainController;
use App\Http\Controllers\Admin\Tour\StandardController as AdminStandardController;
use App\Http\Controllers\Admin\Tour\BespokeController as AdminBespokeController;
use App\Http\Controllers\Admin\Tour\ItineraryController as AdminItineraryController;
use App\Http\Controllers\Admin\Tour\MultimediaController as AdminMultimediaController;
use App\Http\Controllers\Admin\Tour\PricingController as AdminPricingController;
use App\Http\Controllers\Admin\Tour\TrashController as AdminTrashController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\ShareController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Invite acceptance routes
Route::get('/invite/{token}', [InviteController::class, 'show'])->name('invite.accept');
Route::post('/invite/{token}', [InviteController::class, 'accept'])->name('invite.accept.store');

// Booking routes
Route::get('/book/{location:slug}/{tour:slug}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/book/{location:slug}/{tour:slug}', [BookingController::class, 'store'])->name('bookings.store');
Route::post('/share/book', [BookingController::class, 'storeShare'])->name('bookings.store.share');
Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');
Route::get('/booking/{booking}/cancelled', [BookingController::class, 'cancel'])->name('bookings.cancel');
Route::get('/booking/{booking}/retry', [BookingController::class, 'retryPayment'])->name('bookings.retry');

// Share routes (for bespoke tours)
Route::get('/share/{shareToken}', [ShareController::class, 'showTour'])->name('share.tour');
Route::get('/share/{shareToken}/book', [ShareController::class, 'bookTour'])->name('share.book');

// Public FAQs
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');

// Redirect old dashboard route
Route::middleware('auth')->get('/dashboard', function () {
    $hasRole = auth()->user()->hasRole(['admin', 'manager']);
    
    if ($hasRole) {
        return redirect()->route('console.dashboard');
    }
    return redirect()->route('customer.dashboard');
})->name('dashboard');

// Customer routes
Route::middleware(['auth', 'role:customer'])->prefix('account')->name('customer.')->group(function () {
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
    Route::patch('/bookings/{booking}/date', [AdminBookingController::class, 'updateDate'])->name('bookings.update-date');
    Route::patch('/bookings/{booking}/notes', [AdminBookingController::class, 'updateNotes'])->name('bookings.update-notes');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');

    // Main tour routes
    Route::get('/tours', [AdminMainController::class, 'index'])->name('tours.index');
    Route::delete('/tours/{tour:slug}', [AdminMainController::class, 'destroy'])->name('tours.destroy');
    Route::patch('/tours/{tour:slug}/toggle-status', [AdminMainController::class, 'toggleStatus'])->name('tours.toggle-status');

    // Standard tour routes
    Route::get('/tours/standard/create', [AdminStandardController::class, 'create'])->name('tours.standard.create');
    Route::post('/tours/standard/store', [AdminStandardController::class, 'store'])->name('tours.standard.store');
    Route::get('/tours/standard/{tour:slug}', [AdminStandardController::class, 'show'])->name('tours.standard.show');
    Route::get('/tours/standard/{tour:slug}/edit', [AdminStandardController::class, 'edit'])->name('tours.standard.edit');
    Route::put('/tours/standard/{tour:slug}', [AdminStandardController::class, 'update'])->name('tours.standard.update');

    // Bespoke tour routes
    Route::get('/tours/bespoke/create', [AdminBespokeController::class, 'create'])->name('tours.bespoke.create');
    Route::post('/tours/bespoke/store', [AdminBespokeController::class, 'store'])->name('tours.bespoke.store');
    Route::get('/tours/bespoke/{tour:slug}', [AdminBespokeController::class, 'show'])->name('tours.bespoke.show');
    Route::get('/tours/bespoke/{tour:slug}/edit', [AdminBespokeController::class, 'edit'])->name('tours.bespoke.edit');
    Route::put('/tours/bespoke/{tour:slug}', [AdminBespokeController::class, 'update'])->name('tours.bespoke.update');
    Route::post('/tours/bespoke/{tour}/share', [AdminBespokeController::class, 'generateShareLink'])->name('tours.bespoke.share');

    // Tour itinerary management
    Route::get('/tours/{tour:slug}/itinerary', [AdminItineraryController::class, 'index'])->name('tours.itinerary.index');
    Route::get('/tours/{tour:slug}/itinerary/create', [AdminItineraryController::class, 'create'])->name('tours.itinerary.create');
    Route::post('/tours/{tour:slug}/itinerary', [AdminItineraryController::class, 'store'])->name('tours.itinerary.store');
    Route::get('/tours/{tour:slug}/itinerary/{itineraryItem}/edit', [AdminItineraryController::class, 'edit'])->name('tours.itinerary.edit');
    Route::put('/tours/{tour:slug}/itinerary/{itineraryItem}', [AdminItineraryController::class, 'update'])->name('tours.itinerary.update');
    Route::delete('/tours/{tour:slug}/itinerary/{itineraryItem}', [AdminItineraryController::class, 'destroy'])->name('tours.itinerary.destroy');
    Route::post('/tours/{tour:slug}/itinerary/reorder', [AdminItineraryController::class, 'reorder'])->name('tours.itinerary.reorder');

    // Tour multimedia management
    Route::get('/tours/{tour:slug}/multimedia', [AdminMultimediaController::class, 'index'])->name('tours.multimedia.index');
    Route::post('/tours/{tour:slug}/multimedia/upload', [AdminMultimediaController::class, 'upload'])->name('tours.multimedia.upload');
    Route::put('/tours/{tour:slug}/multimedia', [AdminMultimediaController::class, 'update'])->name('tours.multimedia.update');
    Route::delete('/tours/{tour:slug}/multimedia', [AdminMultimediaController::class, 'destroy'])->name('tours.multimedia.destroy');
    Route::post('/tours/{tour:slug}/multimedia/set-cover', [AdminMultimediaController::class, 'setCover'])->name('tours.multimedia.set-cover');

    // Tour pricing management
    Route::get('/tours/{tour:slug}/pricing/create', [AdminPricingController::class, 'create'])->name('tours.pricing.create');
    Route::post('/tours/{tour:slug}/pricing/store', [AdminPricingController::class, 'store'])->name('tours.pricing.store');
    Route::delete('/tours/{tour:slug}/pricing/{tourPricing}', [AdminPricingController::class, 'destroy'])->name('tours.pricing.destroy');

    // Tour trash management
    Route::get('/tours/trash', [AdminTrashController::class, 'index'])->name('tours.trash.index');
    Route::post('/tours/trash/{tour}/restore', [AdminTrashController::class, 'restore'])->name('tours.trash.restore');
    Route::delete('/tours/trash/{tour}', [AdminTrashController::class, 'destroy'])->name('tours.trash.destroy');

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

// Admin routes (admin only)
Route::middleware(['auth', 'role:admin'])->prefix('console')->name('console.')->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/resend-invite', [AdminUserController::class, 'resendInvite'])->name('users.resend-invite');
    Route::post('/bookings/{booking}/refund', [AdminBookingController::class, 'refund'])->name('bookings.refund');
});

// Location/Tour routes (must be last - catches /{slug} patterns)
Route::get('/{location:slug}', [LocationController::class, 'show'])->name('locations.show');
Route::get('/{location:slug}/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/{location:slug}/tours/{tour:slug}', [TourController::class, 'show'])->name('tours.show');
