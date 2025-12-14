<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Interfaces\UserRepositoryInterface;
use App\Notifications\Admin\BookingPaidNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdminsOfBookingPayment implements ShouldQueue
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function handle(PaymentSucceeded $event): void
    {
        $admins = $this->userRepository->getAdminAndManagers();

        if ($admins->isEmpty()) {
            return;
        }

        // Load booking relationships for the notification
        $event->booking->loadMissing(['tour', 'location']);

        // Send notification to all admins and managers
        Notification::send($admins, new BookingPaidNotification($event->booking, $event->payment));
    }
}
