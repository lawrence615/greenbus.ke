<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Location;
use App\Models\TourImage;
use App\Models\Booking;
use App\Models\TourCategory;
use App\Models\TourItineraryItem;
use App\Models\TourPricing;
use App\Models\Testimonial;
use App\Models\TourShare;

class Tour extends Model
{
    use SoftDeletes;
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
        'additional_information',
        'cancellation_policy',
        'duration_text',
        'no_of_people',
        'meeting_point',
        'starts_at_time',
        'starts_at_time',
        'cut_off_time',
        'is_daily',
        'is_featured',
        'is_the_bus_tour',
        'status',
    ];

    protected $casts = [
        'is_daily' => 'boolean',
        'is_featured' => 'boolean',
        'is_the_bus_tour' => 'boolean',
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

        $maxRetries = 5;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            try {
                // Start a database transaction to prevent race conditions
                return DB::transaction(function () use ($locationId, $locationCode) {
                    // Find all existing numeric codes (across all locations) with a lock
                    $existingCodes = static::withTrashed() // Include soft-deleted records
                        ->whereNotNull('code')
                        ->where('code', 'like', $locationCode . '-%')
                        ->lockForUpdate() // Prevent other transactions from reading these rows
                        ->pluck('code')
                        ->toArray();

                    // Extract all numeric suffixes
                    $existingNumbers = [];
                    foreach ($existingCodes as $code) {
                        if (preg_match('/-(\d+)$/', $code, $matches)) {
                            $existingNumbers[] = (int) $matches[1];
                        }
                    }

                    // Start from 1 and find the first available number
                    $nextNumber = 1;
                    while (in_array($nextNumber, $existingNumbers)) {
                        $nextNumber++;
                    }

                    $newCode = $locationCode . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

                    // Final double-check to ensure uniqueness (including soft-deleted)
                    if (static::withTrashed()->where('code', $newCode)->exists()) {
                        throw new \Exception('Code still exists after generation');
                    }

                    // Log for debugging
                    Log::info("Generated tour code: {$newCode} for location {$locationId}. Existing codes: " . implode(', ', $existingCodes));

                    return $newCode;
                });
            } catch (\Exception $e) {
                // Increment retry counter
                $retryCount = $retryCount + 1;
                Log::warning("Tour code generation attempt {$retryCount} failed: " . $e->getMessage());

                if ($retryCount >= $maxRetries) {
                    // Fallback: use a timestamp-based approach
                    $timestamp = now()->format('His');
                    $fallbackCode = $locationCode . '-' . $timestamp;

                    if (!static::withTrashed()->where('code', $fallbackCode)->exists()) {
                        Log::info("Using fallback tour code: {$fallbackCode}");
                        return $fallbackCode;
                    }

                    throw new \Exception("Unable to generate unique tour code after {$maxRetries} attempts");
                }

                // Wait a random amount of time before retrying (to avoid synchronized retries)
                usleep(rand(100000, 500000)); // 100-500ms delay
            }
        }

        throw new \Exception("Unable to generate unique tour code after {$maxRetries} attempts");
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

    public function shares(): HasMany
    {
        return $this->hasMany(TourShare::class);
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
     * Mark the tour as shared with a client
     */
    public function markAsShared(): void
    {
        $this->share_status = 'shared';
        $this->shared_at = now();
        $this->save();
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

    /**
     * Format duration text based on tour category
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_text) {
            return 'N/A';
        }
        
        switch($this->tour_category_id) {
            case 1: // Hourly Tours
                // Convert "1" to "1 hour", "2" to "2 hours", etc.
                $duration = $this->duration_text;
                if (is_numeric($duration)) {
                    return $duration == 1 ? '1 hour' : $duration . ' hours';
                }
                return $duration;
                
            case 2: // Half Day Tours
                return '6 hours';
                
            case 3: // Full Day Tours
                return 'Full day';
                
            case 4: // Multiple Day Tours
                // Should already be in format "X days"
                return $this->duration_text;
                
            default:
                return $this->duration_text;
        }
    }
}
