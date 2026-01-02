@extends('layouts.admin')

@section('title', 'Tours')
@section('page-title', 'Tours')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div x-data="{ 
    showConfirmModal: false,
    selectedTour: null,
    selectedAction: '',
    showTourDropdown: false,
    isBusTourFilterActive: {{ request('is_the_bus_tour') ? 'true' : 'false' }},
    toggleBusTourFilter: function() {
        this.isBusTourFilterActive = !this.isBusTourFilterActive;
        const url = new URL(window.location);
        if (this.isBusTourFilterActive) {
            url.searchParams.set('is_the_bus_tour', '1');
        } else {
            url.searchParams.delete('is_the_bus_tour');
        }
        window.location.href = url.toString();
    },
    openModal: function(tour, action) {
        this.selectedTour = tour;
        this.selectedAction = action;
        this.showConfirmModal = true;
    },
    closeModal: function() {
        this.showConfirmModal = false;
        this.selectedTour = null;
        this.selectedAction = '';
    }
}" class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-500">Manage your tour listings</p>
        </div>
        <!-- Tour Type Dropdown -->
        <div class="relative" x-data="{ isOpen: false }">
            <button 
                @click="isOpen = !isOpen"
                @click.away="isOpen = false"
                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Tour
                <svg class="w-4 h-4 transition-transform" :class="isOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="isOpen" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-slate-200 z-10">
                <div class="py-1">
                    <a href="{{ route('console.tours.standard.create') }}" 
                       class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 hover:text-slate-900">
                        <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div>
                            <div class="font-medium">Standard Tour</div>
                            <div class="text-slate-500">Pre-defined tour packages</div>
                        </div>
                    </a>
                    <a href="{{ route('console.tours.bespoke.create') }}" 
                       class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 hover:text-slate-900">
                        <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        <div>
                            <div class="font-medium">Bespoke Tour</div>
                            <div class="text-slate-500">Custom, personalized experiences</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Filters -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-500 rounded-full shadow-sm">
                    🚌
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Quick Filters</h2>
                    <p class="text-sm text-slate-600">Instant access to special tours</p>
                </div>
            </div>
            <button 
                type="button"
                @click="toggleBusTourFilter"
                :class="isBusTourFilterActive ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white border-amber-500 shadow-md' : 'bg-white text-amber-700 border-amber-200 hover:bg-amber-50'"
                class="inline-flex items-center gap-2 px-4 py-2.5 border rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>
                <span x-text="isBusTourFilterActive ? 'Showing Bus Tour Only' : '🚌 The Bus Tour'" class="font-medium"></span>
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form method="GET" action="{{ route('console.tours.index') }}" class="flex flex-col lg:flex-row lg:items-end gap-4">
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
                        placeholder="Tour title..."
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                </div>

                <!-- Location -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Location
                    </label>
                    <select name="location_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer">
                        <option value="">All Locations</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Category
                    </label>
                    <select name="category_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
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
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filter
                </button>
                
                @if(request()->hasAny(['search', 'location_id', 'category_id', 'status', 'is_the_bus_tour']))
                <a href="{{ route('console.tours.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 border border-slate-300 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bus Tour Filter Indicator -->
    @if(request('is_the_bus_tour'))
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-8 h-8 bg-amber-100 rounded-full">
                🚌
            </div>
            <div>
                <h3 class="font-medium text-amber-900">Showing "The Bus Tour" Only</h3>
                <p class="text-sm text-amber-700">Displaying the tour marked as the official bus tour experience</p>
            </div>
            <a href="{{ route('console.tours.index') }}" class="ml-auto text-sm text-amber-600 hover:text-amber-800 font-medium">
                Clear Filter →
            </a>
        </div>
    </div>
    @endif

    <!-- Tours Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-b from-slate-50 to-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tour
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Location
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.519 4.674c.3.921-.755 1.688-1.539 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.539-1.118l1.519-4.674a1 1 0 00-.363-1.118L2.98 10.101c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                Tour Type
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left hidden xl:table-cell">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Price
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
                    @forelse($tours as $tour)
                    <tr class="hover:bg-emerald-50/50 transition-colors duration-150 group {{ $tour->is_the_bus_tour ? 'bg-gradient-to-r from-amber-50/80 to-orange-50/80 border-l-4 border-amber-400' : '' }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @php
                                $cover = $tour->images->firstWhere('is_cover', true) ?? $tour->images->first();
                                @endphp
                                <div class="shrink-0 hidden sm:block">
                                    @if($tour->is_the_bus_tour)
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center ring-2 ring-amber-300 shadow-sm">
                                        <span class="text-white text-lg">🚌</span>
                                    </div>
                                    @elseif($cover)
                                    <img src="{{ $cover->url }}" alt="{{ $tour->title }}" class="w-12 h-12 rounded-lg object-cover shadow-sm ring-1 ring-slate-200">
                                    @else
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center ring-1 ring-slate-200">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <!-- Tour Code Badge -->
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-medium {{ $tour->is_the_bus_tour ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} mb-1">
                                        {{ $tour->code }}
                                    </span>
                                    <!-- Clickable Tour Title -->
                                    <div class="flex items-center gap-2">
                                        <a href="{{ ($tour->tour_type ?? 'standard') === 'bespoke' ? route('console.tours.bespoke.show', $tour) : route('console.tours.standard.show', $tour) }}" class="block font-semibold {{ $tour->is_the_bus_tour ? 'text-amber-900 hover:text-amber-700' : 'text-slate-900 hover:text-emerald-600' }} transition-colors truncate max-w-[200px] lg:max-w-[350px]" title="{{ $tour->title }}">
                                            {{ $tour->title }}
                                        </a>
                                        @if($tour->is_the_bus_tour)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-medium rounded-full border border-amber-200">
                                            🚌 The Bus Tour
                                        </span>
                                        @endif
                                    </div>
                                    <!-- Mobile: Show location & price inline -->
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs {{ $tour->is_the_bus_tour ? 'text-amber-700' : 'text-slate-500' }}">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $tour->formatted_duration }}
                                        </span>
                                        <span class="lg:hidden flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            {{ $tour->location->name ?? 'N/A' }}
                                        </span>
                                        <span class="xl:hidden font-medium {{ $tour->is_the_bus_tour ? 'text-amber-700' : 'text-emerald-600' }}">
                                            @php
                                            $adultPricing = $tour->pricings->where('person_type', 'adult')->first();
                                            $price = $adultPricing ? $adultPricing->price : ($tour->base_price_adult ?? 0);
                                            @endphp
                                            USD {{ number_format($price) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <div class="flex flex-col gap-1">
                                <span class="font-medium {{ $tour->is_the_bus_tour ? 'text-amber-900' : 'text-slate-700' }}">{{ $tour->location->name ?? 'N/A' }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ $tour->is_the_bus_tour ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }} w-fit">
                                    {{ $tour->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold ring-1 ring-inset
                                {{ $tour->is_the_bus_tour 
                                    ? 'bg-amber-100 text-amber-800 ring-amber-200' 
                                    : ($tour->status === 'published' 
                                        ? 'bg-emerald-100 text-emerald-800 ring-emerald-200' 
                                        : 'bg-slate-100 text-slate-800 ring-slate-200') }}">
                                @if($tour->is_the_bus_tour)
                                🚌 Bus Tour
                                @else
                                {{ $tour->status === 'published' ? 'Published' : 'Draft' }}
                                @endif
                            </span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold {{ $tour->is_the_bus_tour ? 'text-amber-900' : 'text-emerald-600' }}">
                                    @php
                                    $adultPricing = $tour->pricings->where('person_type', 'adult')->first();
                                    $price = $adultPricing ? $adultPricing->price : ($tour->base_price_adult ?? 0);
                                    @endphp
                                    USD {{ number_format($price) }}
                                </span>
                                @if($tour->is_the_bus_tour)
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-medium rounded border border-amber-200">
                                    Special
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold ring-1 ring-inset
                                {{ $tour->is_the_bus_tour 
                                    ? 'bg-amber-100 text-amber-800 ring-amber-200' 
                                    : (($tour->tour_type ?? 'standard') === 'standard' 
                                        ? 'bg-blue-50 text-blue-700 ring-blue-600/20' 
                                        : (($tour->tour_type ?? 'standard') === 'bespoke' 
                                            ? 'bg-purple-50 text-purple-700 ring-purple-600/20' 
                                            : 'bg-slate-100 text-slate-700 ring-slate-600/20')) }}">
                                @if($tour->is_the_bus_tour)
                                🚌 Bus Tour
                                @else
                                {{ ucfirst($tour->tour_type ?? 'standard') }}
                                @endif
                            </span>
                        </td>
                        <td class="px-4 py-3 hidden xl:table-cell">
                            <div class="flex flex-col">
                                <span class="font-semibold {{ $tour->is_the_bus_tour ? 'text-amber-900' : 'text-slate-900' }}">
                                    @php
                                    $adultPricing = $tour->pricings->where('person_type', 'adult')->first();
                                    $price = $adultPricing ? $adultPricing->price : ($tour->base_price_adult ?? 0);
                                    @endphp
                                    USD {{ number_format($price) }}
                                </span>
                                <span class="text-[10px] {{ $tour->is_the_bus_tour ? 'text-amber-600' : 'text-slate-500' }}">per adult</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $tour->is_the_bus_tour 
                                        ? 'bg-amber-100 text-amber-700' 
                                        : ($tour->status === 'published' 
                                            ? 'bg-emerald-100 text-emerald-700' 
                                            : 'bg-slate-100 text-slate-600') }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $tour->is_the_bus_tour ? 'bg-amber-500' : ($tour->status === 'published' ? 'bg-emerald-500' : 'bg-slate-400') }}"></span>
                                    @if($tour->is_the_bus_tour)
                                    Special
                                    @else
                                    {{ ucfirst($tour->status) }}
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-0.5">
                                <a href="{{ ($tour->tour_type ?? 'standard') === 'bespoke' ? route('console.tours.bespoke.show', $tour) : route('console.tours.standard.show', $tour) }}" class="p-1.5 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors duration-150" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ ($tour->tour_type ?? 'standard') === 'bespoke' ? route('console.tours.bespoke.edit', $tour) : route('console.tours.standard.edit', $tour) }}" class="p-1.5 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors duration-150" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button
                                    type="button"
                                    @click="openModal({{ $tour->toJson() }}, '{{ $tour->status === 'published' ? 'draft' : 'publish' }}')"
                                    class="p-1.5 rounded-lg transition-colors duration-150 cursor-pointer {{ $tour->status === 'published' ? 'text-amber-600 hover:text-amber-700 hover:bg-amber-50' : 'text-blue-600 hover:text-blue-700 hover:bg-blue-50' }}"
                                    title="{{ $tour->status === 'published' ? 'draft' : 'Publish' }}">
                                    @if($tour->status === 'published')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    @endif
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium">No tours found</p>
                                <p class="text-slate-400 text-sm mt-1">Try adjusting your filters or create a new tour</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tours->hasPages())
        <div class="px-4 py-4 border-t border-slate-200">
            {{ $tours->links() }}
        </div>
        @endif
    </div>

    <!-- Single Shared Confirmation Modal -->
    <div
        x-show="showConfirmModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        @keydown.escape.window="closeModal()">
        <div class="fixed inset-0 bg-black/50" @click="closeModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl p-6 max-w-sm mx-4 z-10">
            <h3 class="text-lg text-center font-semibold text-slate-900 mb-2">
                <span x-text="selectedAction === 'draft' ? 'Unpublish Tour?' : 'Publish Tour?'"></span>
            </h3>
            <p class="text-sm text-slate-600 mb-4">
                <span x-show="selectedAction === 'draft'">This tour will no longer be visible to the public.</span>
                <span x-show="selectedAction === 'publish'">This tour will become visible to the public.</span>
            </p>
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="closeModal()"
                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 cursor-pointer">
                    Cancel
                </button>
                <form method="POST" :action="`/console/tours/${selectedTour.slug}/toggle-status`" 
      @submit="$event.preventDefault(); window.showLoading('Toggling tour status...', 'Updating Tour'); $el.submit();">
                    @csrf
                    @method('PATCH')
                    <button 
                        type="submit" 
                        :class="selectedAction === 'draft' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-600 hover:bg-green-700'"
                        class="px-4 py-2 text-sm font-medium text-white rounded-lg cursor-pointer"
                    >
                        <span x-text="selectedAction === 'draft' ? 'Unpublish' : 'Publish'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection