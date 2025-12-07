<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Jobs\SendBookingConfirmationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendBookingConfirmationOnPayment implements ShouldQueue
{
    public function handle(PaymentSucceeded $event): void
    {
        Log::info('Listener: SendBookingConfirmationOnPayment handling event', [
            'booking_id' => $event->booking->id,
            'booking_reference' => $event->booking->reference,
            'trace_id' => uniqid('listener_'),
            'timestamp' => now()->toIso8601String(),
        ]);

        SendBookingConfirmationEmail::dispatch($event->booking);

        Log::info('Listener: Dispatched SendBookingConfirmationEmail job', [
            'booking_id' => $event->booking->id,
            'trace_id' => uniqid('job_dispatch_'),
        ]);
    }
}
