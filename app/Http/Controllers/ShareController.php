<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

use App\Interfaces\Tour\ShareRepositoryInterface;
use App\Interfaces\BookingRepositoryInterface;
use App\Services\Payment\PaymentService;
use App\Http\Requests\Booking\Share\StoreRequest;
use App\Models\Tour;

class ShareController extends Controller
{
    public function __construct(
        private readonly ShareRepositoryInterface $shareRepository,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly PaymentService $paymentService,
    ) {}
    /**
     * Show the shared tour view for clients
     */
    public function tour(string $shareToken): View
    {
        $share = $this->shareRepository->findByToken($shareToken);

        // Check if the share link is valid
        if (!$this->shareRepository->isShareLinkValid($shareToken)) {
            abort(404, 'This share link has expired or is no longer valid.');
        }

        // Mark as shared when first accessed
        if ($share->share_status === 'ready') {
            $this->shareRepository->markAsShared($share->id);
        }
        $tour = $share->tour;
        return view('share.tour', compact('tour', 'share'));
    }

    /**
     * Handle booking from share link
     */
    public function book(string $shareToken, Request $request): View
    {
        $share = $this->shareRepository->findByToken($shareToken);

        // Check if the share link is valid
        if (!$this->shareRepository->isShareLinkValid($shareToken)) {
            abort(404, 'This share link has expired or is no longer valid.');
        }

        $tour = $share->tour->load(['location', 'category', 'images', 'itineraryItems', 'pricings']);

        return view('share.book', [
            'tour' => $tour,
            'shareToken' => $shareToken,
        ]);
    }

    /**
     * Store a booking from a share link.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        Log::info($request->all());
        try {
            $validated = $request->validated();

            // // Verify the share token and get the tour
            // $tour = Tour::where('share_token', $validated['share_token'])
            //     ->where('id', $validated['tour_id'])
            //     ->firstOrFail();

            // // Check if the share link is still valid
            // if (!$tour->isShareLinkValid()) {
            //     return back()->with('error', 'This share link has expired or is no longer valid.');
            // }

            if (!$this->shareRepository->isShareLinkValid($validated['share_token'])) {
                return back()->with('error', 'This share link has expired or is no longer valid.');
            }

            $share = $this->shareRepository->findByToken($validated['share_token']);
            
            $tour = $share->tour;

            $location = $tour->location;

            // Create booking with pending_payment status
            $booking = $this->bookingRepository->store($location, $tour, $validated);

            // Initiate payment and redirect to payment provider
            $successUrl = route('bookings.success', ['booking' => $booking->reference]);
            $cancelUrl = route('bookings.share.book', ['shareToken' => $validated['share_token']]);

            $result = $this->paymentService->initiatePayment($booking, $successUrl, $cancelUrl);

            return redirect()->away($result['checkout_url']);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Unable to create booking: ' . $e->getMessage());
        }
    }
}
