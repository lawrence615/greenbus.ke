@extends('layouts.customer')

@section('title', 'Booking ' . $booking->reference)
@section('page-title', 'Booking Details')

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('customer.bookings.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to My Bookings
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tour Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-slate-900">{{ $booking->tour->name ?? 'Tour' }}</h2>
                        <p class="text-sm text-slate-500">{{ $booking->location->name ?? 'N/A' }}</p>
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
                        <p class="text-sm text-slate-500">Country of Origin</p>
                        <p class="font-medium text-slate-900">{{ $booking->country_of_origin ?? 'Not specified' }}</p>
                    </div>
                    @if($booking->special_requests)
                    <div class="col-span-2">
                        <p class="text-sm text-slate-500">Special Requests</p>
                        <p class="font-medium text-slate-900">{{ $booking->special_requests }}</p>
                    </div>
                    @endif
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
                        <p class="text-sm text-slate-500">Amount Paid</p>
                        <p class="font-medium text-slate-900">{{ $booking->payment->currency }} {{ number_format($booking->payment->amount) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Payment Status</p>
                        @php $paymentStatus = \App\Enums\PaymentStatus::tryFrom($booking->payment->status) @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            @if($paymentStatus?->isSuccessful()) bg-emerald-100 text-emerald-700
                            @elseif($booking->payment->status === 'failed') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">
                            {{ $paymentStatus?->label() ?? $booking->payment->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Payment Method</p>
                        <p class="font-medium text-slate-900 capitalize">{{ $booking->payment->provider }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Payment Date</p>
                        <p class="font-medium text-slate-900">{{ $booking->payment->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Booking Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Booking Summary</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Reference</span>
                        <span class="font-mono text-sm text-slate-900">{{ $booking->reference }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Booked On</span>
                        <span class="text-slate-900">{{ $booking->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="pt-4 border-t border-slate-200">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-slate-900">Total</span>
                            <span class="text-xl font-bold text-emerald-600">{{ $booking->currency }} {{ number_format($booking->total_amount) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Need Help -->
            <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                <h3 class="font-semibold text-slate-900 mb-2">Need Help?</h3>
                <p class="text-sm text-slate-600 mb-4">Contact us if you have any questions about your booking.</p>
                <a href="mailto:info@greenbus.ke" class="inline-flex items-center gap-2 text-sm font-medium text-emerald-600 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    info@greenbus.ke
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
