<?php

namespace App\Jobs;

use App\Mail\BookingCreated;
use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmationEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    public function handle(): void
    {
        Mail::to($this->booking->customer_email)
            ->send(new BookingCreated($this->booking));
    }
}
