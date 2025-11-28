<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\StoreRequest;
use App\Interfaces\BookingRepositoryInterface;
use App\Models\City;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
        $validated = $request->validated();

        $booking = $this->bookingRepository->store($city, $tour, $validated);

        return redirect()
            ->route('home')
            ->with('status', 'Booking created. Reference: ' . $booking->reference);
    }
}
