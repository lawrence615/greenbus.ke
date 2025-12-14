<?php

namespace App\Http\Controllers\Customer;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_bookings' => Booking::where('customer_email', $user->email)->count(),
            'upcoming_bookings' => Booking::where('customer_email', $user->email)
                ->where('status', BookingStatus::CONFIRMED->value)
                ->where('date', '>=', now()->toDateString())
                ->count(),
            'completed_bookings' => Booking::where('customer_email', $user->email)
                ->where('status', BookingStatus::COMPLETED->value)
                ->count(),
        ];

        $upcomingBookings = Booking::with(['tour', 'location'])
            ->where('customer_email', $user->email)
            ->where('status', BookingStatus::CONFIRMED->value)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->take(3)
            ->get();

        return view('customer.dashboard', compact('stats', 'upcomingBookings'));
    }
}
