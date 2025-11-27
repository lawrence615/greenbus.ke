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
            <div class="bg-white rounded-xl border border-slate-100 p-3">
                <p class="font-semibold mb-1">Duration</p>
                <p>{{ $tour->duration_text }}</p>
            </div>
            @endif
            @if ($tour->starts_at_time)
            <div class="bg-white rounded-xl border border-slate-100 p-3">
                <p class="font-semibold mb-1">Departure time</p>
                <p>{{ $tour->starts_at_time }}</p>
            </div>
            @endif
            @if ($tour->meeting_point)
            <div class="bg-white rounded-xl border border-slate-100 p-3">
                <p class="font-semibold mb-1">Meeting point</p>
                <p>{{ $tour->meeting_point }}</p>
            </div>
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
                        <p class="font-semibold mb-0.5">Duration</p>
                        <p>{{ $tour->duration_text }}</p>
                    </div>
                    @endif
                    @if ($tour->starts_at_time)
                    <div>
                        <p class="font-semibold mb-0.5">Starting time</p>
                        <p>{{ $tour->starts_at_time }}</p>
                    </div>
                    @endif
                    @if ($tour->meeting_point)
                    <div>
                        <p class="font-semibold mb-0.5">Meeting point</p>
                        <p>{{ $tour->meeting_point }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="font-semibold mb-0.5">Live tour guide</p>
                        <p>English-speaking local guide in {{ $city->name }}.</p>
                    </div>
                    <div>
                        <p class="font-semibold mb-0.5">Ticket type</p>
                        <p>Mobile or printed ticket accepted. PDF is sent after booking.</p>
                    </div>
                    <div>
                        <p class="font-semibold mb-0.5">Group type</p>
                        <p>Small-group city tour suitable for first-time visitors.</p>
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