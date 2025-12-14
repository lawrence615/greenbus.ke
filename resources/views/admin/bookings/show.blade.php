@extends('layouts.admin')

@section('title', 'Booking ' . $booking->reference)
@section('page-title', 'Booking Details')

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('console.bookings.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Bookings
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-slate-900">Booking Information</h2>
                        <p class="text-sm text-slate-500 font-mono">{{ $booking->reference }}</p>
                    </div>
                    @php $status = \App\Enums\BookingStatus::tryFrom($booking->status) @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($status?->color() === 'green') bg-emerald-100 text-emerald-700
                        @elseif($status?->color() === 'yellow') bg-yellow-100 text-yellow-700
                        @elseif($status?->color() === 'red') bg-red-100 text-red-700
                        @elseif($status?->color() === 'blue') bg-blue-100 text-blue-700
                        @else bg-slate-100 text-slate-700
                        @endif">
                        {{ $status?->label() ?? $booking->status }}
                    </span>
                </div>
                <div class="p-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-500">Tour</p>
                        <p class="font-medium text-slate-900">{{ Str::limit($booking->tour->title, 120) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Location</p>
                        <p class="font-medium text-slate-900">{{ $booking->location->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Date</p>
                        <p class="font-medium text-slate-900">{{ $booking->date->format('l, F j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Time</p>
                        <p class="font-medium text-slate-900">{{ $booking->time }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Guests</p>
                        <p class="font-medium text-slate-900">
                            {{ $booking->adults }} Adults, {{ $booking->children }} Children, {{ $booking->infants }} Infants
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Total Amount</p>
                        <p class="font-medium text-slate-900">{{ $booking->currency }} {{ number_format($booking->total_amount) }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-slate-500">Pickup Location</p>
                        <p class="font-medium text-slate-900">{{ $booking->pickup_location ?? 'Not specified' }}</p>
                    </div>
                    @if($booking->special_requests)
                    <div class="col-span-2">
                        <p class="text-sm text-slate-500">Special Requests</p>
                        <p class="font-medium text-slate-900">{{ $booking->special_requests }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Customer Information</h2>
                </div>
                <div class="p-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-500">Name</p>
                        <p class="font-medium text-slate-900">{{ $booking->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Email</p>
                        <p class="font-medium text-slate-900">{{ $booking->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Phone</p>
                        <p class="font-medium text-slate-900">{{ $booking->customer_phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Booked On</p>
                        <p class="font-medium text-slate-900">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            @if($booking->payment)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Payment Information</h2>
                </div>
                <div class="p-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-500">Provider</p>
                        <p class="font-medium text-slate-900 capitalize">{{ $booking->payment->provider }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Reference</p>
                        <p class="font-medium text-slate-900 font-mono text-sm">{{ $booking->payment->provider_reference ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Amount</p>
                        <p class="font-medium text-slate-900">{{ $booking->payment->currency }} {{ number_format($booking->payment->amount) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Status</p>
                        @php $paymentStatus = \App\Enums\PaymentStatus::tryFrom($booking->payment->status) @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            @if($paymentStatus?->isSuccessful()) bg-emerald-100 text-emerald-700
                            @elseif($booking->payment->status === 'failed') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">
                            {{ $paymentStatus?->label() ?? $booking->payment->status }}
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Update Status -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Update Status</h2>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('console.bookings.update-status', $booking) }}">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @foreach(\App\Enums\BookingStatus::cases() as $statusOption)
                                <option value="{{ $statusOption->value }}" {{ $booking->status === $statusOption->value ? 'selected' : '' }}>
                                    {{ $statusOption->label() }}
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Refund Section -->
            @if($booking->isEligibleForRefund())
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Process Refund</h2>
                </div>
                <div class="p-6">
                    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex gap-2">
                            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div class="text-sm text-amber-800">
                                <p class="font-medium">Refund Eligibility</p>
                                <p class="mt-1">This booking is eligible for a refund. The tour is more than 24 hours away.</p>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('console.bookings.refund', $booking) }}" onsubmit="return confirm('Are you sure you want to process this refund? This action cannot be undone.');">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="reason" class="block text-sm font-medium text-slate-700 mb-1">Refund Reason</label>
                                <textarea 
                                    name="reason" 
                                    id="reason" 
                                    rows="3" 
                                    required
                                    placeholder="Enter the reason for this refund..."
                                    class="w-full rounded-lg border border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 placeholder:text-slate-400"
                                ></textarea>
                                @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                                Process Refund
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @elseif($booking->status === \App\Enums\BookingStatus::REFUNDED->value)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Refund Information</h2>
                </div>
                <div class="p-6">
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg">
                        <div class="flex gap-2">
                            <svg class="w-5 h-5 text-slate-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-slate-700">
                                <p class="font-medium">This booking has been refunded</p>
                                @if($booking->refunded_at)
                                <p class="mt-1">Refunded on: {{ $booking->refunded_at->format('M d, Y \a\t H:i') }}</p>
                                @endif
                                @if($booking->refund_reason)
                                <p class="mt-2"><span class="font-medium">Reason:</span> {{ $booking->refund_reason }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            @php $ineligibilityReason = $booking->getRefundIneligibilityReason() @endphp
            @if($ineligibilityReason && $booking->status === \App\Enums\BookingStatus::CONFIRMED->value)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Refund Status</h2>
                </div>
                <div class="p-6">
                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex gap-2">
                            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-red-800">
                                <p class="font-medium">Not Eligible for Refund</p>
                                <p class="mt-1">{{ $ineligibilityReason }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="mailto:{{ $booking->customer_email }}" class="flex items-center gap-2 text-sm text-slate-600 hover:text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Email Customer
                    </a>
                    @if($booking->customer_phone)
                    <a href="tel:{{ $booking->customer_phone }}" class="flex items-center gap-2 text-sm text-slate-600 hover:text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Call Customer
                    </a>
                    @endif
                    @if($booking->payment)
                    <a href="{{ route('console.payments.show', $booking->payment) }}" class="flex items-center gap-2 text-sm text-slate-600 hover:text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        View Payment Details
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
