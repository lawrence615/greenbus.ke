@extends('layouts.admin')

@section('title', 'Payment Details')
@section('page-title', 'Payment Details')

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <div>
        <a href="{{ route('console.payments.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Payments
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-slate-900">Payment Information</h2>
                        <p class="text-sm text-slate-500 font-mono">{{ $payment->provider_reference ?? 'No reference' }}</p>
                    </div>
                    @php $status = \App\Enums\PaymentStatus::tryFrom($payment->status) @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($status?->isSuccessful()) bg-emerald-100 text-emerald-700
                        @elseif($payment->status === 'failed' || $payment->status === 'cancelled') bg-red-100 text-red-700
                        @else bg-yellow-100 text-yellow-700
                        @endif">
                        {{ $status?->label() ?? $payment->status }}
                    </span>
                </div>
                <div class="p-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-500">Provider</p>
                        <p class="font-medium text-slate-900 capitalize">{{ $payment->provider }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Amount</p>
                        <p class="font-medium text-slate-900">{{ $payment->currency }} {{ number_format($payment->amount) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Created</p>
                        <p class="font-medium text-slate-900">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Last Updated</p>
                        <p class="font-medium text-slate-900">{{ $payment->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Associated Booking -->
            @if($payment->booking)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-900">Associated Booking</h2>
                    <a href="{{ route('console.bookings.show', $payment->booking) }}" class="text-sm text-emerald-600 hover:text-emerald-700">
                        View Booking
                    </a>
                </div>
                <div class="p-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-500">Reference</p>
                        <p class="font-medium text-slate-900 font-mono">{{ $payment->booking->reference }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Customer</p>
                        <p class="font-medium text-slate-900">{{ $payment->booking->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Tour</p>
                        <p class="font-medium text-slate-900">{{ $payment->booking->tour->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Tour Date</p>
                        <p class="font-medium text-slate-900">{{ $payment->booking->date->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Raw Payload -->
            @if($payment->raw_payload)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Raw Payload</h2>
                </div>
                <div class="p-6">
                    <pre class="bg-slate-900 text-slate-100 p-4 rounded-lg text-xs overflow-x-auto">{{ json_encode($payment->raw_payload, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    @if($payment->booking)
                    <a href="{{ route('console.bookings.show', $payment->booking) }}" class="flex items-center gap-2 text-sm text-slate-600 hover:text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        View Booking
                    </a>
                    <a href="mailto:{{ $payment->booking->customer_email }}" class="flex items-center gap-2 text-sm text-slate-600 hover:text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Email Customer
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
