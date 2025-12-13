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
use App\Models\Testimonial;

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
        'included',
        'excluded',
        'important_information',
        'cancellation_policy',
        'duration_text',
        'no_of_people',
        'meeting_point',
        'starts_at_time',
        'cut_off_time',
        'is_daily',
        'featured',
        'base_price_adult',
        'base_price_child',
        'base_price_infant',
        'base_price_senior',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tour $tour) {
            if (empty($tour->code)) {
                $tour->code = static::generateCode($tour->city_id);
            }
        });
    }

    /**
     * Generate a unique tour code based on city.
     * Format: {CITY_CODE}-{NUMBER} e.g. NRB-001, MBS-002
     */
    public static function generateCode(int $cityId): string
    {
        $city = City::find($cityId);
        $cityCode = $city?->code ?? 'TUR';
        
        // Get the last tour number for this city
        $lastTour = static::where('city_id', $cityId)
            ->whereNotNull('code')
            ->orderByRaw("CAST(SUBSTRING_INDEX(code, '-', -1) AS UNSIGNED) DESC")
            ->first();
        
        if ($lastTour && preg_match('/-(\d+)$/', $lastTour->code, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $cityCode . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
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

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function coverImage(): ?TourImage
    {
        return $this->images()->where('is_cover', true)->first()
            ?? $this->images()->orderBy('sort_order')->first();
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->coverImage()?->url;
    }
}
