<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\City;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
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

    public function store(Request $request, City $city, Tour $tour): RedirectResponse
    {
        if ($tour->city_id !== $city->id) {
            abort(404);
        }

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['nullable', 'string', 'max:50'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['nullable', 'integer', 'min:0'],
            'infants' => ['nullable', 'integer', 'min:0'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'special_requests' => ['nullable', 'string'],
        ]);

        $adults = (int) $validated['adults'];
        $children = (int) ($validated['children'] ?? 0);
        $infants = (int) ($validated['infants'] ?? 0);

        $total = $adults * $tour->base_price_adult
            + $children * (int) ($tour->base_price_child ?? 0);

        $booking = Booking::create([
            'tour_id' => $tour->id,
            'city_id' => $city->id,
            'reference' => strtoupper(uniqid('GB')),
            'date' => $validated['date'],
            'time' => $validated['time'] ?? null,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'total_amount' => $total,
            'currency' => 'KES',
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'] ?? null,
            'pickup_location' => $validated['pickup_location'] ?? null,
            'special_requests' => $validated['special_requests'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('home')
            ->with('status', 'Booking created. Reference: ' . $booking->reference);
    }
}
