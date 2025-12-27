<?php

namespace App\Repositories\Tour;

use Illuminate\Support\Collection;

use App\Interfaces\Tour\PricingRepositoryInterface;
use App\Models\Tour;
use App\Models\TourPricing;
use Illuminate\Support\Facades\Log;

class PricingRepository implements PricingRepositoryInterface
{
    public function listByTour(Tour $tour): Collection
    {
        return TourPricing::query()
            ->where('tour_id', $tour->id)
            ->orderBy('person_type')
            ->get();
    }

    public function find(int $id): ?TourPricing
    {
        return TourPricing::find($id);
    }

    public function store(Tour $tour, array $data): TourPricing
    {
        $data['tour_id'] = $tour->id;
        Log::info('store: repository', ['data' => $data]);
        return TourPricing::create($data);
    }

    public function update(TourPricing $tourPricing, array $data): TourPricing
    {
        $tourPricing->update($data);

        return $tourPricing->fresh();
    }

    public function delete(TourPricing $tourPricing): void
    {
        $tourPricing->delete();
    }
}
