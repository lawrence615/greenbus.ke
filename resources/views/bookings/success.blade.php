@extends('layouts.app')

@php
    use App\Enums\BookingStatus;
    $isConfirmed = $booking->status === BookingStatus::CONFIRMED->value;
    $isPending = $booking->status === BookingStatus::PENDING_PAYMENT->value;
@endphp

@section('title', ($isConfirmed ? 'Booking Confirmed' : 'Processing Payment') . ' – ' . $booking->reference)

@section('content')
<section class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-slate-100 p-8 shadow-sm text-center">
        @if($isConfirmed)
        {{-- Success Icon --}}
        <div class="mx-auto w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-semibold text-slate-900 mb-2">Booking Confirmed!</h1>
        <p class="text-slate-600 mb-6">Thank you for your booking. A confirmation email has been sent to <strong>{{ $booking->customer_email }}</strong>.</p>
        @elseif($isPending && isset($payment_status) && $payment_status === 'successful')
        {{-- Processing Icon --}}
        <div class="mx-auto w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-amber-600 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-semibold text-slate-900 mb-2">Processing Payment...</h1>
        <p class="text-slate-600 mb-6">Your payment is being verified. This page will update automatically.</p>
        
        {{-- Auto-refresh while pending --}}
        <meta http-equiv="refresh" content="5">
        @else
        {{-- Pending/Unknown State --}}
        <div class="mx-auto w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-semibold text-slate-900 mb-2">Payment Pending</h1>
        <p class="text-slate-600 mb-6">Your booking is awaiting payment confirmation.</p>
        @endif

        {{-- Booking Reference --}}
        <div class="bg-emerald-50 rounded-xl p-4 mb-6">
            <p class="text-xs text-emerald-700 font-medium mb-1">Booking Reference</p>
            <p class="text-2xl font-bold text-emerald-800 tracking-wide">{{ $booking->reference }}</p>
        </div>

        {{-- Booking Details --}}
        <div class="text-left bg-slate-50 rounded-xl p-6 mb-6">
            <h2 class="text-sm font-semibold text-slate-900 mb-4">Booking Details</h2>
            
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-600">Tour</dt>
                    <dd class="font-medium text-slate-900">{{ $booking->tour->title }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-600">Location</dt>
                    <dd class="font-medium text-slate-900">{{ $booking->city->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-600">Date</dt>
                    <dd class="font-medium text-slate-900">{{ $booking->date->format('l, M d, Y') }}</dd>
                </div>
                @if($booking->time)
                <div class="flex justify-between">
                    <dt class="text-slate-600">Time</dt>
                    <dd class="font-medium text-slate-900">{{ $booking->time }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-slate-600">Guests</dt>
                    <dd class="font-medium text-slate-900">
                        {{ $booking->adults }} {{ Str::plural('adult', $booking->adults) }}
                        @if($booking->children > 0)
                            , {{ $booking->children }} {{ Str::plural('child', $booking->children) }}
                        @endif
                        @if($booking->infants > 0)
                            , {{ $booking->infants }} {{ Str::plural('infant', $booking->infants) }}
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-600">Customer</dt>
                    <dd class="font-medium text-slate-900">{{ $booking->customer_name }}</dd>
                </div>
                <div class="border-t border-slate-200 pt-3 mt-3 flex justify-between">
                    <dt class="text-slate-900 font-semibold">Total Paid</dt>
                    <dd class="font-bold text-emerald-700">${{ number_format($booking->total_amount / 153, 2) }} USD</dd>
                </div>
            </dl>
        </div>

        {{-- Payment Status --}}
        @if($booking->payment && $isConfirmed)
        <div class="flex items-center justify-center gap-2 text-sm text-emerald-700 mb-6">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>Payment successful via {{ ucfirst($booking->payment->provider) }}</span>
        </div>
        @endif

        {{-- What's Next - Only show when confirmed --}}
        @if($isConfirmed)
        <div class="text-left bg-blue-50 rounded-xl p-6 mb-6">
            <h3 class="text-sm font-semibold text-blue-900 mb-2">What's Next?</h3>
            <ul class="text-sm text-blue-800 space-y-2">
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Check your email for the confirmation with full details</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Save your booking reference: <strong>{{ $booking->reference }}</strong></span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Arrive at the meeting point on time on your tour date</span>
                </li>
            </ul>
        </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                Explore More Tours
            </a>
            <a href="{{ route('tours.show', [$booking->city, $booking->tour]) }}" 
               class="inline-flex items-center justify-center px-6 py-2.5 rounded-full border border-slate-300 text-slate-700 text-sm font-semibold hover:bg-slate-50">
                View Tour Details
            </a>
        </div>
    </div>
</section>
@endsection
