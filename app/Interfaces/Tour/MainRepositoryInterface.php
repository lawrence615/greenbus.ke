<?php

namespace App\Interfaces\Tour;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MainRepositoryInterface
{
    /**
     * Get all tours for admin with filters
     */
    public function index(array $filters = [], int $perPage = 15): LengthAwarePaginator; 
}