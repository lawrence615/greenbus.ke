<?php

namespace App\Interfaces;

use App\Models\Faq;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface FaqRepositoryInterface
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
     * Get all unique categories
     */
    public function getCategories(): array;

    /**
     * Find a FAQ by ID
     */
    public function find(int $id): ?Faq;

    /**
     * Create a new FAQ
     */
    public function store(array $data): Faq;

    /**
     * Update an existing FAQ
     */
    public function update(Faq $faq, array $data): Faq;

    /**
     * Delete a FAQ
     */
    public function delete(Faq $faq): void;

    /**
     * Toggle FAQ active status
     */
    public function toggleStatus(Faq $faq): Faq;
}
