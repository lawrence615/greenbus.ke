<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Jobs\SendBookingConfirmationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBookingConfirmationOnPayment implements ShouldQueue
{
    public function handle(PaymentSucceeded $event): void
    {
        SendBookingConfirmationEmail::dispatch($event->booking);
    }
}
