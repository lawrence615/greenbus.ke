<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Tour;
use App\Models\City;
use App\Models\Payment;

class Booking extends Model
{
    protected $fillable = [
        'tour_id',
        'city_id',
        'reference',
        'date',
        'time',
        'adults',
        'children',
        'infants',
        'total_amount',
        'currency',
        'customer_name',
        'customer_email',
        'customer_phone',
        'pickup_location',
        'special_requests',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
