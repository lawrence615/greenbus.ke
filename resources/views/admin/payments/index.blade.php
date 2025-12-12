@extends('layouts.admin')

@section('title', 'Payments')
@section('page-title', 'Payments')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Successful</p>
                    <p class="text-2xl font-bold text-slate-900">KES {{ number_format($totalSuccessful) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Pending</p>
                    <p class="text-2xl font-bold text-slate-900">KES {{ number_format($totalPending) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form method="GET" action="{{ route('console.payments.index') }}" class="flex flex-col xl:flex-row xl:items-end gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 flex-1">
                <!-- Search -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Reference, email..."
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"
                    >
                </div>
                
                <!-- Status -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                
                <!-- Provider -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Provider
                    </label>
                    <select name="provider" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer">
                        <option value="">All Providers</option>
                        @foreach($providers as $provider)
                        <option value="{{ $provider }}" {{ request('provider') === $provider ? 'selected' : '' }}>
                            {{ ucfirst($provider) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date From -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Date From
                    </label>
                    <input 
                        type="date" 
                        name="date_from" 
                        value="{{ request('date_from') }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"
                    >
                </div>
                
                <!-- Date To -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Date To
                    </label>
                    <input 
                        type="date" 
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"
                    >
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'provider', 'date_from', 'date_to']))
                <a href="{{ route('console.payments.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 border border-slate-300 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-b from-slate-50 to-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Payment
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Amount
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Date
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    @forelse($payments as $payment)
                    <tr class="hover:bg-emerald-50/50 transition-colors duration-150 group">
                        <td class="px-4 py-3">
                            <div class="min-w-0">
                                <!-- Provider Reference -->
                                <a href="{{ route('console.payments.show', $payment) }}" class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-medium bg-slate-100 text-slate-600 hover:bg-emerald-100 hover:text-emerald-700 transition-colors mb-1">
                                    {{ Str::limit($payment->provider_reference ?? 'N/A', 16) }}
                                </a>
                                <!-- Customer Name - Clickable -->
                                @if($payment->booking)
                                <a href="{{ route('console.payments.show', $payment) }}" class="block font-semibold text-slate-900 hover:text-emerald-600 transition-colors truncate max-w-[160px] lg:max-w-[200px]" title="{{ $payment->booking->customer_name }}">
                                    {{ $payment->booking->customer_name }}
                                </a>
                                @else
                                <span class="block text-slate-400 italic text-sm">No booking linked</span>
                                @endif
                                <!-- Provider & Mobile Info -->
                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1 text-xs text-slate-500">
                                    @php
                                        $providerColors = [
                                            'flutterwave' => 'bg-orange-50 text-orange-600',
                                            'stripe' => 'bg-purple-50 text-purple-600',
                                            'mpesa' => 'bg-green-50 text-green-600',
                                        ];
                                        $providerClass = $providerColors[$payment->provider] ?? 'bg-slate-50 text-slate-600';
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded font-medium {{ $providerClass }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        {{ ucfirst($payment->provider) }}
                                    </span>
                                    <!-- Mobile: Show amount inline -->
                                    <span class="md:hidden font-medium text-emerald-600">
                                        {{ $payment->currency }} {{ number_format($payment->amount) }}
                                    </span>
                                    <!-- Mobile: Show date inline -->
                                    <span class="lg:hidden flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $payment->created_at->format('M d') }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="font-semibold text-slate-900">{{ $payment->currency }} {{ number_format($payment->amount) }}</span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-900">{{ $payment->created_at->format('M d, Y') }}</span>
                                <span class="text-xs text-slate-500">{{ $payment->created_at->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @php $status = \App\Enums\PaymentStatus::tryFrom($payment->status) @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-semibold
                                @if($status?->isSuccessful()) bg-emerald-100 text-emerald-700
                                @elseif($payment->status === 'failed' || $payment->status === 'cancelled') bg-red-100 text-red-700
                                @elseif($payment->status === 'refunded') bg-slate-100 text-slate-700
                                @else bg-yellow-100 text-yellow-700
                                @endif">
                                <span class="w-1.5 h-1.5 rounded-full 
                                    @if($status?->isSuccessful()) bg-emerald-500
                                    @elseif($payment->status === 'failed' || $payment->status === 'cancelled') bg-red-500
                                    @elseif($payment->status === 'refunded') bg-slate-500
                                    @else bg-yellow-500
                                    @endif"></span>
                                {{ $status?->label() ?? $payment->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('console.payments.show', $payment) }}" class="p-1.5 inline-flex text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors duration-150" title="View Details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium">No payments found</p>
                                <p class="text-slate-400 text-sm mt-1">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
