<?php

namespace App\Interfaces;

use App\Models\FAQ;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface FAQRepositoryInterface
{
    /**
     * Get paginated FAQs for admin
     */
    public function index(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all active FAQs for public display
     */
    public function getActive(?string $category = null): Collection;

    /**
     * Get all active FAQs for the public FAQ page
     */
    public function getPublicFaqs(): Collection;

    /**
     * Get all unique categories
     */
    public function getCategories(): array;

    /**
     * Find a FAQ by ID
     */
    public function find(int $id): ?FAQ;

    /**
     * Create a new FAQ
     */
    public function store(array $data): FAQ;

    /**
     * Update an existing FAQ
     */
    public function update(FAQ $faq, array $data): FAQ;

    /**
     * Delete a FAQ
     */
    public function delete(FAQ $faq): void;

    /**
     * Toggle FAQ active status
     */
    public function toggleStatus(FAQ $faq): FAQ;
}
