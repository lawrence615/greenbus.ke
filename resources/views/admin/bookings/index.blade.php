@extends('layouts.admin')

@section('title', 'Bookings')
@section('page-title', 'Bookings')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush


@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form method="GET" action="{{ route('console.bookings.index') }}" class="flex flex-col xl:flex-row xl:items-end gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 flex-1">
                <!-- Search -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Reference, name, email..."
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                </div>

                <!-- Status -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Status
                    </label>
                    <select name="status" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tour -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tours
                    </label>
                    <div class="relative">
                        <button type="button" onclick="toggleTourDropdown()" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer text-left flex items-center justify-between">
                            <span id="tour-selected-text">All Tours</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="tour-dropdown" class="hidden absolute z-10 w-full mt-1 bg-white border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <div class="p-2 border-b border-slate-200">
                                <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer hover:bg-slate-50 p-1 rounded">
                                    <input type="checkbox" name="tour_ids[]" value="" onchange="updateTourSelection()" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    <span>All Tours</span>
                                </label>
                            </div>
                            @foreach($tours as $tour)
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer hover:bg-slate-50 p-2">
                                <input type="checkbox" name="tour_ids[]" value="{{ $tour->id }}" onchange="updateTourSelection()"
                                    {{ in_array($tour->id, request('tour_ids', [])) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>{{ $tour->title }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Date Range
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            id="date-range"
                            placeholder="Select date range"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer"
                            readonly>
                        <input type="hidden" name="date_from" id="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" id="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'tour_ids', 'tour_id', 'date_from', 'date_to']))
                <a href="{{ route('console.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 border border-slate-300 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-b from-slate-50 to-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Booking
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Schedule
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left hidden xl:table-cell">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Guests
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Amount
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Status
                            </div>
                        </th>
                        <th class="px-4 py-3 text-right">
                            <span class="font-semibold text-slate-700 uppercase text-xs tracking-wider">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-emerald-50/50 transition-colors duration-150 group cursor-pointer" onclick="window.location.href='{{ route('console.bookings.show', $booking->id) }}'">
                        <td class="px-4 py-3">
                            <div class="min-w-0">
                                <!-- Booking Reference -->
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-medium bg-slate-100 text-slate-600 mb-1">
                                    {{ $booking->reference }}
                                </span>
                                <!-- Customer Name -->
                                <div class="font-semibold text-slate-900 truncate max-w-[180px] lg:max-w-[220px]" title="{{ $booking->customer_name }}">
                                    {{ $booking->customer_name }}
                                </div>
                                <!-- Tour Code & Mobile Info -->
                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1 text-xs text-slate-500">
                                    @if($booking->tour)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 font-medium" title="{{ $booking->tour->title }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $booking->tour->code ?? 'N/A' }}
                                    </span>
                                    @endif
                                    <!-- Mobile: Show date inline -->
                                    <span class="lg:hidden flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $booking->date->format('M d') }}
                                    </span>
                                    <!-- Mobile: Show amount inline -->
                                    <span class="md:hidden font-medium text-emerald-600">
                                        {{ $booking->currency }} {{ number_format($booking->total_amount) }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-900">{{ $booking->date->format('D, M d, Y') }}</span>
                                <span class="text-xs text-slate-500">{{ $booking->time }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden xl:table-cell">
                            <div class="flex items-center gap-1">
                                @if($booking->adults > 0)
                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-medium" title="Adults">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $booking->adults }}
                                </span>
                                @endif
                                @if($booking->children > 0)
                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 text-[10px] font-medium" title="Children">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $booking->children }}
                                </span>
                                @endif
                                @if($booking->infants > 0)
                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-pink-50 text-pink-700 text-[10px] font-medium" title="Infants">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    {{ $booking->infants }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-900">{{ $booking->currency }} {{ number_format($booking->total_amount) }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @php $status = \App\Enums\BookingStatus::tryFrom($booking->status) @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-semibold
                                @if($status?->color() === 'green') bg-emerald-100 text-emerald-700
                                @elseif($status?->color() === 'yellow') bg-yellow-100 text-yellow-700
                                @elseif($status?->color() === 'red') bg-red-100 text-red-700
                                @elseif($status?->color() === 'blue') bg-blue-100 text-blue-700
                                @else bg-slate-100 text-slate-700
                                @endif">
                                <span class="w-1.5 h-1.5 rounded-full 
                                    @if($status?->color() === 'green') bg-emerald-500
                                    @elseif($status?->color() === 'yellow') bg-yellow-500
                                    @elseif($status?->color() === 'red') bg-red-500
                                    @elseif($status?->color() === 'blue') bg-blue-500
                                    @else bg-slate-500
                                    @endif"></span>
                                {{ $status?->label() ?? $booking->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('console.bookings.show', $booking) }}" onclick="event.stopPropagation()" class="p-1.5 inline-flex text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors duration-150" title="View Details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium">No bookings found</p>
                                <p class="text-slate-400 text-sm mt-1">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    function toggleTourDropdown() {
        const dropdown = document.getElementById('tour-dropdown');
        dropdown.classList.toggle('hidden');

        // Close dropdown when clicking outside
        document.addEventListener('click', function closeDropdown(e) {
            if (!e.target.closest('#tour-dropdown') && !e.target.closest('button[onclick="toggleTourDropdown()"]')) {
                dropdown.classList.add('hidden');
                document.removeEventListener('click', closeDropdown);
            }
        });
    }

    function updateTourSelection() {
        const checkboxes = document.querySelectorAll('input[name="tour_ids[]"]');
        const selectedText = document.getElementById('tour-selected-text');
        const allCheckbox = document.querySelector('input[name="tour_ids[]"][value=""]');

        let checkedCount = 0;
        let totalCount = 0;

        checkboxes.forEach(checkbox => {
            if (checkbox.value !== '') {
                totalCount++;
                if (checkbox.checked) {
                    checkedCount++;
                }
            }
        });

        // Handle "All Tours" checkbox
        if (allCheckbox.checked) {
            // Uncheck all individual tours when "All Tours" is checked
            checkboxes.forEach(checkbox => {
                if (checkbox.value !== '') {
                    checkbox.checked = false;
                }
            });
            selectedText.textContent = 'All Tours';
        } else if (checkedCount === 0) {
            selectedText.textContent = 'All Tours';
        } else if (checkedCount === 1) {
            // Show the selected tour name
            const checked = Array.from(checkboxes).find(cb => cb.checked && cb.value !== '');
            if (checked) {
                const label = checked.closest('label').querySelector('span').textContent;
                selectedText.textContent = label;
            }
        } else {
            selectedText.textContent = `${checkedCount} tours selected`;
        }

        // Auto-submit form when selection changes (optional)
        // document.querySelector('form').submit();
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTourSelection();
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