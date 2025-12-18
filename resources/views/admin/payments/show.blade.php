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

            <!-- Payment Details -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Payment Details</h2>
                </div>
                <div class="p-6 grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-500">Transaction ID</p>
                        <p class="font-medium text-slate-900 font-mono">{{ $payment->provider_transaction_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Payment Method</p>
                        <p class="font-medium text-slate-900">{{ $payment->payment_method ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Status</p>
                        <p class="font-medium text-slate-900">{{ $payment->status }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Amount</p>
                        <p class="font-medium text-slate-900">{{ $payment->currency }} {{ number_format($payment->amount) }}</p>
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
                        <p class="font-medium text-slate-900">{{ $payment->booking->tour->title ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Tour Date</p>
                        <p class="font-medium text-slate-900">{{ $payment->booking->date->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Technical Details -->
            @if($payment->raw_payload)
            <div x-data="{ expanded: false, copied: false }" class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-slate-900">Technical Details</h2>
                        <p class="text-sm text-slate-500 mt-1">Payment gateway response data</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button 
                            @click="expanded = !expanded"
                            class="text-sm text-emerald-600 hover:text-emerald-700 font-medium"
                            x-text="expanded ? 'Show Simple' : 'Show Raw JSON'">
                        </button>
                    </div>
                </div>
                
                <!-- Simple View -->
                <div x-show="!expanded" class="p-6">
                    <div class="space-y-4">
                        @php
                            $payload = $payment->raw_payload;
                            $summary = [];
                            
                            // Extract key information for simple view
                            if (is_array($payload)) {
                                if (isset($payload['status'])) $summary[] = ['Status' => $payload['status']];
                                if (isset($payload['transaction_id'])) $summary[] = ['Transaction ID' => $payload['transaction_id']];
                                if (isset($payload['amount'])) $summary[] = ['Amount' => ($payload['currency'] ?? '') . ' ' . ($payload['amount'] ?? '')];
                                if (isset($payload['payment_method'])) $summary[] = ['Payment Method' => $payload['payment_method']];
                                if (isset($payload['created_at'])) $summary[] = ['Created' => $payload['created_at']];
                                if (isset($payload['response_code'])) $summary[] = ['Response Code' => $payload['response_code']];
                                if (isset($payload['auth_code'])) $summary[] = ['Auth Code' => $payload['auth_code']];
                                
                                // Handle common payment gateway fields
                                if (isset($payload['merchant_reference'])) $summary[] = ['Merchant Reference' => $payload['merchant_reference']];
                                if (isset($payload['gateway_response'])) $summary[] = ['Gateway Response' => $payload['gateway_response']];
                                if (isset($payload['payment_status'])) $summary[] = ['Payment Status' => $payload['payment_status']];
                                if (isset($payload['transaction_status'])) $summary[] = ['Transaction Status' => $payload['transaction_status']];
                                if (isset($payload['failure_reason'])) $summary[] = ['Failure Reason' => $payload['failure_reason']];
                                if (isset($payload['success'])) $summary[] = ['Success' => $payload['success'] ? 'Yes' : 'No'];
                                
                                // Add any other important scalar fields
                                foreach ($payload as $key => $value) {
                                    $excluded_keys = ['status', 'transaction_id', 'amount', 'currency', 'payment_method', 'created_at', 'response_code', 'auth_code', 'merchant_reference', 'gateway_response', 'payment_status', 'transaction_status', 'failure_reason', 'success'];
                                    if (!in_array($key, $excluded_keys) && is_scalar($value) && strlen($value) < 200) {
                                        $summary[] = [ucwords(str_replace('_', ' ', $key)) => $value];
                                        if (count($summary) >= 10) break; // Limit to 10 items
                                    }
                                }
                            }
                        @endphp
                        
                        @if(!empty($summary))
                            @foreach($summary as $item)
                                @foreach($item as $key => $value)
                                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                    <span class="text-sm font-medium text-slate-700">{{ $key }}</span>
                                    <span class="text-sm text-slate-900 font-mono">{{ $value }}</span>
                                </div>
                                @endforeach
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-slate-500">No structured data available</p>
                                <p class="text-sm text-slate-400 mt-1">View raw JSON for complete details</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Raw JSON View -->
                <div x-show="expanded" class="p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-sm text-slate-600">Complete payment gateway response in JSON format</p>
                        <button 
                            @click="navigator.clipboard.writeText(document.getElementById('raw-payload').textContent); copied = true; setTimeout(() => copied = false, 2000)"
                            class="flex items-center gap-2 px-3 py-1 text-sm bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                    <div class="relative">
                        <pre id="raw-payload" class="bg-slate-900 text-slate-100 p-4 rounded-lg text-xs overflow-x-auto font-mono leading-relaxed">{{ json_encode($payment->raw_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
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
