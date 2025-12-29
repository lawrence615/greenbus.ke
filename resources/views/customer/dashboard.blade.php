@extends('layouts.customer')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl p-6 text-white">
        <h2 class="text-xl font-semibold">Welcome back, {{ auth()->user()->name }}!</h2>
        <p class="mt-1 text-emerald-100">Here's an overview of your bookings.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Bookings</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['total_bookings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Upcoming Tours</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['upcoming_bookings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Completed</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['completed_bookings'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Bookings -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-semibold text-slate-900">Upcoming Tours</h2>
            <a href="{{ route('customer.bookings.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700">View all</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($upcomingBookings as $booking)
            <a href="{{ route('customer.bookings.show', $booking) }}" class="block px-6 py-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-slate-900">{{ $booking->tour->title ?? 'Tour Unavailable' }}</p>
                        <p class="text-sm text-slate-500">{{ $booking->location->name ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-slate-900">{{ $booking->date->format('M d, Y') }}</p>
                        <p class="text-sm text-slate-500">{{ $booking->time }}</p>
                    </div>
                </div>
            </a>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-slate-500 mb-4">No upcoming tours</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                    Browse Tours
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
