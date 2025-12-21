<?php

namespace App\Repositories;

use App\Enums\BookingStatus;
use App\Events\BookingCreated;
use App\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use App\Models\Location;
use App\Models\Tour;

class BookingRepository implements BookingRepositoryInterface
{
    public function index(?Location $location = null, ?Tour $tour = null, int $perPage = 15)
    {
        $query = Booking::query()->with(['tour', 'location']);

        if ($location) {
            $query->where('location_id', $location->id);
        }

        if ($tour) {
            $query->where('tour_id', $tour->id);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function get(Booking $booking): Booking
    {
        return $booking->load(['tour', 'location', 'payment']);
    }

    public function store(Location $location, Tour $tour, array $data): Booking
    {
        if ($tour->location_id !== $location->id) {
            abort(404);
        }

        $adults = (int) ($data['adults'] ?? 0);
        $youth = (int) ($data['youth'] ?? 0);
        $seniors = (int) ($data['seniors'] ?? 0);
        $children = (int) ($data['children'] ?? 0);
        $infants = (int) ($data['infants'] ?? 0);

        $total = $adults * $tour->base_price_adult
            + $youth * (int) ($tour->base_price_youth ?? $tour->base_price_child ?? 0)
            + $seniors * (int) ($tour->base_price_senior ?? $tour->base_price_adult)
            + $children * (int) ($tour->base_price_child ?? 0)
            + $infants * (int) ($tour->base_price_infant ?? 0);

        $booking = Booking::create([
            'tour_id' => $tour->id,
            'location_id' => $location->id,
            'reference' => strtoupper(uniqid('GB')),
            'date' => $data['date'],
            'time' => $data['time'] ?? null,
            'adults' => $adults,
            'youth' => $youth,
            'seniors' => $seniors,
            'children' => $children,
            'infants' => $infants,
            'total_amount' => $total,
            'currency' => 'USD',
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'] ?? null,
            'country_of_origin' => $data['country_of_origin'] ?? null,
            'special_requests' => $data['special_requests'] ?? null,
            'status' => BookingStatus::PENDING_PAYMENT->value,
        ]);

        // Dispatch event to notify admins/managers
        BookingCreated::dispatch($booking);

        return $booking;
    }

    public function update(Booking $booking, array $data): Booking
    {
        $booking->fill($data);
        $booking->save();

        return $booking->fresh();
    }

    public function delete(Booking $booking): void
    {
        $booking->delete();
    }
}
