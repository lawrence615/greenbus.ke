<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\City;
use App\Models\TourImage;
use App\Models\Booking;
use App\Models\TourCategory;
use App\Models\TourItineraryItem;

class Tour extends Model
{
    protected $fillable = [
        'city_id',
        'tour_category_id',
        'code',
        'title',
        'slug',
        'short_description',
        'description',
        'includes',
        'important_information',
        'duration_text',
        'meeting_point',
        'starts_at_time',
        'is_daily',
        'featured',
        'base_price_adult',
        'base_price_child',
        'base_price_infant',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tour $tour) {
            if (empty($tour->code)) {
                $tour->code = static::generateCode();
            }
        });
    }

    /**
     * Generate a unique tour code.
     */
    public static function generateCode(): string
    {
        $lastTour = static::orderBy('id', 'desc')->first();
        $nextId = $lastTour ? $lastTour->id + 1 : 1;
        
        return 'TUR-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'tour_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(TourImage::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function itineraryItems(): HasMany
    {
        return $this->hasMany(TourItineraryItem::class)
            ->orderBy('sort_order');
    }
}
