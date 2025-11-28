<?php

namespace App\Repositories;

use App\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use App\Models\City;
use App\Models\Tour;

class BookingRepository implements BookingRepositoryInterface
{
    public function index(?City $city = null, ?Tour $tour = null, int $perPage = 15)
    {
        $query = Booking::query()->with(['tour', 'city']);

        if ($city) {
            $query->where('city_id', $city->id);
        }

        if ($tour) {
            $query->where('tour_id', $tour->id);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function get(Booking $booking): Booking
    {
        return $booking->load(['tour', 'city', 'payment']);
    }

    public function store(City $city, Tour $tour, array $data): Booking
    {
        if ($tour->city_id !== $city->id) {
            abort(404);
        }

        $adults = (int) ($data['adults'] ?? 0);
        $children = (int) ($data['children'] ?? 0);
        $infants = (int) ($data['infants'] ?? 0);

        $total = $adults * $tour->base_price_adult
            + $children * (int) ($tour->base_price_child ?? 0);

        return Booking::create([
            'tour_id' => $tour->id,
            'city_id' => $city->id,
            'reference' => strtoupper(uniqid('GB')),
            'date' => $data['date'],
            'time' => $data['time'] ?? null,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'total_amount' => $total,
            'currency' => 'KES',
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'] ?? null,
            'pickup_location' => $data['pickup_location'] ?? null,
            'special_requests' => $data['special_requests'] ?? null,
            'status' => 'pending',
        ]);
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
