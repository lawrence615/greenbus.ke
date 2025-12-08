<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface TourCategoryRepositoryInterface
{
    public function getAll(): Collection;
}
