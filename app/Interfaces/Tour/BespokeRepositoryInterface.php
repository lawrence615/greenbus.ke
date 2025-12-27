<?php

namespace App\Interfaces\Tour;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Models\Location;
use App\Models\Tour;

interface BespokeRepositoryInterface
{
    /**
     * Get all tours for admin
     */
    public function index(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a tour by ID with relationships
     */
    public function find(int $id): ?Tour;

    /**
     * Find a tour by slug with relationships
     */
    public function findBySlug(string $slug): ?Tour;

    /**
     * Create a new tour
     */
    public function store(array $data): Tour;

    /**
     * Update an existing tour
     */
    public function update(Tour $tour, array $data): Tour;
}
