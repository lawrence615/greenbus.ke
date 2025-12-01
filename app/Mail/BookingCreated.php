<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
        $this->booking->loadMissing(['tour.city']);
    }

    public function build(): self
    {
        $pdf = Pdf::loadView('emails.bookings.ticket-pdf', [
            'booking' => $this->booking,
        ]);

        return $this
            ->subject('Your Greenbus booking – ' . $this->booking->reference)
            ->view('emails.bookings.created')
            ->with([
                'booking' => $this->booking,
            ])
            ->attachData(
                $pdf->output(),
                'greenbus-ticket-' . $this->booking->reference . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
