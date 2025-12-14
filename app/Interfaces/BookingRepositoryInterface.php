<?php

namespace App\Interfaces;

use App\Models\Booking;
use App\Models\Location;
use App\Models\Tour;

interface BookingRepositoryInterface
{
    public function index(?Location $location = null, ?Tour $tour = null, int $perPage = 15);

    public function get(Booking $booking): Booking;

    public function store(Location $location, Tour $tour, array $data): Booking;

    public function update(Booking $booking, array $data): Booking;

    public function delete(Booking $booking): void;
}
