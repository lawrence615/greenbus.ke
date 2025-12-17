<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

use App\Interfaces\LocationRepositoryInterface;
use App\Models\Location;

class LocationRepository implements LocationRepositoryInterface
{
    public function index(int $perPage = 15, ?bool $onlyActive = null)
    {
        $query = Location::query();

        if ($onlyActive !== null) {
            $query->where('is_active', $onlyActive);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function getAll(): Collection
    {
        return Location::orderBy('name')->get();
    }

    public function getBySlug(string $slug, bool $onlyActive = true): ?Location
    {
        $query = Location::where('slug', $slug);

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        return $query->first();
    }

    public function getDefaultCity(): ?Location
    {
        return $this->getBySlug('nairobi', true);
    }

    public function getFeaturedToursForCity(Location $location, int $limit = 6)
    {
        return $location->tours()
            ->with('location')
            ->where('is_featured', true)
            ->where('status', 'published')
            ->take($limit)
            ->get();
    }

    public function countFeaturedToursForCity(Location $location): int
    {
        return $location->tours()
            ->where('is_featured', true)
            ->where('status', 'published')
            ->count();
    }
}
