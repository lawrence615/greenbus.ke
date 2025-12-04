<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use App\Enums\BookingStatus;
use App\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use App\Models\City;
use App\Models\Tour;
use App\Services\Payment\PaymentService;
use App\Http\Requests\Booking\StoreRequest;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly PaymentService $paymentService,
    ) {}
    public function create(City $city, Tour $tour): View
    {
        if ($tour->city_id !== $city->id) {
            abort(404);
        }

        return view('bookings.create', [
            'city' => $city,
            'tour' => $tour,
        ]);
    }

    public function store(StoreRequest $request, City $city, Tour $tour): RedirectResponse
    {
        try {
            $validated = $request->validated();

            // Create booking with pending_payment status
            $booking = $this->bookingRepository->store($city, $tour, $validated);

            // Initiate payment and redirect to Stripe Checkout
            $successUrl = route('bookings.success', ['booking' => $booking->reference]);
            $cancelUrl = route('bookings.cancel', ['booking' => $booking->reference]);

            $result = $this->paymentService->initiatePayment($booking, $successUrl, $cancelUrl);

            return redirect()->away($result['checkout_url']);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment callback from Flutterwave.
     * Flutterwave redirects here for both success and failure with query params.
     */
    public function success(string $booking): View|RedirectResponse
    {
        $booking = Booking::where('reference', $booking)
            ->with(['tour', 'city', 'payment'])
            ->firstOrFail();

        // Flutterwave appends status and tx_ref to the redirect URL
        $status = request()->query('status');
        $txRef = request()->query('tx_ref');
        $transactionId = request()->query('transaction_id');

        // If status indicates failure/cancellation, redirect to cancel page
        if ($status && $status !== 'successful' && $status !== 'completed') {
            return redirect()->route('bookings.cancel', ['booking' => $booking->reference])
                ->with('error', 'Payment was not completed.');
        }

        // If payment is still pending (webhook hasn't processed yet), show waiting state
        // The webhook will update the status asynchronously
        if ($booking->status === BookingStatus::PENDING_PAYMENT->value && $status === 'successful') {
            // Payment reported successful by redirect, but webhook hasn't confirmed yet
            // Refresh booking to check if webhook already processed
            $booking->refresh();
        }

        return view('bookings.success', [
            'booking' => $booking,
            'payment_status' => $status,
            'transaction_id' => $transactionId,
        ]);
    }

    /**
     * Display payment cancelled page.
     */
    public function cancel(string $booking): View
    {
        $booking = Booking::where('reference', $booking)
            ->with(['tour', 'city'])
            ->firstOrFail();

        return view('bookings.cancel', [
            'booking' => $booking,
        ]);
    }

    /**
     * Retry payment for a pending booking.
     */
    public function retryPayment(string $booking): RedirectResponse
    {
        $booking = Booking::where('reference', $booking)
            ->where('status', BookingStatus::PENDING_PAYMENT->value)
            ->firstOrFail();

        try {
            $successUrl = route('bookings.success', ['booking' => $booking->reference]);
            $cancelUrl = route('bookings.cancel', ['booking' => $booking->reference]);

            $result = $this->paymentService->initiatePayment($booking, $successUrl, $cancelUrl);

            return redirect()->away($result['checkout_url']);
        } catch (\Exception $e) {
            return redirect()
                ->route('bookings.cancel', ['booking' => $booking->reference])
                ->with('error', 'Failed to initiate payment: ' . $e->getMessage());
        }
    }
}
