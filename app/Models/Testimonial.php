<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    protected $fillable = [
        'tour_id',
        'author_name',
        'author_location',
        'author_date',
        'author_cover',
        'tour_name',
        'content',
        'travel_type',
        'rating',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
        'sort_order' => 'integer',
        'author_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('created_at');
    }

    public function getInitialAttribute(): string
    {
        return strtoupper(substr($this->author_name, 0, 1));
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class)->withTrashed();
    }
}
