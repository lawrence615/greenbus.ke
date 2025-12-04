<?php

namespace App\Jobs;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireUnpaidBookings implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     * Expires bookings that have been pending payment for more than 30 minutes.
     */
    public function handle(): void
    {
        $expiryThreshold = now()->subMinutes(30);

        $expiredCount = 0;

        Booking::where('status', BookingStatus::PENDING_PAYMENT->value)
            ->where('created_at', '<', $expiryThreshold)
            ->chunkById(100, function ($bookings) use (&$expiredCount) {
                foreach ($bookings as $booking) {
                    $this->expireBooking($booking);
                    $expiredCount++;
                }
            });

        if ($expiredCount > 0) {
            Log::info('Expired unpaid bookings', ['count' => $expiredCount]);
        }
    }

    private function expireBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => BookingStatus::EXPIRED->value,
            ]);

            if ($booking->payment) {
                $booking->payment->update([
                    'status' => PaymentStatus::CANCELLED->value,
                ]);
            }

            Log::info('Booking expired due to non-payment', [
                'booking_reference' => $booking->reference,
                'created_at' => $booking->created_at->toIso8601String(),
            ]);
        });
    }
}
