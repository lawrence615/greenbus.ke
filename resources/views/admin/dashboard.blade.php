@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Bookings</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_bookings']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Confirmed Bookings</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['confirmed_bookings']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Pending Payment</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['pending_bookings']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Revenue</p>
                    <p class="text-2xl font-bold text-slate-900">USD {{ number_format($stats['total_revenue']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="font-semibold text-slate-900">Recent Bookings</h2>
                <a href="{{ route('console.bookings.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700">View all</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentBookings as $booking)
                <a href="{{ route('console.bookings.show', $booking) }}" class="block px-6 py-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-900">{{ $booking->customer_name }}</p>
                            <p class="text-sm text-slate-500">{{ $booking->tour->title ?? 'Tour Unavailable' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-slate-900">{{ $booking->reference }}</p>
                            @php $status = \App\Enums\BookingStatus::tryFrom($booking->status) @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                @if($status?->color() === 'green') bg-emerald-100 text-emerald-700
                                @elseif($status?->color() === 'yellow') bg-yellow-100 text-yellow-700
                                @elseif($status?->color() === 'red') bg-red-100 text-red-700
                                @elseif($status?->color() === 'blue') bg-blue-100 text-blue-700
                                @else bg-slate-100 text-slate-700
                                @endif">
                                {{ $status?->label() ?? $booking->status }}
                            </span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="px-6 py-8 text-center text-slate-500">
                    No bookings yet.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="font-semibold text-slate-900">Recent Payments</h2>
                <a href="{{ route('console.payments.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700">View all</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentPayments as $payment)
                <a href="{{ route('console.payments.show', $payment) }}" class="block px-6 py-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-900">{{ $payment->booking->customer_name ?? 'N/A' }}</p>
                            <p class="text-sm text-slate-500">{{ Str::limit($payment->booking->tour->title, 120) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-emerald-600">USD {{ number_format($payment->amount) }}</p>
                            <p class="text-xs text-slate-500">{{ $payment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
                @empty
                <div class="px-6 py-8 text-center text-slate-500">
                    No payments yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection