<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.tour', 'booking.location'])->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by provider
        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by reference
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('provider_reference', 'like', "%{$search}%")
                    ->orWhereHas('booking', function ($bq) use ($search) {
                        $bq->where('reference', 'like', "%{$search}%")
                            ->orWhere('customer_email', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->paginate(15)->withQueryString();
        $statuses = PaymentStatus::cases();
        $providers = ['flutterwave', 'stripe'];

        // Summary stats
        $totalSuccessful = Payment::where('status', PaymentStatus::SUCCEEDED->value)->sum('amount');
        $totalPending = Payment::whereIn('status', [PaymentStatus::INITIATED->value, PaymentStatus::PROCESSING->value])->sum('amount');

        return view('admin.payments.index', compact('payments', 'statuses', 'providers', 'totalSuccessful', 'totalPending'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.tour', 'booking.location']);

        return view('admin.payments.show', compact('payment'));
    }
}
