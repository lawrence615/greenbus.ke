<?php

namespace App\Notifications\Admin;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingPaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Booking $booking,
        public readonly Payment $payment
    ) {}

    /**
     * Get the notification's delivery channels.
     * This can be extended to include 'vonage' (SMS), 'slack', 'database', etc.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Future: return array based on user preferences
        // e.g., $notifiable->notification_channels ?? ['mail']
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $booking = $this->booking;
        $payment = $this->payment;
        $viewUrl = route('console.bookings.show', $booking);

        return (new MailMessage)
            ->subject("Payment Received: {$booking->reference}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A booking has been paid and confirmed!")
            ->line("**Booking Details:**")
            ->line("- **Reference:** {$booking->reference}")
            ->line("- **Customer:** {$booking->customer_name}")
            ->line("- **Tour:** {$booking->tour->title}")
            ->line("- **Date:** {$booking->date->format('M j, Y')}")
            ->line("**Payment Details:**")
            ->line("- **Amount:** USD " . number_format($payment->amount))
            ->line("- **Method:** {$payment->payment_method}")
            ->line("- **Transaction ID:** {$payment->transaction_id}")
            ->action('View Booking', $viewUrl)
            ->salutation('GreenBus Notifications');
    }

    /**
     * Get the array representation of the notification for database storage.
     * Useful if you add 'database' to the via() channels.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_paid',
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->reference,
            'payment_id' => $this->payment->id,
            'customer_name' => $this->booking->customer_name,
            'tour_name' => $this->booking->tour->title,
            'amount' => $this->payment->amount,
            'message' => "Booking {$this->booking->reference} paid - USD " . number_format($this->payment->amount),
        ];
    }

    /**
     * Get the SMS representation of the notification.
     * Uncomment and configure when SMS channel is added.
     */
    // public function toVonage(object $notifiable): \Illuminate\Notifications\Messages\VonageMessage
    // {
    //     return (new \Illuminate\Notifications\Messages\VonageMessage)
    //         ->content("Payment received! Booking {$this->booking->reference} - USD " . number_format($this->payment->amount));
    // }
}
