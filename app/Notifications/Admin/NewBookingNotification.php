<?php

namespace App\Notifications\Admin;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Booking $booking
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
        $viewUrl = route('console.bookings.show', $booking);

        return (new MailMessage)
            ->subject("New Booking: {$booking->reference}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new booking has been created and is awaiting payment.")
            ->line("**Booking Details:**")
            ->line("- **Reference:** {$booking->reference}")
            ->line("- **Customer:** {$booking->customer_name}")
            ->line("- **Tour:** {$booking->tour->title}")
            ->line("- **Date:** {$booking->date->format('M j, Y')}")
            ->line("- **Amount:** USD " . number_format($booking->total_amount))
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
            'type' => 'new_booking',
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->reference,
            'customer_name' => $this->booking->customer_name,
            'tour_name' => $this->booking->tour->title,
            'total_amount' => $this->booking->total_amount,
            'message' => "New booking {$this->booking->reference} created by {$this->booking->customer_name}",
        ];
    }

    /**
     * Get the SMS representation of the notification.
     * Uncomment and configure when SMS channel is added.
     */
    // public function toVonage(object $notifiable): \Illuminate\Notifications\Messages\VonageMessage
    // {
    //     return (new \Illuminate\Notifications\Messages\VonageMessage)
    //         ->content("New booking {$this->booking->reference} - {$this->booking->customer_name} - USD " . number_format($this->booking->total_amount));
    // }
}
