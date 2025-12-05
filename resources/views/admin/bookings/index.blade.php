@extends('layouts.admin')

@section('title', 'Bookings')
@section('page-title', 'Bookings')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" action="{{ route('console.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Search</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Reference, name, email..."
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tour</label>
                <select name="tour_id" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">All Tours</option>
                    @foreach($tours as $tour)
                    <option value="{{ $tour->id }}" {{ request('tour_id') == $tour->id ? 'selected' : '' }}>
                        {{ $tour->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Date From</label>
                <input 
                    type="date" 
                    name="date_from" 
                    value="{{ request('date_from') }}"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                >
            </div>
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date To</label>
                    <input 
                        type="date" 
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    >
                </div>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'tour_id', 'date_from', 'date_to']))
                <a href="{{ route('console.bookings.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200">
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
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Reference</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Customer</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Tour</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Date</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Guests</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Amount</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Status</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded">{{ $booking->reference }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-slate-900">{{ $booking->customer_name }}</p>
                                <p class="text-xs text-slate-500">{{ $booking->customer_email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-900">{{ $booking->tour->name ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-900">{{ $booking->date->format('M d, Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $booking->time }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-900">{{ $booking->adults + $booking->children + $booking->infants }}</p>
                            <p class="text-xs text-slate-500">{{ $booking->adults }}A, {{ $booking->children }}C, {{ $booking->infants }}I</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-900">{{ $booking->currency }} {{ number_format($booking->total_amount) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php $status = \App\Enums\BookingStatus::tryFrom($booking->status) @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($status?->color() === 'green') bg-emerald-100 text-emerald-700
                                @elseif($status?->color() === 'yellow') bg-yellow-100 text-yellow-700
                                @elseif($status?->color() === 'red') bg-red-100 text-red-700
                                @elseif($status?->color() === 'blue') bg-blue-100 text-blue-700
                                @else bg-slate-100 text-slate-700
                                @endif">
                                {{ $status?->label() ?? $booking->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('console.bookings.show', $booking) }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                            No bookings found.
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
