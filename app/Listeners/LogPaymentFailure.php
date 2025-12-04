<?php

namespace App\Listeners;

use App\Events\PaymentFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogPaymentFailure implements ShouldQueue
{
    public function handle(PaymentFailed $event): void
    {
        Log::warning('Payment failed for booking', [
            'booking_reference' => $event->booking->reference,
            'customer_email' => $event->booking->customer_email,
            'reason' => $event->reason,
            'payment_id' => $event->payment?->id,
        ]);

        // TODO: Optionally send a failure notification email to customer
        // or notify admin about failed payments
    }
}
