@extends('layouts.customer')

@section('title', 'My Bookings')
@section('page-title', 'My Bookings')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" action="{{ route('customer.bookings.index') }}" class="flex flex-wrap items-end justify-end gap-4">
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-slate-700 mb-1">Date Range</label>
                <input type="text" id="date-range" placeholder="Select date range" readonly class="w-full sm:w-64 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer">
                <input type="hidden" name="date_from" id="date_from" value="{{ request('date_from') }}">
                <input type="hidden" name="date_to" id="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full sm:w-48 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 cursor-pointer">
                Filter
            </button>
            @if(request('status') || request('date_from') || request('date_to'))
            <a href="{{ route('customer.bookings.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 cursor-pointer">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Bookings List -->
    <div class="space-y-4">
        @forelse($bookings as $booking)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">{{ $booking->tour->title ?? 'N/A' }}</h3>
                                <p class="text-sm text-slate-500">{{ $booking->location->name ?? 'N/A' }}</p>
                                <div class="mt-2 flex flex-wrap items-center gap-3 text-sm">
                                    <span class="flex items-center gap-1 text-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $booking->date->format('M d, Y') }}
                                    </span>
                                    <span class="flex items-center gap-1 text-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $booking->time }}
                                    </span>
                                    <span class="flex items-center gap-1 text-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        {{ $booking->adults + $booking->children + $booking->infants }} guests
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
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
                        <p class="text-lg font-bold text-slate-900">{{ $booking->currency }} {{ number_format($booking->total_amount) }}</p>
                        <p class="text-xs text-slate-500 font-mono">{{ $booking->reference }}</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <p class="text-sm text-slate-500">Booked on {{ $booking->created_at->format('M d, Y') }}</p>
                <a href="{{ route('customer.bookings.show', $booking) }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                    View Details
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <p class="text-slate-500 mb-4">No bookings found</p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                Browse Tours
            </a>
        </div>
        @endforelse
    </div>

    @if($bookings->hasPages())
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeDateRangePicker();
    });

    // Date Range Picker
    function initializeDateRangePicker() {
        const dateRangeInput = document.getElementById('date-range');
        const dateFromInput = document.getElementById('date_from');
        const dateToInput = document.getElementById('date_to');

        // Set initial display value
        updateDateRangeDisplay();

        // Create flatpickr instance
        flatpickr(dateRangeInput, {
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "M j, Y",
            defaultDate: [
                dateFromInput.value ? new Date(dateFromInput.value) : null,
                dateToInput.value ? new Date(dateToInput.value) : null
            ],
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    dateFromInput.value = instance.formatDate(selectedDates[0], 'Y-m-d');
                    dateToInput.value = instance.formatDate(selectedDates[1], 'Y-m-d');
                } else if (selectedDates.length === 1) {
                    dateFromInput.value = instance.formatDate(selectedDates[0], 'Y-m-d');
                    dateToInput.value = instance.formatDate(selectedDates[0], 'Y-m-d');
                } else {
                    dateFromInput.value = '';
                    dateToInput.value = '';
                }
            }
        });
    }

    function updateDateRangeDisplay() {
        const dateFromInput = document.getElementById('date_from');
        const dateToInput = document.getElementById('date_to');
        const dateRangeInput = document.getElementById('date-range');

        if (dateFromInput.value && dateToInput.value) {
            if (dateFromInput.value === dateToInput.value) {
                dateRangeInput.value = new Date(dateFromInput.value).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
            } else {
                const fromDate = new Date(dateFromInput.value).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                });
                const toDate = new Date(dateToInput.value).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
                dateRangeInput.value = `${fromDate} - ${toDate}`;
            }
        }
    }
</script>
@endpush