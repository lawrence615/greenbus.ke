<?php

namespace App\Interfaces;

use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TestimonialRepositoryInterface
{
    /**
     * Get paginated testimonials for admin
     */
    public function index(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all active testimonials for public display
     */
    public function getActive(): Collection;

    /**
     * Find a testimonial by ID
     */
    public function find(int $id): ?Testimonial;

    /**
     * Create a new testimonial
     */
    public function store(array $data): Testimonial;

    /**
     * Update an existing testimonial
     */
    public function update(Testimonial $testimonial, array $data): Testimonial;

    /**
     * Delete a testimonial
     */
    public function delete(Testimonial $testimonial): void;

    /**
     * Toggle testimonial active status
     */
    public function toggleStatus(Testimonial $testimonial): Testimonial;

    /**
     * Get featured testimonials for homepage
     * Returns active testimonials with highest rating, ordered by sort_order
     */
    public function getFeatured(int $limit = 6): Collection;
}
