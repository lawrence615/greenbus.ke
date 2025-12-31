@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.13.2/build/css/intlTelInput.css">
<style>
    .iti {
        width: 100%;
    }

    .iti__flag {
        background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@25.13.2/build/img/flags.png");
    }

    @media (-webkit-min-device-pixel-ratio: 2),
    (min-resolution: 192dpi) {
        .iti__flag {
            background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@25.13.2/build/img/flags@2x.png");
        }
    }

    .iti__country-list {
        z-index: 9999;
    }
</style>
@endpush

@section('page-title', 'Book Custom Tour - ' . $tour->title)

@section('content')
@php
// Define pricing variables for the entire view
$adultPricing = $tour->pricings->where('person_type', 'adult')->first();
$youthPricing = $tour->pricings->where('person_type', 'youth')->first();
$childPricing = $tour->pricings->where('person_type', 'child')->first();
$infantPricing = $tour->pricings->where('person_type', 'infant')->first();
$seniorPricing = $tour->pricings->where('person_type', 'senior')->first();
$individualPricing = $tour->pricings->where('person_type', 'individual')->first();

// Check if we have specific group pricing or individual pricing
$hasGroupPricing = $adultPricing || $youthPricing || $childPricing || $infantPricing || $seniorPricing;
$hasIndividualPricing = $individualPricing;

// Get prices for JavaScript
$adultPrice = $adultPricing ? $adultPricing->price : 0;
$youthPrice = $youthPricing ? $youthPricing->price : 0;
$childPrice = $childPricing ? $childPricing->price : 0;
$infantPrice = $infantPricing ? $infantPricing->price : 0;
$seniorPrice = $seniorPricing ? $seniorPricing->price : 0;
$individualPrice = $individualPricing ? $individualPricing->price : 0;

// If no pricing is set at all, user cannot proceed
if (!$hasGroupPricing && !$hasIndividualPricing) {
abort(403, 'This tour has no pricing configured. Please contact the tour provider.');
}
@endphp
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Back to Tour -->
        <a href="{{ route('bookings.share.tour', $shareToken) }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to tour details
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-8" data-pricing-config='{!! json_encode([
                        ' hasGroupPricing'=> $hasGroupPricing,
                    'hasIndividualPricing' => $hasIndividualPricing,
                    'prices' => [
                    'adult' => $adultPrice,
                    'youth' => $youthPrice,
                    'child' => $childPrice,
                    'infant' => $infantPrice,
                    'senior' => $seniorPrice,
                    'individual' => $individualPrice
                    ]
                    ]) !!}'>
                    <h1 class="text-2xl font-bold text-slate-900 mb-6">Book Your Custom Tour</h1>

                    <!-- Error Messages -->
                    @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Error</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    {{ session('error') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('bookings.share.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                        <input type="hidden" name="share_token" value="{{ $shareToken }}">

                        <!-- Tour Summary -->
                        <div class="bg-slate-50 rounded-lg p-4 mb-6">
                            <h3 class="font-semibold text-slate-900 mb-2">{{ $tour->title }}</h3>
                            <p class="text-sm text-slate-600">{{ $tour->location->name }} • {{ $tour->duration_text ?? 'Flexible duration' }}</p>
                        </div>

                        <!-- Contact Information -->
                        <div class="space-y-4">
                            <h3 class="font-semibold text-slate-900">Contact Information</h3>

                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-slate-700 mb-1">Full Name *</label>
                                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 @error('customer_name') border-red-300 @enderror"
                                    value="{{ old('customer_name') }}"
                                    placeholder="Enter your full name">
                                @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-slate-700 mb-1">Email Address *</label>
                                <input type="customer_email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 @error('customer_email') border-red-300 @enderror">
                                @error('customer_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number *</label>
                                <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="space-y-4">
                            <h3 class="font-semibold text-slate-900">Booking Details</h3>

                            <div>
                                <label for="date" class="block text-sm font-medium text-slate-700 mb-1">Preferred Date *</label>
                                <input type="date" id="date" name="date" value="{{ old('date') }}"
                                    min="{{ now()->format('Y-m-d') }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 @error('date') border-red-300 @enderror">
                                @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Traveller Selection -->
                            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                                <div class="bg-linear-to-r from-blue-50 to-purple-50 px-4 py-3 border-b border-slate-100">
                                    <h4 class="font-semibold text-slate-800">Number of Travellers</h4>
                                </div>
                                <div class="p-4">
                                    <!-- Adults (always shown) -->
                                    @if($hasGroupPricing)
                                    <div class="bg-slate-50 rounded-lg border border-slate-100 mb-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">Adults (18-63)</p>
                                                <p class="text-xs text-slate-500 font-medium">Required minimum 1</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button type="button" onclick="updateTraveller('adults', -1, 1)" class="w-8 h-8 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <input type="number" min="1" id="adults" name="adults" value="{{ old('adults', 1) }}" class="w-10 border-0 bg-transparent text-center text-base font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" onclick="updateTraveller('adults', 1, 1)" class="w-8 h-8 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Additional Travellers -->
                                    <div class="space-y-3">
                                        @if($hasGroupPricing)
                                        <!-- Show group pricing options -->
                                        @if($seniorPricing)
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">Seniors (63+)</p>
                                                <p class="text-xs text-blue-600 font-medium">USD {{ number_format($seniorPricing->price) }} each</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button type="button" onclick="updateTraveller('seniors', -1, 0)" class="w-8 h-8 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <input type="number" min="0" id="seniors" name="seniors" value="0" class="w-10 border-0 bg-transparent text-center text-base font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" onclick="updateTraveller('seniors', 1, 0)" class="w-8 h-8 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        @endif

                                        @if($youthPricing)
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">Youth (13-17)</p>
                                                <p class="text-xs text-blue-600 font-medium">USD {{ number_format($youthPricing->price) }} each</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button type="button" onclick="updateTraveller('youth', -1, 0)" class="w-8 h-8 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <input type="number" min="0" id="youth" name="youth" value="0" class="w-10 border-0 bg-transparent text-center text-base font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" onclick="updateTraveller('youth', 1, 0)" class="w-8 h-8 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        @endif

                                        @if($childPricing)
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">Children (5-12)</p>
                                                <p class="text-xs text-blue-600 font-medium">USD {{ number_format($childPricing->price) }} each</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button type="button" onclick="updateTraveller('children', -1, 0)" class="w-8 h-8 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <input type="number" min="0" id="children" name="children" value="0" class="w-10 border-0 bg-transparent text-center text-base font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" onclick="updateTraveller('children', 1, 0)" class="w-8 h-8 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        @endif

                                        @if($infantPricing)
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">Infants (0-4)</p>
                                                <p class="text-xs text-blue-600 font-medium">{{ $infantPricing->price > 0 ? 'USD ' . number_format($infantPricing->price) . ' each' : 'Free' }}</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button type="button" onclick="updateTraveller('infants', -1, 0)" class="w-8 h-8 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <input type="number" min="0" id="infants" name="infants" value="0" class="w-10 border-0 bg-transparent text-center text-base font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" onclick="updateTraveller('infants', 1, 0)" class="w-8 h-8 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        @endif

                                        @elseif($hasIndividualPricing)
                                        <!-- Show individual pricing option -->
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">Individual</p>
                                                <p class="text-xs text-blue-600 font-medium">USD {{ number_format($individualPricing->price) }} each</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button type="button" onclick="updateTraveller('individuals', -1, 0)" class="w-8 h-8 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <input type="number" min="0" id="individuals" name="individuals" value="{{ old('individuals', 1) }}" class="w-10 border-0 bg-transparent text-center text-base font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" onclick="updateTraveller('individuals', 1, 0)" class="w-8 h-8 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="special_requests" class="block text-sm font-medium text-slate-700 mb-1">Special Requests (Optional)</label>
                                <textarea id="special_requests" name="special_requests" rows="3"
                                    placeholder="Any special requirements or requests..."
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6">
                            <button type="submit"
                                class="w-full bg-linear-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all cursor-pointer">
                                Proceed to Payment
                            </button>
                            <p class="text-xs text-slate-500 mt-2 text-center">
                                By booking, you agree to our terms and conditions
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tour Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Tour Summary</h3>

                    @if($tour->images->isNotEmpty())
                    <img src="{{ $tour->images->first()->url }}" alt="{{ $tour->title }}"
                        class="w-full h-48 object-cover rounded-lg mb-4">
                    @endif

                    <h4 class="font-medium text-slate-900 mb-2">{{ $tour->title }}</h4>
                    <p class="text-sm text-slate-600 mb-4">{{ $tour->location->name }}</p>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Duration:</span>
                            <span class="font-medium">{{ $tour->duration_text ?? 'Flexible' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Meeting Point:</span>
                            <span class="font-medium">{{ $tour->meeting_point ?? 'To be arranged' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Max Group Size:</span>
                            <span class="font-medium">{{ $tour->no_of_people }} people</span>
                        </div>
                    </div>

                    @if($tour->pricings->isNotEmpty())
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <h4 class="font-medium text-slate-900 mb-2">Price breakdown</h4>
                        <div class="space-y-2 text-sm" id="price-breakdown">
                            @if($hasGroupPricing)
                            @if($adultPricing)
                            <div class="flex justify-between text-slate-600">
                                <span>Adults (18-63) (<span id="summary-adults">1</span> x USD {{ number_format($adultPricing->price) }})</span>
                                <span id="total-adults">USD {{ number_format($adultPricing->price) }}</span>
                            </div>
                            @endif

                            @if($seniorPricing)
                            <div class="flex justify-between text-slate-600" id="seniors-row" style="display: none;">
                                <span>Seniors (63+) (<span id="summary-seniors">0</span> x USD {{ number_format($seniorPricing->price) }})</span>
                                <span id="total-seniors">USD 0</span>
                            </div>
                            @endif

                            @if($youthPricing)
                            <div class="flex justify-between text-slate-600" id="youth-row" style="display: none;">
                                <span>Youth (13-17) (<span id="summary-youth">0</span> x USD {{ number_format($youthPricing->price) }})</span>
                                <span id="total-youth">USD 0</span>
                            </div>
                            @endif

                            @if($childPricing)
                            <div class="flex justify-between text-slate-600" id="children-row" style="display: none;">
                                <span>Children (5-12) (<span id="summary-children">0</span> x USD {{ number_format($childPricing->price) }})</span>
                                <span id="total-children">USD 0</span>
                            </div>
                            @endif

                            @if($infantPricing)
                            <div class="flex justify-between text-slate-600" id="infants-row" style="display: none;">
                                <span>Infants (0-4) (<span id="summary-infants">0</span>@if($infantPricing->price > 0) x USD {{ number_format($infantPricing->price) }}@endif)</span>
                                <span id="total-infants" class="text-blue-600">{{ $infantPricing->price > 0 ? 'USD 0' : 'Free' }}</span>
                            </div>
                            @endif
                            @elseif($hasIndividualPricing)
                            <div class="flex justify-between text-slate-600">
                                <span>Individuals (<span id="summary-individuals">1</span> x USD {{ number_format($individualPricing->price) }})</span>
                                <span id="total-individuals">USD {{ number_format($individualPricing->price) }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="border-t border-slate-100 mt-3 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-slate-900">Total</span>
                                <span class="text-lg font-bold text-blue-600" id="grand-total">USD {{ number_format($hasGroupPricing ? ($adultPricing->price ?? 0) : ($individualPricing->price ?? 0)) }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-700">
                            <strong>This is a custom tour</strong> designed specifically for you.
                            All details can be finalized after booking.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
// Define pricing variables for JavaScript use
$adultPricing = $tour->pricings->where('person_type', 'adult')->first();
$youthPricing = $tour->pricings->where('person_type', 'youth')->first();
$childPricing = $tour->pricings->where('person_type', 'child')->first();
$infantPricing = $tour->pricings->where('person_type', 'infant')->first();
$seniorPricing = $tour->pricings->where('person_type', 'senior')->first();
$individualPricing = $tour->pricings->where('person_type', 'individual')->first();

// Check if we have specific group pricing or individual pricing
$hasGroupPricing = $adultPricing || $youthPricing || $childPricing || $infantPricing || $seniorPricing;
$hasIndividualPricing = $individualPricing;

// If no pricing is set at all, user cannot proceed
if (!$hasGroupPricing && !$hasIndividualPricing) {
abort(403, 'This tour has no pricing configured. Please contact the tour provider.');
}
@endphp
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.13.2/build/js/intlTelInput.min.js"></script>
<script>
    // Pricing constants and configuration - read from data attribute
    const pricingConfigElement = document.querySelector('[data-pricing-config]');
    const pricingConfig = pricingConfigElement ? JSON.parse(pricingConfigElement.dataset.pricingConfig) : {
        hasGroupPricing: false,
        hasIndividualPricing: false,
        prices: {
            adult: 0,
            youth: 0,
            child: 0,
            infant: 0,
            senior: 0,
            individual: 0
        }
    };

    const HAS_GROUP_PRICING = pricingConfig.hasGroupPricing;
    const HAS_INDIVIDUAL_PRICING = pricingConfig.hasIndividualPricing;
    const PRICE_ADULT = pricingConfig.prices.adult;
    const PRICE_YOUTH = pricingConfig.prices.youth;
    const PRICE_CHILD = pricingConfig.prices.child;
    const PRICE_INFANT = pricingConfig.prices.infant;
    const PRICE_SENIOR = pricingConfig.prices.senior;
    const PRICE_INDIVIDUAL = pricingConfig.prices.individual;

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function updatePriceSummary() {
        let grandTotal = 0;

        if (HAS_GROUP_PRICING) {
            // Handle group pricing
            const adultsInput = document.getElementById('adults');
            const youthInput = document.getElementById('youth');
            const childrenInput = document.getElementById('children');
            const infantsInput = document.getElementById('infants');
            const seniorsInput = document.getElementById('seniors');

            const adults = parseInt(adultsInput?.value || '1', 10);
            const youth = parseInt(youthInput?.value || '0', 10);
            const children = parseInt(childrenInput?.value || '0', 10);
            const infants = parseInt(infantsInput?.value || '0', 10);
            const seniors = parseInt(seniorsInput?.value || '0', 10);

            // Update summary counts
            const summaryAdults = document.getElementById('summary-adults');
            const summaryYouth = document.getElementById('summary-youth');
            const summaryChildren = document.getElementById('summary-children');
            const summaryInfants = document.getElementById('summary-infants');
            const summarySeniors = document.getElementById('summary-seniors');

            if (summaryAdults) summaryAdults.textContent = adults;
            if (summaryYouth) summaryYouth.textContent = youth;
            if (summaryChildren) summaryChildren.textContent = children;
            if (summaryInfants) summaryInfants.textContent = infants;
            if (summarySeniors) summarySeniors.textContent = seniors;

            // Calculate totals
            const totalAdults = adults * PRICE_ADULT;
            const totalYouth = youth * PRICE_YOUTH;
            const totalChildren = children * PRICE_CHILD;
            const totalInfants = infants * PRICE_INFANT;
            const totalSeniors = seniors * PRICE_SENIOR;
            grandTotal = totalAdults + totalYouth + totalChildren + totalInfants + totalSeniors;

            // Update displayed totals
            const totalAdultsEl = document.getElementById('total-adults');
            const totalYouthEl = document.getElementById('total-youth');
            const totalChildrenEl = document.getElementById('total-children');
            const totalInfantsEl = document.getElementById('total-infants');
            const totalSeniorsEl = document.getElementById('total-seniors');

            if (totalAdultsEl) totalAdultsEl.textContent = 'USD ' + formatNumber(totalAdults);
            if (totalYouthEl) totalYouthEl.textContent = 'USD ' + formatNumber(totalYouth);
            if (totalChildrenEl) totalChildrenEl.textContent = 'USD ' + formatNumber(totalChildren);
            if (totalInfantsEl) totalInfantsEl.textContent = PRICE_INFANT > 0 ? 'USD ' + formatNumber(totalInfants) : 'Free';
            if (totalSeniorsEl) totalSeniorsEl.textContent = 'USD ' + formatNumber(totalSeniors);

            // Show/hide rows based on selection
            const youthRow = document.getElementById('youth-row');
            const childrenRow = document.getElementById('children-row');
            const infantsRow = document.getElementById('infants-row');
            const seniorsRow = document.getElementById('seniors-row');

            if (youthRow) youthRow.style.display = youth > 0 ? 'flex' : 'none';
            if (childrenRow) childrenRow.style.display = children > 0 ? 'flex' : 'none';
            if (infantsRow) infantsRow.style.display = infants > 0 ? 'flex' : 'none';
            if (seniorsRow) seniorsRow.style.display = seniors > 0 ? 'flex' : 'none';

        } else if (HAS_INDIVIDUAL_PRICING) {
            // Handle individual pricing
            const individualsInput = document.getElementById('individuals');
            const individuals = parseInt(individualsInput?.value || '1', 10);

            // Update summary count
            const summaryIndividuals = document.getElementById('summary-individuals');
            if (summaryIndividuals) summaryIndividuals.textContent = individuals;

            // Calculate total
            grandTotal = individuals * PRICE_INDIVIDUAL;

            // Update displayed total
            const totalIndividualsEl = document.getElementById('total-individuals');
            if (totalIndividualsEl) totalIndividualsEl.textContent = 'USD ' + formatNumber(grandTotal);
        }

        // Update grand total
        const grandTotalEl = document.getElementById('grand-total');
        if (grandTotalEl) grandTotalEl.textContent = 'USD ' + formatNumber(grandTotal);
    }

    function updateTraveller(field, delta, min) {
        const input = document.getElementById(field);
        if (!input) return;
        const current = parseInt(input.value || '0', 10);
        const next = Math.max(min, current + delta);
        input.value = next;
        updatePriceSummary();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Make date input fully clickable
        const dateInput = document.getElementById('tour_date');
        if (dateInput) {
            dateInput.addEventListener('click', function() {
                this.showPicker();
            });
        }

        // Initialize price summary
        updatePriceSummary();

        // Listen for manual input changes - include individuals for individual pricing
        const fields = ['adults', 'youth', 'children', 'infants', 'seniors', 'individuals'];
        fields.forEach(function(field) {
            const input = document.getElementById(field);
            if (input) {
                input.addEventListener('change', updatePriceSummary);
                input.addEventListener('input', updatePriceSummary);
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize intl-tel-input for phone field
        const phoneInput = document.querySelector("#customer_phone");
        if (phoneInput) {
            const iti = window.intlTelInput(phoneInput, {
                loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.13.2/build/js/utils.js"),
                initialCountry: "ke", // Default to Kenya
                preferredCountries: ["ke", "ug", "tz", "rw", "bi"], // East African countries
                separateDialCode: true,
                nationalMode: false,
                formatOnDisplay: true,
                autoPlaceholder: "aggressive"
            });

            // Format the number on blur and form submission
            phoneInput.addEventListener('blur', function() {
                if (iti.isValidNumber()) {
                    const formattedNumber = iti.getNumber();
                    phoneInput.value = formattedNumber;
                }
            });

            // Validate on form submission
            const form = document.getElementById('booking-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (iti && !iti.isValidNumber()) {
                        e.preventDefault();
                        // Show error message
                        let errorMsg = document.getElementById('phone-error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('p');
                            errorMsg.id = 'phone-error';
                            errorMsg.className = 'text-xs text-red-600 mt-1';
                            phoneInput.parentNode.appendChild(errorMsg);
                        }
                        errorMsg.textContent = 'Please enter a valid phone number';
                        phoneInput.focus();
                        return false;
                    } else if (iti) {
                        // Ensure the input has the full international format
                        const formattedNumber = iti.getNumber();
                        phoneInput.value = formattedNumber;
                    }
                });
            }
        }
    });
</script>
@endpush