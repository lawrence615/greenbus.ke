<?php

namespace App\Repositories;

use App\Interfaces\TourRepositoryInterface;
use App\Models\City;
use App\Models\Tour;

class TourRepository implements TourRepositoryInterface
{
    public function index(City $city, int $perPage = 12, ?string $search = null)
    {
        $query = $city->tours()
            ->where('status', 'published')
            ->with(['images', 'category']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function get(City $city, Tour $tour)
    {
        if ($tour->city_id !== $city->id) {
            abort(404);
        }

        return $tour->load(['images', 'category', 'itineraryItems']);
    }
}
