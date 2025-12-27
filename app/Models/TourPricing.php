<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourPricing extends Model
{
    protected $fillable = [
        'tour_id',
        'person_type',
        'price',
        'discount',
        'discounted_price',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
