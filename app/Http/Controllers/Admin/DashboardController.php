<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('status', BookingStatus::CONFIRMED->value)->count(),
            'pending_bookings' => Booking::where('status', BookingStatus::PENDING_PAYMENT->value)->count(),
            'total_revenue' => Payment::where('status', PaymentStatus::SUCCEEDED->value)->sum('amount'),
            'total_users' => User::count(),
        ];

        $recentBookings = Booking::with(['tour', 'location', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with('booking.tour')
            ->where('status', PaymentStatus::SUCCEEDED->value)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentPayments'));
    }
}
