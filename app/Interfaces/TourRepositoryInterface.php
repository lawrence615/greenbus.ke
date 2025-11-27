<?php

namespace App\Interfaces;

use App\Models\City;
use App\Models\Tour;

interface TourRepositoryInterface
{
    public function index(City $city, int $perPage = 12, ?string $search = null);
    public function get(City $city, Tour $tour);
}
