<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\Location;
use App\Models\TourImage;
use App\Models\Booking;
use App\Models\TourCategory;
use App\Models\TourItineraryItem;
use App\Models\TourPricing;
use App\Models\Testimonial;

class Tour extends Model
{
    protected $fillable = [
        'location_id',
        'tour_category_id',
        'tour_type',
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
        'is_featured',
        'base_price_adult',
        'base_price_youth',
        'base_price_child',
        'base_price_infant',
        'base_price_senior',
        'status',
        'share_token',
        'share_status',
        'shared_at',
        'expires_at',
    ];

    protected $casts = [
        'starts_at_time' => 'datetime',
        'shared_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_daily' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tour $tour) {
            if (empty($tour->code)) {
                $tour->code = static::generateCode($tour->location_id);
            }
        });
    }

    /**
     * Generate a unique tour code based on location.
     * Format: {LOCATION_CODE}-{NUMBER} e.g. NRB-001, MMA-002
     */
    public static function generateCode(int $locationId): string
    {
        $location = Location::find($locationId);
        $locationCode = $location?->code ?? 'LOC';
        
        // Get the last tour number for this location
        $lastTour = static::where('location_id', $locationId)
            ->whereNotNull('code')
            ->orderByRaw("CAST(SUBSTRING_INDEX(code, '-', -1) AS UNSIGNED) DESC")
            ->first();
        
        if ($lastTour && preg_match('/-(\d+)$/', $lastTour->code, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $locationCode . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
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

    public function pricings(): HasMany
    {
        return $this->hasMany(TourPricing::class);
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

    /**
     * Check if tour is available for a specific date and time
     */
    public function isAvailable(string $date, string $time): bool
    {
        $bookedParticipants = $this->bookings()
            ->where('tour_date', $date)
            ->where('start_time', $time)
            ->whereIn('status', ['confirmed', 'pending'])
            ->sum('number_of_participants');

        return $bookedParticipants < $this->no_of_people;
    }

    /**
     * Get available slots for a specific date
     */
    public function getAvailableSlots(string $date): array
    {
        $availableSlots = [];
        $times = ['09:00', '17:00']; // Fixed start times

        foreach ($times as $time) {
            $bookedParticipants = $this->bookings()
                ->where('tour_date', $date)
                ->where('start_time', $time)
                ->whereIn('status', ['confirmed', 'pending'])
                ->sum('number_of_participants');

            $availableSpots = $this->no_of_people - $bookedParticipants;
            
            $availableSlots[] = [
                'time' => $time,
                'time_display' => $time === '09:00' ? '9:00 AM' : '5:00 PM',
                'booked_participants' => $bookedParticipants,
                'available_spots' => max(0, $availableSpots),
                'max_capacity' => $this->no_of_people,
                'is_available' => $availableSpots > 0,
                'is_full' => $availableSpots <= 0
            ];
        }

        return $availableSlots;
    }

    /**
     * Get remaining capacity for a specific date and time
     */
    public function getRemainingCapacity(string $date, string $time): int
    {
        $bookedParticipants = $this->bookings()
            ->where('tour_date', $date)
            ->where('start_time', $time)
            ->whereIn('status', ['confirmed', 'pending'])
            ->sum('number_of_participants');

        return max(0, $this->no_of_people - $bookedParticipants);
    }

    /**
     * Check if tour is fully booked for a specific date and time
     */
    public function isFullyBooked(string $date, string $time): bool
    {
        return !$this->isAvailable($date, $time);
    }

    /**
     * Get booking statistics for a date range
     */
    public function getBookingStats(string $startDate, string $endDate): array
    {
        $bookings = $this->bookings()
            ->whereBetween('tour_date', [$startDate, $endDate])
            ->whereIn('status', ['confirmed', 'pending'])
            ->get();

        $stats = [
            'total_bookings' => $bookings->count(),
            'total_participants' => $bookings->sum('number_of_participants'),
            'max_capacity_per_slot' => $this->no_of_people,
            'total_possible_slots' => 0, // Will be calculated based on dates
            'utilization_rate' => 0
        ];

        // Calculate total possible slots and utilization
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            new \DateTime($endDate . ' +1 day')
        );

        $totalPossibleCapacity = 0;
        foreach ($period as $date) {
            $totalPossibleCapacity += $this->no_of_people * 2; // 2 time slots per day
        }

        $stats['total_possible_slots'] = $totalPossibleCapacity;
        if ($totalPossibleCapacity > 0) {
            $stats['utilization_rate'] = round(($stats['total_participants'] / $totalPossibleCapacity) * 100, 2);
        }

        return $stats;
    }

    /**
     * Generate a unique share token for the tour
     */
    public function generateShareToken(): string
    {
        do {
            $token = Str::random(32);
        } while (static::where('share_token', $token)->exists());

        return $token;
    }

    /**
     * Mark the tour as ready to share and generate a share token
     */
    public function markAsReadyToShare(): void
    {
        $this->share_token = $this->generateShareToken();
        $this->share_status = 'ready';
        $this->expires_at = now()->addDays(7); // Expires in 7 days
        $this->save();
    }

    /**
     * Mark the tour as shared with a client
     */
    public function markAsShared(): void
    {
        $this->share_status = 'shared';
        $this->shared_at = now();
        $this->save();
    }

    /**
     * Check if the share link is valid
     */
    public function isShareLinkValid(): bool
    {
        return $this->share_token && 
               in_array($this->share_status, ['ready', 'shared']) &&
               (!$this->expires_at || $this->expires_at->isFuture());
    }

    /**
     * Get the share URL for this tour
     */
    public function getShareUrlAttribute(): string
    {
        return $this->share_token ? route('share.tour', $this->share_token) : '';
    }

    /**
     * Get the correct admin show route based on tour type
     */
    public function getAdminShowRouteAttribute(): string
    {
        return $this->tour_type === 'bespoke' 
            ? route('console.tours.bespoke.show', $this)
            : route('console.tours.standard.show', $this);
    }
}
