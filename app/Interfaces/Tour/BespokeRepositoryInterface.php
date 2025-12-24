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
     * Update an existing tour
     */
    public function update(Tour $tour, array $data): Tour;
}
