<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourShare extends Model
{
    protected $fillable = [
        'tour_id',
        'share_token',
        'share_status',
        'shared_at',
        'expires_at',
    ];

    protected $casts = [
        'shared_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->share_status === 'shared' && !$this->isExpired();
    }

    public function scopeActive($query)
    {
        return $query->where('share_status', 'shared')
            ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where(function ($query) {
            $query->where('share_status', 'expired')
                ->orWhere('expires_at', '<', now());
        });
    }
}
