<?php

namespace App\Repositories\Tour;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use App\Interfaces\Tour\CategoryRepositoryInterface;
use App\Models\TourCategory;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all tour categories with pagination
     */
    public function index(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $query = TourCategory::query()->latest();
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Find a tour category by ID
     */
    public function find(int $id): ?TourCategory
    {
        return TourCategory::firstOrFail($id);
    }

    /**
     * Create a new tour category
     */
    public function store(array $data): TourCategory
    {
        return TourCategory::create($data);
    }

    /**
     * Update an existing tour category
     */
    public function update(TourCategory $tourCategory, array $data): TourCategory
    {
        $tourCategory->update($data);
        return $tourCategory->fresh();
    }

    /**
     * Delete a tour category
     */
    public function delete(TourCategory $tourCategory): void
    {
        $tourCategory->delete();
    }

    /**
     * Get all tour categories without pagination
     */
    public function getAll(): Collection
    {
        return TourCategory::all();
    }
}
