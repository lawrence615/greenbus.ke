<?php

namespace App\Jobs;

use App\Mail\BookingCreated;
use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmationEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    public function handle(): void
    {
        Log::info('Job: SendBookingConfirmationEmail executing', [
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->reference,
            'customer_email' => $this->booking->customer_email,
            'trace_id' => uniqid('job_exec_'),
            'timestamp' => now()->toIso8601String(),
        ]);

        Mail::to($this->booking->customer_email)
            ->send(new BookingCreated($this->booking));

        Log::info('Job: Email sent successfully', [
            'booking_id' => $this->booking->id,
            'trace_id' => uniqid('email_sent_'),
        ]);
    }
}
