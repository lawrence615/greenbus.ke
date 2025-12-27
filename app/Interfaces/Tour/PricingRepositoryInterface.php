<?php

namespace App\Interfaces\Tour;

use Illuminate\Support\Collection;

use App\Models\Tour;
use App\Models\TourPricing;

interface PricingRepositoryInterface
{
    public function listByTour(Tour $tour): Collection;

    public function find(int $id): ?TourPricing;

    public function store(Tour $tour, array $data): TourPricing;

    public function update(TourPricing $tourPricing, array $data): TourPricing;

    public function delete(TourPricing $tourPricing): void;
}
