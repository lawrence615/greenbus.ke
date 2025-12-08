<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TourImage extends Model
{
    protected $fillable = [
        'tour_id',
        'path',
        'is_cover',
        'sort_order',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    protected $appends = ['url'];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->path ? Storage::disk('do')->url($this->path) : null,
        );
    }
}
