<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInviteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $acceptUrl = route('invite.accept', ['token' => $notifiable->invite_token]);
        $expiresAt = $notifiable->invite_sent_at->addDays(7)->format('F j, Y');

        return (new MailMessage)
            ->subject('You\'ve Been Invited to Join GreenBus')
            ->greeting("Hello {$notifiable->name}!")
            ->line('You have been invited to join the GreenBus platform.')
            ->line('Click the button below to set up your password and activate your account.')
            ->action('Accept Invitation', $acceptUrl)
            ->line("This invitation will expire on {$expiresAt}.")
            ->line('If you did not expect this invitation, you can safely ignore this email.')
            ->salutation('Best regards, The GreenBus Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'invite_sent_at' => $notifiable->invite_sent_at,
        ];
    }
}
