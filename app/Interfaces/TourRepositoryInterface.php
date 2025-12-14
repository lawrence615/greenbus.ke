<?php

namespace App\Interfaces;

use App\Models\Location;
use App\Models\Tour;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TourRepositoryInterface
{
    public function index(Location $location, int $perPage = 12, ?string $search = null);
    
    public function get(Location $location, Tour $tour);

    /**
     * Get all tours for admin with filters
     */
    public function adminIndex(array $filters = [], int $perPage = 15): LengthAwarePaginator;

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
