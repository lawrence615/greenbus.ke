<?php

namespace App\Http\Controllers\Customer;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Booking::with(['tour', 'location', 'payment'])
            ->where('customer_email', $user->email)
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10)->withQueryString();
        $statuses = BookingStatus::cases();

        return view('customer.bookings.index', compact('bookings', 'statuses'));
    }

    public function show(Booking $booking)
    {
        $user = auth()->user();

        // Ensure the booking belongs to the current user
        if ($booking->customer_email !== $user->email) {
            abort(403);
        }

        $booking->load(['tour', 'location', 'payment']);

        return view('customer.bookings.show', compact('booking'));
    }
}
