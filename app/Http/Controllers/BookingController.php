<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use App\Interfaces\BookingRepositoryInterface;
use App\Jobs\SendBookingConfirmationEmail;
use App\Models\City;
use App\Models\Tour;
use App\Http\Requests\Booking\StoreRequest;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
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

            $booking = $this->bookingRepository->store($city, $tour, $validated);

            SendBookingConfirmationEmail::dispatch($booking);

            return redirect()
                ->route('home')
                ->with('success', 'Booking created. Reference: ' . $booking->reference . ' Check your mail for more details.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }
}
