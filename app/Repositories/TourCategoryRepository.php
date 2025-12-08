<?php

namespace App\Repositories;

use App\Interfaces\TourCategoryRepositoryInterface;
use App\Models\TourCategory;
use Illuminate\Support\Collection;

class TourCategoryRepository implements TourCategoryRepositoryInterface
{
    public function getAll(): Collection
    {
        return TourCategory::orderBy('name')->get();
    }
}
