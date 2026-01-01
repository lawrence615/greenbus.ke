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

@section('title', 'Buy tickets – ' . $tour->title)

@section('breadcrumb')
<x-breadcrumb :items="[
        ['label' => $location->name . ' tours', 'url' => route('tours.index', $location)],
        ['label' => $tour->title, 'url' => route('tours.show', [$location, $tour])],
        ['label' => 'Buy tickets'],
    ]" />
@endsection

@section('content')
<section class="max-w-6xl mx-auto px-4 pb-10 grid gap-10 lg:grid-cols-3">
    <div class="lg:col-span-2">
        {{-- Page header --}}
        <div class="mb-6">
            <p class="text-xs uppercase tracking-wide text-emerald-700 mb-1">{{ $location->name }} location tour</p>
            @if ($tour->category)
            <p class="text-[11px] text-slate-600 mb-1">{{ $tour->category->name }}</p>
            @endif
            <h1 class="text-2xl md:text-3xl font-semibold mb-2">Complete your booking</h1>
            <p class="text-sm text-slate-600 mb-4">You're just a few steps away from an amazing experience</p>
        </div>

        {{-- Tour Details Form --}}
        <form id="booking-form" method="POST" action="{{ route('bookings.store', [$location, $tour]) }}" class="space-y-5">
            @csrf

            {{-- Section 1: Tour Details --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-linear-to-r from-emerald-50 to-teal-50 px-5 py-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold">1</span>
                        <h2 class="font-semibold text-slate-800">Plan Your Tour</h2>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="date" class="block text-xs font-medium text-slate-600 mb-1.5">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Tour date
                                </span>
                            </label>
                            <input type="date" id="date" name="date" value="{{ old('date') }}" min="{{ now()->toDateString() }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition cursor-pointer">
                            @error('date')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="time" class="block text-xs font-medium text-slate-600 mb-1.5">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Start time
                                </span>
                            </label>
                            <input type="time" id="time" name="time" value="{{ old('time', $tour->starts_at_time) }}" readonly class="w-full rounded-lg border border-slate-200 bg-slate-100 text-sm px-3 py-2.5 text-slate-500 cursor-not-allowed">
                            @error('time')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Travellers --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-linear-to-r from-emerald-50 to-teal-50 px-5 py-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold">2</span>
                        <h2 class="font-semibold text-slate-800">Number of travellers</h2>
                    </div>
                </div>
                <div class="p-5">
                    {{-- Traveller Selection Dropdown --}}
                    <div class="space-y-4">
                        {{-- Adults (always shown) --}}
                        <div class="bg-slate-50 rounded-lg p-4 border border-slate-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-800">Adults (18-63)</p>
                                    <p class="text-xs text-slate-500">Required minimum 1</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="updateTraveller('adults', -1, 1)" class="w-9 h-9 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <input type="number" min="1" id="adults" name="adults" value="{{ old('adults', 1) }}" class="w-12 border-0 bg-transparent text-center text-lg font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    <button type="button" onclick="updateTraveller('adults', 1, 1)" class="w-9 h-9 rounded-md bg-emerald-600 text-white hover:bg-emerald-700 transition flex items-center justify-center cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('adults')
                            <p class="text-xs text-red-600 mt-2 text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Additional Travellers Dropdown --}}
                        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                            <button type="button" onclick="toggleTravellerDropdown()" class="w-full px-4 py-3 flex items-center justify-between hover:bg-slate-50 transition cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-slate-700">Add more travellers</span>
                                </div>
                                <svg id="dropdown-arrow" class="w-4 h-4 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div id="traveller-dropdown" class="hidden border-t border-slate-100">
                                <div class="p-4 space-y-4">
                                    {{-- Seniors --}}
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Seniors (63-90)</p>
                                            <p class="text-xs text-emerald-600 font-medium">USD {{ number_format($tour->base_price_senior ?? $tour->base_price_adult) }} each</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" onclick="updateTraveller('seniors', -1, 0)" class="w-8 h-8 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" min="0" id="seniors" name="seniors" value="{{ old('seniors', 0) }}" class="w-10 border-0 bg-transparent text-center text-base font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            <button type="button" onclick="updateTraveller('seniors', 1, 0)" class="w-8 h-8 rounded-md bg-emerald-600 text-white hover:bg-emerald-700 transition flex items-center justify-center cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @error('seniors')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror

                                    {{-- Youth --}}
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Youth (13-17)</p>
                                            <p class="text-xs text-emerald-600 font-medium">USD {{ number_format($tour->base_price_youth ?? $tour->base_price_child) }} each</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" onclick="updateTraveller('youth', -1, 0)" class="w-8 h-8 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" min="0" id="youth" name="youth" value="{{ old('youth', 0) }}" class="w-10 border-0 bg-transparent text-center text-base font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            <button type="button" onclick="updateTraveller('youth', 1, 0)" class="w-8 h-8 rounded-md bg-emerald-600 text-white hover:bg-emerald-700 transition flex items-center justify-center cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @error('youth')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror

                                    {{-- Children --}}
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Children (5-12)</p>
                                            <p class="text-xs text-emerald-600 font-medium">USD {{ number_format($tour->base_price_child) }} each</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" onclick="updateTraveller('children', -1, 0)" class="w-8 h-8 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" min="0" id="children" name="children" value="{{ old('children', 0) }}" class="w-10 border-0 bg-transparent text-center text-base font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            <button type="button" onclick="updateTraveller('children', 1, 0)" class="w-8 h-8 rounded-md bg-emerald-600 text-white hover:bg-emerald-700 transition flex items-center justify-center cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @error('children')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror

                                    {{-- Infants --}}
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">Infants (0-4)</p>
                                            <p class="text-xs text-emerald-600 font-medium">{{ $tour->base_price_infant > 0 ? 'USD ' . number_format($tour->base_price_infant) . ' each' : 'Free' }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" onclick="updateTraveller('infants', -1, 0)" class="w-8 h-8 rounded-md bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition flex items-center justify-center cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" min="0" id="infants" name="infants" value="{{ old('infants', 0) }}" class="w-10 border-0 bg-transparent text-center text-base font-semibold text-slate-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            <button type="button" onclick="updateTraveller('infants', 1, 0)" class="w-8 h-8 rounded-md bg-emerald-600 text-white hover:bg-emerald-700 transition flex items-center justify-center cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @error('infants')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Contact Details --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-linear-to-r from-emerald-50 to-teal-50 px-5 py-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold">3</span>
                        <h2 class="font-semibold text-slate-800">Your details</h2>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="customer_name" class="block text-xs font-medium text-slate-600 mb-1.5">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Full name
                                </span>
                            </label>
                            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition" placeholder="Alex Johnson">
                            @error('customer_name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="customer_email" class="block text-xs font-medium text-slate-600 mb-1.5">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Email address
                                </span>
                            </label>
                            <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition" placeholder="alex@example.com">
                            @error('customer_email')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="customer_phone" class="block text-xs font-medium text-slate-600 mb-1.5">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    Phone / WhatsApp
                                </span>
                            </label>
                            <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition" placeholder="+254 700 000 000">
                            @error('customer_phone')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="country_of_origin" class="block text-xs font-medium text-slate-600 mb-1.5">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Country of origin <span class="text-slate-400 font-normal">(optional)</span>
                                </span>
                            </label>
                            <input type="text" id="country_of_origin" name="country_of_origin" value="{{ old('country_of_origin') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition" placeholder="e.g. USA, UK, Canada">
                            @error('country_of_origin')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="special_requests" class="block text-xs font-medium text-slate-600 mb-1.5">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                Special requests <span class="text-slate-400 font-normal">(optional)</span>
                            </span>
                        </label>
                        <textarea id="special_requests" name="special_requests" rows="4" class="w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition resize-none" placeholder="Any dietary requirements, accessibility needs, or other requests...">{{ old('special_requests') }}</textarea>
                        @error('special_requests')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Submit section (mobile) --}}
            <div class="lg:hidden bg-white rounded-xl border border-slate-200 p-5">
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition cursor-pointer shadow-lg shadow-emerald-600/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Continue to Payment
                    </button>
                    <a href="{{ route('tours.show', [$location, $tour]) }}" class="text-center text-sm text-slate-500 hover:text-slate-700">
                        Cancel and go back
                    </a>
                </div>
                <p class="mt-4 text-xs text-slate-500 text-center">
                    By continuing, you agree to our
                    <a href="{{ url('/terms') }}" target="_blank" rel="noopener" class="text-emerald-600 hover:underline">booking terms</a>.
                </p>
            </div>
        </form>
    </div>

    {{-- Sidebar: Tour summary --}}
    <div class="lg:col-span-1">
        <div class="sticky top-24 space-y-4">
            {{-- Tour card --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                @php
                $cover = $tour->images->firstWhere('is_cover', true) ?? $tour->images->first();
                @endphp
                @if($cover)
                <img src="{{ $cover->url }}" alt="{{ $tour->title }}" class="w-full h-50 object-cover">
                @else
                <div class="w-full h-32 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-slate-900 leading-tight">{{ $tour->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $location->name }}</p>
                    <div class="flex items-center gap-3 mt-3 text-xs text-slate-600">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $tour->duration }}
                        </span>
                        @if($tour->starts_at_time)
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($tour->starts_at_time)->format('g:i A') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Price summary --}}
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <h4 class="text-sm font-semibold text-slate-800 mb-3">Price breakdown</h4>
                <div class="space-y-2 text-sm" id="price-breakdown">
                    <div class="flex justify-between text-slate-600">
                        <span>Adults (18-63) (<span id="summary-adults">1</span> x USD {{ number_format($tour->base_price_adult) }})</span>
                        <span id="total-adults">USD {{ number_format($tour->base_price_adult) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600" id="seniors-row" style="display: none;">
                        <span>Seniors (63-90) (<span id="summary-seniors">0</span> x USD {{ number_format($tour->base_price_senior ?? $tour->base_price_adult) }})</span>
                        <span id="total-seniors">USD 0</span>
                    </div>
                    <div class="flex justify-between text-slate-600" id="youth-row" style="display: none;">
                        <span>Youth (13-17) (<span id="summary-youth">0</span> x USD {{ number_format($tour->base_price_youth ?? $tour->base_price_child) }})</span>
                        <span id="total-youth">USD 0</span>
                    </div>
                    <div class="flex justify-between text-slate-600" id="children-row" style="display: none;">
                        <span>Children (5-12) (<span id="summary-children">0</span> x USD {{ number_format($tour->base_price_child) }})</span>
                        <span id="total-children">USD 0</span>
                    </div>
                    <div class="flex justify-between text-slate-600" id="infants-row" style="display: none;">
                        <span>Infants (0-4) (<span id="summary-infants">0</span>@if($tour->base_price_infant > 0) x USD {{ number_format($tour->base_price_infant) }}@endif)</span>
                        <span id="total-infants" class="text-emerald-600">{{ $tour->base_price_infant > 0 ? 'USD 0' : 'Free' }}</span>
                    </div>
                </div>
                <div class="border-t border-slate-100 mt-3 pt-3">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-slate-900">Total</span>
                        <span class="text-lg font-bold text-emerald-600" id="grand-total">USD {{ number_format($tour->price_adult) }}</span>
                    </div>
                </div>
            </div>

            {{-- Desktop submit --}}
            <div class="hidden lg:block space-y-3">
                <button type="submit" form="booking-form" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition cursor-pointer shadow-lg shadow-emerald-600/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Continue to Payment
                </button>
                <a href="{{ route('tours.show', [$location, $tour]) }}" class="block text-center text-sm text-slate-500 hover:text-slate-700">
                    Cancel and go back
                </a>
                <p class="text-xs text-slate-500 text-center">
                    By continuing, you agree to our
                    <a href="{{ url('/terms') }}" target="_blank" rel="noopener" class="text-emerald-600 hover:underline">booking terms</a>.
                </p>
            </div>

            {{-- Trust badges --}}
            <div class="flex items-center justify-center gap-4 text-xs text-slate-400 pt-2">
                <span class="inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Secure
                </span>
                <span class="inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Instant confirm
                </span>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.13.2/build/js/intlTelInput.min.js"></script>
<script>
    // Pricing constants - eslint-disable no-undef
    /* eslint-disable */
    const PRICE_ADULT = {{ $tour->base_price_adult ?? 0 }};
    const PRICE_CHILD = {{ $tour->base_price_child ?? 0 }};
    const PRICE_INFANT = {{ $tour->base_price_infant ?? 0 }};
    const PRICE_SENIOR = {{ $tour->base_price_senior ?? $tour->base_price_adult ?? 0 }};
    const PRICE_YOUTH = {{ $tour->base_price_youth ?? $tour->base_price_child ?? 0 }};
    /* eslint-enable */

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function updatePriceSummary() {
        const adults = parseInt(document.getElementById('adults').value || '1', 10);
        const children = parseInt(document.getElementById('children').value || '0', 10);
        const infants = parseInt(document.getElementById('infants').value || '0', 10);
        const seniors = parseInt(document.getElementById('seniors').value || '0', 10);
        const youth = parseInt(document.getElementById('youth').value || '0', 10);

        // Update summary counts
        document.getElementById('summary-adults').textContent = adults;
        document.getElementById('summary-children').textContent = children;
        document.getElementById('summary-infants').textContent = infants;
        document.getElementById('summary-seniors').textContent = seniors;
        document.getElementById('summary-youth').textContent = youth;

        // Calculate totals
        const totalAdults = adults * PRICE_ADULT;
        const totalChildren = children * PRICE_CHILD;
        const totalInfants = infants * PRICE_INFANT;
        const totalSeniors = seniors * PRICE_SENIOR;
        const totalYouth = youth * PRICE_YOUTH;
        const grandTotal = totalAdults + totalChildren + totalInfants + totalSeniors + totalYouth;

        // Update displayed totals
        document.getElementById('total-adults').textContent = 'USD ' + formatNumber(totalAdults);
        document.getElementById('total-children').textContent = 'USD ' + formatNumber(totalChildren);
        document.getElementById('total-infants').textContent = PRICE_INFANT > 0 ? 'USD ' + formatNumber(totalInfants) : 'Free';
        document.getElementById('total-seniors').textContent = 'USD ' + formatNumber(totalSeniors);
        document.getElementById('total-youth').textContent = 'USD ' + formatNumber(totalYouth);
        document.getElementById('grand-total').textContent = 'USD ' + formatNumber(grandTotal);

        // Show/hide rows
        document.getElementById('children-row').style.display = children > 0 ? 'flex' : 'none';
        document.getElementById('infants-row').style.display = infants > 0 ? 'flex' : 'none';
        document.getElementById('seniors-row').style.display = seniors > 0 ? 'flex' : 'none';
        document.getElementById('youth-row').style.display = youth > 0 ? 'flex' : 'none';
    }

    function updateTraveller(field, delta, min) {
        const input = document.getElementById(field);
        if (!input) return;
        const current = parseInt(input.value || '0', 10);
        const next = Math.max(min, current + delta);
        input.value = next;
        updatePriceSummary();
    }

    function toggleTravellerDropdown() {
        const dropdown = document.getElementById('traveller-dropdown');
        const arrow = document.getElementById('dropdown-arrow');

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Make date input fully clickable
        const dateInput = document.getElementById('date');
        if (dateInput) {
            dateInput.addEventListener('click', function() {
                this.showPicker();
            });
        }

        // Initialize price summary
        updatePriceSummary();

        // Listen for manual input changes
        ['adults', 'children', 'infants', 'seniors', 'youth'].forEach(function(field) {
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

        // Form submit loading state
        const form = document.getElementById('booking-form');
        if (!form) return;

        const submitButtons = document.querySelectorAll('button[type="submit"]');

        form.addEventListener('submit', function() {
            submitButtons.forEach(function(btn) {
                if (btn.disabled) return;
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                const originalHTML = btn.innerHTML;
                btn.dataset.originalHtml = originalHTML;
                btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
            });
        });
    });
</script>
@endpush