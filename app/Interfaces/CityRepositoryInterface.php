<?php

namespace App\Interfaces;

use App\Models\City;
use Illuminate\Support\Collection;

interface CityRepositoryInterface
{
    public function index(int $perPage = 15, ?bool $onlyActive = null);

    public function getAll(): Collection;

    public function getBySlug(string $slug, bool $onlyActive = true): ?City;

    public function getDefaultCity(): ?City;

    public function getFeaturedToursForCity(City $city, int $limit = 6);

    public function countFeaturedToursForCity(City $city): int;
}
