<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourItineraryItem extends Model
{
    protected $fillable = [
        'tour_id',
        'type',
        'time_label',
        'title',
        'description',
        'duration_value',
        'duration_unit',
        'sort_order',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class)->withTrashed();
    }
}
