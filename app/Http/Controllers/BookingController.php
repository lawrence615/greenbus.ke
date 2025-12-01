<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

use App\Interfaces\BookingRepositoryInterface;
use App\Models\City;
use App\Models\Tour;
use App\Http\Requests\Booking\StoreRequest;
use App\Mail\BookingCreated;

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

        Mail::to($booking->customer_email)
            ->send(new BookingCreated($booking));

        return redirect()
            ->route('home')
            ->with('success', 'Booking created. Reference: ' . $booking->reference);
    }
}
