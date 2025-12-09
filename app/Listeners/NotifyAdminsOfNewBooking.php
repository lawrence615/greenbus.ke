<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Interfaces\UserRepositoryInterface;
use App\Notifications\Admin\NewBookingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdminsOfNewBooking implements ShouldQueue
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function handle(BookingCreated $event): void
    {
        $admins = $this->userRepository->getAdminAndManagers();

        if ($admins->isEmpty()) {
            return;
        }

        // Load booking relationships for the notification
        $event->booking->loadMissing(['tour', 'city']);

        // Send notification to all admins and managers
        Notification::send($admins, new NewBookingNotification($event->booking));
    }
}
