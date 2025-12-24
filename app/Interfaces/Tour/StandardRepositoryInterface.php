<?php

namespace App\Interfaces\Tour;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Models\Location;
use App\Models\Tour;

interface StandardRepositoryInterface
{
    /**
     * Get all tours for location
     */
    public function index(Location $location, int $perPage = 12, ?string $search = null);

    /**
     * Find a tour by ID with relationships
     */
    public function get(Location $location, Tour $tour);

    /**
     * Find a tour by ID with relationships
     */
    public function find(int $id): ?Tour;

    /**
     * Create a new tour
     */
    public function store(array $data): Tour;

    /**
     * Update an existing tour
     */
    public function update(Tour $tour, array $data): Tour;

    /**
     * Delete a tour
     */
    public function delete(Tour $tour): void;

    /**
     * Toggle tour status
     */
    public function toggleStatus(Tour $tour): Tour;
}
