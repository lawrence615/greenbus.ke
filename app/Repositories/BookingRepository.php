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

        // Get pricing data from tour_pricings table
        $pricings = $tour->pricings()->pluck('price', 'person_type')->toArray();
        
        // Check if we have individual pricing or group pricing
        $hasIndividualPricing = isset($pricings['individual']);
        $hasGroupPricing = !empty(array_intersect(['adult', 'senior', 'youth', 'child', 'infant'], array_keys($pricings)));

        $total = 0;

        if ($hasIndividualPricing && !$hasGroupPricing) {
            // Individual pricing only
            $individuals = (int) ($data['individuals'] ?? 0);
            $total = $individuals * $pricings['individual'];
            
            // Set all group counts to 0 for individual pricing
            $adults = 0;
            $youth = 0;
            $seniors = 0;
            $children = 0;
            $infants = 0;
        } else {
            // Group pricing (or both available - use group pricing)
            $adults = (int) ($data['adults'] ?? 0);
            $youth = (int) ($data['youth'] ?? 0);
            $seniors = (int) ($data['seniors'] ?? 0);
            $children = (int) ($data['children'] ?? 0);
            $infants = (int) ($data['infants'] ?? 0);

            $total = $adults * ($pricings['adult'] ?? 0)
                + $youth * ($pricings['youth'] ?? 0)
                + $seniors * ($pricings['senior'] ?? 0)
                + $children * ($pricings['child'] ?? 0)
                + $infants * ($pricings['infant'] ?? 0);
            
            // Set individuals to 0 for group pricing
            $individuals = 0;
        }

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
            'individuals' => $individuals,
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
