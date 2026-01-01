<?php

namespace App\Interfaces\Tour;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Models\Location;
use App\Models\Tour;

interface MainRepositoryInterface
{
    /**
     * Get all tours for guest with filters
     */
    public function guestIndex(Location $location, int $perPage = 6, ?string $search = null);

    /**
     * Get all tours for admin with filters
     */
    public function adminIndex(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * Get a tour by ID with relationships
     */
    public function get(Location $location, Tour $tour);

    /**
     * Delete a tour
     */
    public function delete(Tour $tour): void;

    /**
     * Get only trashed tours
     */
    public function trashed(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * Restore a soft deleted tour
     */
    public function restore(Tour $tour): void;

    /**
     * Permanently delete a tour
     */
    public function forceDelete(Tour $tour): void;

    /**
     * Toggle tour status
     */
    public function toggleStatus(Tour $tour): Tour;

    /**
     * Get the bus tour (tour marked as is_the_bus_tour)
     */
    public function getBusTour(): ?Tour;
}
