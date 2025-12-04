@extends('layouts.app')

@section('title', 'Payment Cancelled – ' . $booking->reference)

@section('content')
<section class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-slate-100 p-8 shadow-sm text-center">
        {{-- Warning Icon --}}
        <div class="mx-auto w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-semibold text-slate-900 mb-2">Payment Cancelled</h1>
        <p class="text-slate-600 mb-6">Your payment was not completed. Don't worry – your booking is saved and you can try again.</p>

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-left">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
        @endif

        {{-- Booking Reference --}}
        <div class="bg-slate-100 rounded-xl p-4 mb-6">
            <p class="text-xs text-slate-600 font-medium mb-1">Booking Reference</p>
            <p class="text-2xl font-bold text-slate-800 tracking-wide">{{ $booking->reference }}</p>
        </div>

        {{-- Booking Summary --}}
        <div class="text-left bg-slate-50 rounded-xl p-6 mb-6">
            <h2 class="text-sm font-semibold text-slate-900 mb-4">Your Booking</h2>
            
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-600">Tour</dt>
                    <dd class="font-medium text-slate-900">{{ $booking->tour->title }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-600">Date</dt>
                    <dd class="font-medium text-slate-900">{{ $booking->date->format('l, M d, Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-600">Guests</dt>
                    <dd class="font-medium text-slate-900">
                        {{ $booking->adults }} {{ Str::plural('adult', $booking->adults) }}
                        @if($booking->children > 0)
                            , {{ $booking->children }} {{ Str::plural('child', $booking->children) }}
                        @endif
                    </dd>
                </div>
                <div class="border-t border-slate-200 pt-3 mt-3 flex justify-between">
                    <dt class="text-slate-900 font-semibold">Amount Due</dt>
                    <dd class="font-bold text-slate-900">${{ number_format($booking->total_amount / 153, 2) }} USD</dd>
                </div>
            </dl>
        </div>

        {{-- Time Warning --}}
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 text-left">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-amber-800">Booking expires in 30 minutes</p>
                    <p class="text-xs text-amber-700 mt-1">Please complete your payment soon to secure your spot on this tour.</p>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('bookings.retry', ['booking' => $booking->reference]) }}" 
               class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Try Payment Again
            </a>
            <a href="{{ route('tours.show', [$booking->city, $booking->tour]) }}" 
               class="inline-flex items-center justify-center px-6 py-2.5 rounded-full border border-slate-300 text-slate-700 text-sm font-semibold hover:bg-slate-50">
                View Tour Details
            </a>
        </div>

        {{-- Help Text --}}
        <p class="text-xs text-slate-500 mt-6">
            Having trouble? Contact us at <a href="mailto:support@greenbus.co.ke" class="text-emerald-600 hover:underline">support@greenbus.co.ke</a>
        </p>
    </div>
</section>
@endsection
