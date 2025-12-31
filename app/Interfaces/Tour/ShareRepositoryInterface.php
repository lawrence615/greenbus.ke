<?php

namespace App\Interfaces\Tour;

use App\Models\TourShare;
use Illuminate\Database\Eloquent\Collection;

interface ShareRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?TourShare;
    public function findByToken(string $token): ?TourShare;
    public function findByTourId(int $tourId): Collection;
    public function store(array $data): TourShare;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function createShareToken(int $tourId): TourShare;
    public function markAsShared(int $id): TourShare;
    public function markAsExpired(int $id): TourShare;
    public function getActiveShares(): Collection;
    public function getExpiredShares(): Collection;
    public function isShareLinkValid(string $token): bool;
    public function getShareUrl(string $token): string;
    public function getShareUrlByTourId(int $tourId): ?string;
}
