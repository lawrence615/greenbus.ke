@extends('layouts.app')

@section('title', $tour->title . ' – ' . $city->name)

@section('content')
<section class="max-w-6xl mx-auto px-4 py-10 grid gap-10 lg:grid-cols-3">
    <div class="lg:col-span-3 mb-3">
        <nav class="text-xs text-slate-500" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-1">
                <li><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
                <li>/</li>
                <li><a href="{{ route('tours.index', $city) }}" class="hover:underline">{{ $city->name }} tours</a></li>
                <li>/</li>
                <li><span class="text-slate-600">{{ $tour->title }}</span></li>
            </ol>
        </nav>
    </div>
    <div class="lg:col-span-2">
        <p class="text-xs uppercase tracking-wide text-emerald-700 mb-1">{{ $city->name }} city tour</p>
        @if ($tour->category)
        <p class="text-[11px] text-slate-600 mb-1">{{ $tour->category->name }}</p>
        @endif
        <h1 class="text-2xl md:text-3xl font-semibold mb-2">{{ $tour->title }}</h1>
        <p class="text-sm text-slate-600 mb-4">{{ $tour->short_description }}</p>

        <div class="mb-6 grid gap-3 text-xs text-slate-700 sm:grid-cols-3">
            @if ($tour->duration_text)
            <x-tour-info-card
                label="Duration"
                :value="$tour->duration_text"
                subtitle="Approx."
                iconBgClass="bg-emerald-50 text-emerald-700"
                borderClass="border-emerald-50/70 hover:border-emerald-100"
            >
                <x-slot:icon>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.5" />
                        <path d="M12 8V12L14.5 13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </x-slot:icon>
            </x-tour-info-card>
            @endif

            @if ($tour->starts_at_time)
            <x-tour-info-card
                label="Departure time"
                :value="$tour->starts_at_time"
                subtitle="Local time"
                iconBgClass="bg-sky-50 text-sky-700"
                borderClass="border-slate-100 hover:border-sky-100"
            >
                <x-slot:icon>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 4.75V6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M16 4.75V6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <rect x="5" y="6.5" width="14" height="12" rx="2" stroke="currentColor" stroke-width="1.5" />
                        <path d="M9 12H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </x-slot:icon>
            </x-tour-info-card>
            @endif

            @if ($tour->meeting_point)
            <x-tour-info-card
                label="Meeting point"
                :value="$tour->meeting_point"
                subtitle="Pickup / start location"
                iconBgClass="bg-amber-50 text-amber-700"
                borderClass="border-slate-100 hover:border-amber-100"
            >
                <x-slot:icon>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3.75C9.37665 3.75 7.25 5.87665 7.25 8.5C7.25 11.1234 9.37665 13.25 12 13.25C14.6234 13.25 16.75 11.1234 16.75 8.5C16.75 5.87665 14.6234 3.75 12 3.75Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M11.9998 21.0001C10.1665 21.0001 8.69484 19.8821 7.59887 18.5421C6.58506 17.3025 5.875 15.843 5.5127 14.8575C5.40989 14.5743 5.52328 14.2598 5.78666 14.1146C7.34943 13.2524 9.11532 12.75 11.9998 12.75C14.8844 12.75 16.6503 13.2524 18.213 14.1146C18.4764 14.2598 18.5898 14.5743 18.487 14.8575C18.1247 15.843 17.4146 17.3025 16.4008 18.5421C15.3049 19.8821 13.8331 21.0001 11.9998 21.0001Z" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                </x-slot:icon>
            </x-tour-info-card>
            @endif
        </div>

        <div class="aspect-video rounded-2xl overflow-hidden bg-slate-200 mb-6">
            @php
            $cover = $tour->images->firstWhere('is_cover', true) ?? $tour->images->first();
            @endphp
            @if ($cover)
            <img src="{{ $cover->path }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
            @else
            <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">Tour photos coming soon</div>
            @endif
        </div>

        <div class="space-y-6 text-sm text-slate-700">
            <section>
                <h2 class="text-base font-semibold mb-2">Overview</h2>
                <div class="prose prose-sm max-w-none">
                    {!! nl2br(e($tour->description)) !!}
                </div>
            </section>

            <section>
                <h2 class="text-base font-semibold mb-2">What the package covers</h2>
                @if ($tour->includes)
                <div class="prose prose-sm max-w-none">
                    {!! nl2br(e($tour->includes)) !!}
                </div>
                @else
                <ul class="list-disc pl-5 space-y-1 text-xs sm:text-sm">
                    <li>Guided city tour with a local expert.</li>
                    <li>Transport as described in the tour information.</li>
                    <li>Entrance fees where specifically mentioned in your ticket or confirmation email.</li>
                    <li>Small-group experience suitable for visitors to Nairobi.</li>
                </ul>
                @endif
            </section>

            <section>
                <h2 class="text-base font-semibold mb-2">Important information</h2>
                @if ($tour->important_information)
                <div class="prose prose-sm max-w-none">
                    {!! nl2br(e($tour->important_information)) !!}
                </div>
                @else
                <ul class="list-disc pl-5 space-y-1 text-xs sm:text-sm">
                    @if ($tour->meeting_point)
                    <li><span class="font-semibold">Meeting point:</span> {{ $tour->meeting_point }}</li>
                    @endif
                    @if ($tour->starts_at_time)
                    <li><span class="font-semibold">Departure time:</span> {{ $tour->starts_at_time }} (please arrive 10–15 minutes early).</li>
                    @endif
                    @if ($tour->duration_text)
                    <li><span class="font-semibold">Duration:</span> {{ $tour->duration_text }} (times may vary slightly with traffic).</li>
                    @endif
                    <li>Please bring a Passport/valid ID and your digital ticket (PDF on your phone is fine).</li>
                    <li>Wear comfortable shoes and weather-appropriate clothing.</li>
                </ul>
                @endif
            </section>

            <section class="border border-slate-100 rounded-xl bg-white/60 p-4">
                <h2 class="text-base font-semibold mb-3">About this activity</h2>
                <div class="grid gap-3 sm:grid-cols-2 text-xs sm:text-sm text-slate-700">
                    @if ($tour->duration_text)
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-900">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.75" />
                                    <path d="M12 8V12L14.5 13.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <p class="font-semibold">Duration</p>
                        </div>
                        <p class="mt-0.5 ml-9">{{ $tour->duration_text }}</p>
                    </div>
                    @endif

                    @if ($tour->starts_at_time)
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-900">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 4.75V6.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                                    <path d="M16 4.75V6.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                                    <rect x="5" y="6.5" width="14" height="12" rx="2" stroke="currentColor" stroke-width="1.75" />
                                </svg>
                            </span>
                            <p class="font-semibold">Starting time</p>
                        </div>
                        <p class="mt-0.5 ml-9">{{ $tour->starts_at_time }}</p>
                    </div>
                    @endif

                    @if ($tour->meeting_point)
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-900">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 3.75C9.376 3.75 7.5 5.626 7.5 7.875C7.5 10.836 10.5 13.25 12 15.75C13.5 13.25 16.5 10.836 16.5 7.875C16.5 5.626 14.624 3.75 12 3.75Z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="12" cy="8" r="1.75" stroke="currentColor" stroke-width="1.75" />
                                </svg>
                            </span>
                            <p class="font-semibold">Meeting point</p>
                        </div>
                        <p class="mt-0.5 ml-9">{{ $tour->meeting_point }}</p>
                    </div>
                    @endif

                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-900">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 6.75C10.7574 6.75 9.75 7.75736 9.75 9C9.75 10.2426 10.7574 11.25 12 11.25C13.2426 11.25 14.25 10.2426 14.25 9C14.25 7.75736 13.2426 6.75 12 6.75Z" stroke="currentColor" stroke-width="1.75" />
                                    <path d="M8.75 16.25C8.75 14.8693 9.86929 13.75 11.25 13.75H12.75C14.1307 13.75 15.25 14.8693 15.25 16.25" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                                </svg>
                            </span>
                            <p class="font-semibold">Live tour guide</p>
                        </div>
                        <p class="mt-0.5 ml-9">English-speaking local guide in {{ $city->name }}.</p>
                    </div>

                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-900">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="6.5" y="7" width="11" height="10" rx="1.5" stroke="currentColor" stroke-width="1.75" />
                                    <path d="M9 9H15" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                                </svg>
                            </span>
                            <p class="font-semibold">Ticket type</p>
                        </div>
                        <p class="mt-0.5 ml-9">Mobile or printed ticket accepted. PDF is sent after booking.</p>
                    </div>

                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-900">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="9" cy="9" r="2" stroke="currentColor" stroke-width="1.75" />
                                    <circle cx="15" cy="9" r="2" stroke="currentColor" stroke-width="1.75" />
                                    <path d="M6.75 15.25C6.75 13.8693 7.86929 12.75 9.25 12.75H10.75C12.1307 12.75 13.25 13.8693 13.25 15.25" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
                                </svg>
                            </span>
                            <p class="font-semibold">Group type</p>
                        </div>
                        <p class="mt-0.5 ml-9">Small-group city tour suitable for first-time visitors.</p>
                    </div>
                </div>
            </section>

            <x-itinerary :items="$tour->itineraryItems" :city="$city" />
        </div>
    </div>

    <aside class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 sticky top-24">
            <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">From</p>
            <p class="text-2xl font-semibold text-emerald-700 mb-4">KES {{ number_format($tour->base_price_adult) }} <span class="text-xs font-normal text-slate-500">per adult</span></p>

            <ul class="text-xs text-slate-600 mb-4 space-y-1">
                <li>Guided city tour with local expert</li>
                <li>Comfortable bus transport</li>
                <li>Perfect for first-time visitors</li>
            </ul>

            <a href="{{ route('bookings.create', [$city, $tour]) }}" class="inline-flex w-full items-center justify-center px-4 py-3 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 mb-3">
                Buy tickets
            </a>

            <p class="text-[11px] text-slate-500">
                You’ll see the full price before payment. Secure card payments powered by a local provider.
            </p>
        </div>
    </aside>
</section>
@endsection