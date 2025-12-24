<?php

namespace App\Interfaces\Tour;

use App\Models\Tour;

interface BespokeRepositoryInterface
{
    /**
     * Create a new tour
     */
    public function store(array $data): Tour;

    /**
     * Find a bespoke tour by slug with relationships
     */
    public function findBySlug(string $slug): ?Tour;

    /**
     * Update an existing tour
     */
    public function update(Tour $tour, array $data): Tour;
}
