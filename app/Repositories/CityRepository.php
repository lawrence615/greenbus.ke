<?php

namespace App\Repositories;

use App\Interfaces\CityRepositoryInterface;
use App\Models\City;
use Illuminate\Support\Collection;

class CityRepository implements CityRepositoryInterface
{
    public function index(int $perPage = 15, ?bool $onlyActive = null)
    {
        $query = City::query();

        if ($onlyActive !== null) {
            $query->where('is_active', $onlyActive);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function getAll(): Collection
    {
        return City::orderBy('name')->get();
    }

    public function getBySlug(string $slug, bool $onlyActive = true): ?City
    {
        $query = City::where('slug', $slug);

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        return $query->first();
    }

    public function getDefaultCity(): ?City
    {
        return $this->getBySlug('nairobi', true);
    }

    public function getFeaturedToursForCity(City $city, int $limit = 6)
    {
        return $city->tours()
            ->with('city')
            ->where('featured', true)
            ->where('status', 'published')
            ->take($limit)
            ->get();
    }

    public function countFeaturedToursForCity(City $city): int
    {
        return $city->tours()
            ->where('featured', true)
            ->where('status', 'published')
            ->count();
    }
}
