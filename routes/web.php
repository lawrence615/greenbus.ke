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
use App\Http\Controllers\Admin\Tour\ShareController as AdminShareController;
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

// // Booking routes
// Route::get('/book/{location:slug}/{tour:slug}', [BookingController::class, 'create'])->name('bookings.create');
// Route::post('/book/{location:slug}/{tour:slug}', [BookingController::class, 'store'])->name('bookings.store');
// Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');
// Route::get('/booking/{booking}/cancelled', [BookingController::class, 'cancel'])->name('bookings.cancel');
// Route::get('/booking/{booking}/retry', [BookingController::class, 'retryPayment'])->name('bookings.retry');

// // Share routes (for bespoke tours)
// Route::post('/share/book', [ShareController::class, 'store'])->name('bookings.store.share');
// Route::get('/share/{shareToken}', [ShareController::class, 'tour'])->name('share.tour');
// Route::get('/share/{shareToken}/book', [ShareController::class, 'book'])->name('share.book');

// Booking routes
Route::prefix('bookings')->name('bookings.')->group(function () {
    // Share routes (for bespoke tours) - must come first to avoid conflicts
    Route::prefix('share')->name('share.')->group(function () {
        Route::get('/{shareToken}', [ShareController::class, 'tour'])->name('tour');
        Route::post('/book', [ShareController::class, 'store'])->name('store');
        Route::get('/{shareToken}/book', [ShareController::class, 'book'])->name('book');
    });

    Route::get('/{booking}/success', [BookingController::class, 'success'])->name('success');
    Route::get('/{booking}/cancelled', [BookingController::class, 'cancel'])->name('cancel');
    Route::get('/{booking}/retry', [BookingController::class, 'retryPayment'])->name('retry');
    Route::get('/{location:slug}/{tour:slug}', [BookingController::class, 'create'])->name('create');
    Route::post('/{location:slug}/{tour:slug}', [BookingController::class, 'store'])->name('store');
});

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

    // Tours
    Route::prefix('tours')->name('tours.')->group(function () {
        // Main routes
        Route::get('/', [AdminMainController::class, 'index'])->name('index');
        Route::delete('/{tour:slug}', [AdminMainController::class, 'destroy'])->name('destroy');
        Route::patch('/{tour:slug}/toggle-status', [AdminMainController::class, 'toggleStatus'])->name('toggle-status');

        // Standard routes
        Route::get('/standard/create', [AdminStandardController::class, 'create'])->name('standard.create');
        Route::post('/standard/store', [AdminStandardController::class, 'store'])->name('standard.store');
        Route::get('/standard/{tour:slug}', [AdminStandardController::class, 'show'])->name('standard.show');
        Route::get('/standard/{tour:slug}/edit', [AdminStandardController::class, 'edit'])->name('standard.edit');
        Route::put('/standard/{tour:slug}', [AdminStandardController::class, 'update'])->name('standard.update');

        // Bespoke routes
        Route::prefix('bespoke')->name('bespoke.')->group(function () {
            Route::get('/create', [AdminBespokeController::class, 'create'])->name('create');
            Route::post('/store', [AdminBespokeController::class, 'store'])->name('store');
            Route::get('/{tour:slug}', [AdminBespokeController::class, 'show'])->name('show');
            Route::get('/{tour:slug}/edit', [AdminBespokeController::class, 'edit'])->name('edit');
            Route::put('/{tour:slug}', [AdminBespokeController::class, 'update'])->name('update');

            // Share
            Route::post('/{tour}/share', [AdminShareController::class, 'generateShareLink'])->name('share');
        });

        // Itinerary
        Route::get('/{tour:slug}/itinerary', [AdminItineraryController::class, 'index'])->name('itinerary.index');
        Route::get('/{tour:slug}/itinerary/create', [AdminItineraryController::class, 'create'])->name('itinerary.create');
        Route::post('/{tour:slug}/itinerary', [AdminItineraryController::class, 'store'])->name('itinerary.store');
        Route::get('/{tour:slug}/itinerary/{itineraryItem}/edit', [AdminItineraryController::class, 'edit'])->name('itinerary.edit');
        Route::put('/{tour:slug}/itinerary/{itineraryItem}', [AdminItineraryController::class, 'update'])->name('itinerary.update');
        Route::delete('/{tour:slug}/itinerary/{itineraryItem}', [AdminItineraryController::class, 'destroy'])->name('itinerary.destroy');
        Route::post('/{tour:slug}/itinerary/reorder', [AdminItineraryController::class, 'reorder'])->name('itinerary.reorder');

        // Multimedia
        Route::get('/{tour:slug}/multimedia', [AdminMultimediaController::class, 'index'])->name('multimedia.index');
        Route::post('/{tour:slug}/multimedia/upload', [AdminMultimediaController::class, 'upload'])->name('multimedia.upload');
        Route::put('/{tour:slug}/multimedia', [AdminMultimediaController::class, 'update'])->name('multimedia.update');
        Route::delete('/{tour:slug}/multimedia', [AdminMultimediaController::class, 'destroy'])->name('multimedia.destroy');
        Route::post('/{tour:slug}/multimedia/set-cover', [AdminMultimediaController::class, 'setCover'])->name('multimedia.set-cover');

        // Pricing
        Route::get('/{tour:slug}/pricing/create', [AdminPricingController::class, 'create'])->name('pricing.create');
        Route::post('/{tour:slug}/pricing/store', [AdminPricingController::class, 'store'])->name('pricing.store');
        Route::delete('/{tour:slug}/pricing/{tourPricing}', [AdminPricingController::class, 'destroy'])->name('pricing.destroy');

        // Trash
        Route::get('/trash', [AdminTrashController::class, 'index'])->name('trash.index');
        Route::post('/trash/{tour}/restore', [AdminTrashController::class, 'restore'])->name('trash.restore');
        Route::delete('/trash/{tour}', [AdminTrashController::class, 'destroy'])->name('trash.destroy');
    });

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
