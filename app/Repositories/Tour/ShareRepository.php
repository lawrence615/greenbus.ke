<?php

namespace App\Repositories\Tour;

use App\Models\TourShare;
use App\Interfaces\Tour\ShareRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShareRepository implements ShareRepositoryInterface
{
    protected TourShare $model;

    public function __construct(TourShare $tourShare)
    {
        $this->model = $tourShare;
    }

    public function all(): Collection
    {
        return $this->model->with('tour')->get();
    }

    public function find(int $id): ?TourShare
    {
        return $this->model->with('tour')->find($id);
    }

    public function findByToken(string $token): ?TourShare
    {
        return $this->model->with('tour')->where('share_token', $token)->first();
    }

    public function findByTourId(int $tourId): Collection
    {
        return $this->model->with('tour')->where('tour_id', $tourId)->get();
    }

    public function store(array $data): TourShare
    {
        return $this->model->create([
            'tour_id' => $data['tour_id'],
            'share_token' => $this->generateUniqueToken(),
            'share_status' => 'ready',
            'expires_at' => now()->addDays(7), // Expires in 7 days
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $tourShare = $this->find($id);
        if (!$tourShare) {
            return false;
        }
        return $tourShare->update($data);
    }

    public function delete(int $id): bool
    {
        $tourShare = $this->find($id);
        if (!$tourShare) {
            return false;
        }
        return $tourShare->delete();
    }

    public function createShareToken(int $tourId): TourShare
    {
        return $this->model->create([
            'tour_id' => $tourId,
            'share_token' => $this->generateUniqueToken(),
            'share_status' => 'ready',
            'expires_at' => Carbon::now()->addDays(30), // Default 30 days expiry
        ]);
    }

    public function markAsShared(int $id): TourShare
    {
        $tourShare = $this->find($id);
        if (!$tourShare) {
            throw new \Exception('Tour share not found');
        }
        
        $tourShare->update([
            'share_status' => 'shared',
            'shared_at' => now(),
        ]);
        
        return $tourShare->fresh();
    }

    public function markAsExpired(int $id): TourShare
    {
        $tourShare = $this->find($id);
        if (!$tourShare) {
            throw new \Exception('Tour share not found');
        }
        
        $tourShare->update([
            'share_status' => 'expired',
        ]);
        
        return $tourShare->fresh();
    }

    public function getActiveShares(): Collection
    {
        return $this->model->with('tour')
            ->where('share_status', 'shared')
            ->where('expires_at', '>', now())
            ->get();
    }

    public function getExpiredShares(): Collection
    {
        return $this->model->with('tour')
            ->where(function ($query) {
                $query->where('share_status', 'expired')
                    ->orWhere('expires_at', '<', now());
            })
            ->get();
    }

    public function isShareLinkValid(string $token): bool
    {
        $share = $this->findByToken($token);
        if (!$share) {
            return false;
        }

        return $share->share_token && 
               in_array($share->share_status, ['ready', 'shared']) &&
               (!$share->expires_at || $share->expires_at->isFuture());
    }

    public function getShareUrl(string $token): string
    {
        return $token ? route('bookings.share.tour', $token) : '';
    }

    public function getShareUrlByTourId(int $tourId): ?string
    {
        $share = $this->model->where('tour_id', $tourId)
            ->whereIn('share_status', ['ready', 'shared'])
            ->where('expires_at', '>', now())
            ->first();
        
        return $share ? $this->getShareUrl($share->share_token) : null;
    }

    private function generateUniqueToken(): string
    {
        do {
            $token = Str::random(32);
        } while ($this->model->where('share_token', $token)->exists());
        
        return $token;
    }
}
