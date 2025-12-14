<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

use App\Models\Location;

interface LocationRepositoryInterface
{
    public function index(int $perPage = 15, ?bool $onlyActive = null);

    public function getAll(): Collection;

    public function getBySlug(string $slug, bool $onlyActive = true): ?Location;

    public function getDefaultCity(): ?Location;

    public function getFeaturedToursForCity(Location $location, int $limit = 6);

    public function countFeaturedToursForCity(Location $location): int;
}
