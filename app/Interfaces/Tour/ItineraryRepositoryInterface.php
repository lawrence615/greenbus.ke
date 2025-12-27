<?php

namespace App\Interfaces\Tour;

use Illuminate\Support\Collection;

use App\Models\Tour;
use App\Models\TourItineraryItem;

interface ItineraryRepositoryInterface
{
    public function list(Tour $tour): Collection;

    public function create(Tour $tour, array $data): TourItineraryItem;

    public function update(TourItineraryItem $itineraryItem, array $data): void;

    public function delete(TourItineraryItem $itineraryItem): void;

    public function reorder(Tour $tour, array $itemIds): void;

    public function assertBelongsToTour(Tour $tour, TourItineraryItem $itineraryItem): void;
}
