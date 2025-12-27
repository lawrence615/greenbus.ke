<?php

namespace App\Interfaces\Tour;

use Illuminate\Support\Collection;

use App\Models\Tour;

interface MultimediaRepositoryInterface
{
    public function list(Tour $tour): Collection;

    public function upload(Tour $tour, array $images): void;

    public function updateMeta(Tour $tour, array $images): void;

    public function deleteImage(Tour $tour, int $imageId): void;

    public function setCover(Tour $tour, int $imageId): void;
}