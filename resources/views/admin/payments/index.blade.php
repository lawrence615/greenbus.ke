@extends('layouts.admin')

@section('title', 'Payments')
@section('page-title', 'Payments')

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
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" action="{{ route('console.payments.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Search</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Reference, email..."
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
                <label class="block text-sm font-medium text-slate-700 mb-1">Provider</label>
                <select name="provider" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">All Providers</option>
                    @foreach($providers as $provider)
                    <option value="{{ $provider }}" {{ request('provider') === $provider ? 'selected' : '' }}>
                        {{ ucfirst($provider) }}
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
                @if(request()->hasAny(['search', 'status', 'provider', 'date_from', 'date_to']))
                <a href="{{ route('console.payments.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200">
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
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Reference</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Booking</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Provider</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Amount</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Date</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded">{{ $payment->provider_reference ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($payment->booking)
                            <div>
                                <p class="font-medium text-slate-900">{{ $payment->booking->reference }}</p>
                                <p class="text-xs text-slate-500">{{ $payment->booking->customer_email }}</p>
                            </div>
                            @else
                            <span class="text-slate-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="capitalize text-slate-900">{{ $payment->provider }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-900">{{ $payment->currency }} {{ number_format($payment->amount) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php $status = \App\Enums\PaymentStatus::tryFrom($payment->status) @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($status?->isSuccessful()) bg-emerald-100 text-emerald-700
                                @elseif($payment->status === 'failed' || $payment->status === 'cancelled') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700
                                @endif">
                                {{ $status?->label() ?? $payment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-900">{{ $payment->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $payment->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('console.payments.show', $payment) }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            No payments found.
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
