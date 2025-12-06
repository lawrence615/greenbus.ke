<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Carbon\Carbon;
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
        'refunded_at',
        'refund_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'refunded_at' => 'datetime',
    ];

    /**
     * Get the tour date and time as a Carbon instance.
     */
    public function getTourDateTime(): Carbon
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->time);
    }

    /**
     * Check if the booking is eligible for a refund.
     * Conditions:
     * 1. Booking must be confirmed (paid)
     * 2. Tour must be at least 24 hours away
     */
    public function isEligibleForRefund(): bool
    {
        // Must be a confirmed booking
        if ($this->status !== BookingStatus::CONFIRMED->value) {
            return false;
        }

        // Must have a successful payment
        if (!$this->payment || !$this->payment->isSuccessful()) {
            return false;
        }

        // Tour must be at least 24 hours away
        $tourDateTime = $this->getTourDateTime();
        $hoursUntilTour = now()->diffInHours($tourDateTime, false);

        return $hoursUntilTour >= 24;
    }

    /**
     * Get the reason why refund is not eligible.
     */
    public function getRefundIneligibilityReason(): ?string
    {
        if ($this->status === BookingStatus::REFUNDED->value) {
            return 'This booking has already been refunded.';
        }

        if ($this->status !== BookingStatus::CONFIRMED->value) {
            return 'Only confirmed bookings can be refunded.';
        }

        if (!$this->payment || !$this->payment->isSuccessful()) {
            return 'No successful payment found for this booking.';
        }

        $tourDateTime = $this->getTourDateTime();
        $hoursUntilTour = now()->diffInHours($tourDateTime, false);

        if ($hoursUntilTour < 24) {
            if ($hoursUntilTour < 0) {
                return 'This tour has already taken place.';
            }
            return 'Refunds must be requested at least 24 hours before the tour. Only ' . round($hoursUntilTour, 1) . ' hours remaining.';
        }

        return null;
    }

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
