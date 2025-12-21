<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['tour', 'location', 'payment'])->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Filter by tour(s)
        if ($request->filled('tour_ids')) {
            $tourIds = $request->tour_ids;
            // Handle empty value for "All Tours"
            if (!empty($tourIds) && !in_array('', $tourIds)) {
                $query->whereIn('tour_id', $tourIds);
            }
        } elseif ($request->filled('tour_id')) {
            // Fallback for single tour_id (backward compatibility)
            $query->where('tour_id', $request->tour_id);
        }

        // Search by reference, name, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();
        $tours = Tour::orderBy('title')->get();
        $statuses = BookingStatus::cases();

        return view('admin.bookings.index', compact('bookings', 'tours', 'statuses'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['tour', 'location', 'payment']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_column(BookingStatus::cases(), 'value')),
        ]);

        $booking->update(['status' => $request->status]);

        return redirect()->route('console.bookings.show', $booking)
            ->with('success', 'Booking status updated successfully.');
    }

    public function updateNotes(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_notes' => 'nullable|string|max:2000',
        ]);

        if ($request->filled('booking_notes')) {
            $booking->setNotesWithMetadata($request->booking_notes, auth()->id());
        } else {
            $booking->booking_notes = null;
        }

        $booking->save();

        Log::info('Booking notes updated', [
            'booking_reference' => $booking->reference,
            'updated_by' => auth()->id(),
            'has_content' => $booking->hasNotes(),
        ]);

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Booking notes updated successfully.',
                'notes_metadata' => [
                    'updated_by' => auth()->user()->name ?? 'Unknown',
                    'updated_at' => $booking->notes_updated_at,
                ]
            ]);
        }

        return back()->with('success', 'Booking notes updated successfully.');
    }

    /**
     * Process a refund for a booking.
     */
    public function refund(Request $request, Booking $booking)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // Check if booking is eligible for refund
        if (!$booking->isEligibleForRefund()) {
            return back()->with('error', $booking->getRefundIneligibilityReason() ?? 'This booking is not eligible for a refund.');
        }

        try {
            DB::transaction(function () use ($booking, $request) {
                // Update booking status
                $booking->update([
                    'status' => BookingStatus::REFUNDED->value,
                    'refunded_at' => now(),
                    'refund_reason' => $request->reason,
                ]);

                // Update payment status
                if ($booking->payment) {
                    $booking->payment->update([
                        'status' => PaymentStatus::REFUNDED->value,
                        'raw_payload' => array_merge(
                            $booking->payment->raw_payload ?? [],
                            [
                                'refund_data' => [
                                    'refunded_at' => now()->toIso8601String(),
                                    'reason' => $request->reason,
                                    'refunded_by' => auth()->id(),
                                ]
                            ]
                        ),
                    ]);
                }

                Log::info('Booking refunded', [
                    'booking_reference' => $booking->reference,
                    'refunded_by' => auth()->id(),
                    'reason' => $request->reason,
                ]);
            });

            return back()->with('success', 'Booking has been refunded successfully. The customer should be notified and the refund processed through the payment provider.');
        } catch (\Exception $e) {
            Log::error('Refund failed', [
                'booking_reference' => $booking->reference,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }
}
