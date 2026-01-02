<?php

namespace App\Interfaces\Tour;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use App\Models\TourCategory;

interface CategoryRepositoryInterface
{
    /**
     * Get all tour categories with pagination
     */
    public function index(int $perPage = 10, ?string $search = null): LengthAwarePaginator;

    /**
     * Find a tour category by ID
     */
    public function find(int $id): ?TourCategory;

    /**
     * Create a new tour category
     */
    public function store(array $data): TourCategory;

    /**
     * Update an existing tour category
     */
    public function update(TourCategory $tourCategory, array $data): TourCategory;

    /**
     * Get all tour categories without pagination
     */
    public function getAll(string $orderBy = 'name'): Collection;
}
