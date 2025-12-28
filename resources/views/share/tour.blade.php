@extends('layouts.app')

@section('page-title', 'Custom Tour - ' . $tour->title)

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Hero Section -->
    @if($tour->images->isNotEmpty())
        <div class="relative h-96 bg-slate-900">
            <img src="{{ $tour->images->first()->url }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white">
                    <h1 class="text-4xl font-bold mb-2">{{ $tour->title }}</h1>
                    <p class="text-xl opacity-90">{{ $tour->location->name }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-linear-to-r from-blue-600 to-purple-600 py-24">
            <div class="max-w-4xl mx-auto px-4 text-center text-white">
                <h1 class="text-4xl font-bold mb-2">{{ $tour->title }}</h1>
                <p class="text-xl opacity-90">{{ $tour->location->name }}</p>
            </div>
        </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Tour Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="font-semibold text-slate-900">Duration</h3>
                </div>
                <p class="text-slate-600">{{ $tour->duration_text ?? 'Flexible' }}</p>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="font-semibold text-slate-900">Meeting Point</h3>
                </div>
                <p class="text-slate-600">{{ $tour->meeting_point ?? 'To be arranged' }}</p>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <h3 class="font-semibold text-slate-900">Group Size</h3>
                </div>
                <p class="text-slate-600">Up to {{ $tour->no_of_people }} people</p>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-lg p-8 shadow-sm mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">About This Tour</h2>
            <div class="prose prose-slate max-w-none">
                {!! $tour->description !!}
            </div>
        </div>

        <!-- Itinerary -->
        @if($tour->itineraryItems->isNotEmpty())
            <div class="bg-white rounded-lg p-8 shadow-sm mb-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Itinerary</h2>
                <div class="space-y-6">
                    @foreach($tour->itineraryItems->sortBy('order') as $item)
                        <div class="flex gap-4">
                            <div class="shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-semibold text-blue-600">{{ $loop->iteration }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900 mb-1">{{ $item->title }}</h3>
                                <p class="text-slate-600">{{ $item->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Pricing -->
        @if($tour->pricings->isNotEmpty())
            <div class="bg-white rounded-lg p-8 shadow-sm mb-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Pricing</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($tour->pricings as $pricing)
                        <div class="border border-slate-200 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-slate-900">{{ ucfirst($pricing->person_type) }}</span>
                                <div class="text-right">
                                    @if(($pricing->discounted_price ?? 0) > 0 && ($pricing->discounted_price ?? 0) < ($pricing->price ?? 0))
                                        <span class="text-2xl font-bold text-green-600">${{ number_format($pricing->discounted_price, 2) }}</span>
                                        <span class="text-sm text-slate-500 line-through block">${{ number_format($pricing->price, 2) }}</span>
                                    @else
                                        <span class="text-2xl font-bold text-slate-900">${{ number_format($pricing->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Included & Excluded -->
        @if($tour->included || $tour->excluded)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                @if($tour->included)
                    <div class="bg-white rounded-lg p-8 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">What's Included</h2>
                        <div class="prose prose-slate max-w-none">
                            {!! nl2br(e($tour->included)) !!}
                        </div>
                    </div>
                @endif

                @if($tour->excluded)
                    <div class="bg-white rounded-lg p-8 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">What's Not Included</h2>
                        <div class="prose prose-slate max-w-none">
                            {!! nl2br(e($tour->excluded)) !!}
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- CTA Section -->
        <div class="bg-linear-to-r from-blue-600 to-purple-600 rounded-lg p-8 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Ready to Book This Custom Tour?</h2>
            <p class="text-xl mb-6 opacity-90">Secure your spot with just a few clicks</p>
            <a href="{{ route('share.book', $tour->share_token) }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-slate-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Book Now
            </a>
        </div>
    </div>
</div>
@endsection
