@extends('layouts.admin')

@section('title', 'Trashed Tours')
@section('page-title', 'Trashed Tours')

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
    restoreUrl: '{{ route("console.tours.trash.restore", "PLACEHOLDER") }}',
    destroyUrl: '{{ route("console.tours.trash.destroy", "PLACEHOLDER") }}',
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
            <h1 class="text-2xl font-bold text-slate-900">Trashed Tours</h1>
            <p class="text-sm text-slate-500">Manage deleted tours - restore or permanently delete</p>
        </div>
        <a href="{{ route('console.tours.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to All Tours
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form method="GET" action="{{ route('console.tours.trash.index') }}" class="flex flex-col lg:flex-row lg:items-end gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 flex-1">
                <!-- Search -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tours..." class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
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
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filter
                </button>
                
                @if(request()->hasAny(['search', 'location_id', 'category_id']))
                <a href="{{ route('console.tours.trash.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 border border-slate-300 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tours Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        @if($tours->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <span class="font-semibold text-slate-700 uppercase text-xs tracking-wider">Tour</span>
                        </th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">
                            <span class="font-semibold text-slate-700 uppercase text-xs tracking-wider">Location</span>
                        </th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">
                            <span class="font-semibold text-slate-700 uppercase text-xs tracking-wider">Status</span>
                        </th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">
                            <span class="font-semibold text-slate-700 uppercase text-xs tracking-wider">Type</span>
                        </th>
                        <th class="px-4 py-3 text-left hidden xl:table-cell">
                            <span class="font-semibold text-slate-700 uppercase text-xs tracking-wider">Price</span>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <span class="font-semibold text-slate-700 uppercase text-xs tracking-wider">Deleted At</span>
                        </th>
                        <th class="px-4 py-3 text-right">
                            <span class="font-semibold text-slate-700 uppercase text-xs tracking-wider">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tours as $tour)
                    <tr class="hover:bg-red-50/50 transition-colors duration-150 group">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @php
                                $cover = $tour->images->firstWhere('is_cover', true) ?? $tour->images->first();
                                @endphp
                                <div class="shrink-0 hidden sm:block">
                                    @if($cover)
                                    <img src="{{ $cover->url }}" alt="{{ $tour->title }}" class="w-12 h-12 rounded-lg object-cover shadow-sm ring-1 ring-slate-200 opacity-75">
                                    @else
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center ring-1 ring-slate-200 opacity-75">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <!-- Tour Code Badge -->
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-medium bg-red-100 text-red-600 mb-1">
                                        {{ $tour->code }}
                                    </span>
                                    <!-- Tour Title -->
                                    <div class="font-medium text-slate-900 truncate max-w-[200px] lg:max-w-[350px]" title="{{ $tour->title }}">
                                        {{ $tour->title }}
                                    </div>
                                    <!-- Mobile: Show location inline -->
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-slate-500">
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
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <div class="flex flex-col gap-1">
                                <span class="font-medium text-slate-700">{{ $tour->location->name ?? 'N/A' }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 w-fit">
                                    {{ $tour->category->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold ring-1 ring-inset bg-red-100 text-red-800 ring-red-200">
                                Deleted
                            </span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold ring-1 ring-inset
                                {{ ($tour->tour_type ?? 'standard') === 'standard' ? 'bg-blue-50 text-blue-700 ring-blue-600/20' : '' }}
                                {{ ($tour->tour_type ?? 'standard') === 'bespoke' ? 'bg-purple-50 text-purple-700 ring-purple-600/20' : '' }}
                                {{ ($tour->tour_type ?? 'standard') === 'other' ? 'bg-slate-100 text-slate-700 ring-slate-600/20' : '' }}">
                                {{ ucfirst($tour->tour_type ?? 'standard') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 hidden xl:table-cell">
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-900">
                                    @php
                                    $adultPricing = $tour->pricings->where('person_type', 'adult')->first();
                                    $price = $adultPricing ? $adultPricing->price : ($tour->base_price_adult ?? 0);
                                    @endphp
                                    USD {{ number_format($price) }}
                                </span>
                                <span class="text-[10px] text-slate-500">per adult</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col">
                                <span class="text-sm text-slate-900">{{ $tour->deleted_at->format('M j, Y') }}</span>
                                <span class="text-[10px] text-slate-500">{{ $tour->deleted_at->format('g:i A') }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-0.5">
                                <button 
                                    @click="openModal({{ $tour->toJson() }}, 'restore')"
                                    class="p-1.5 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors duration-150" 
                                    title="Restore Tour">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                                <button 
                                    @click="openModal({{ $tour->toJson() }}, 'delete')"
                                    class="p-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-150" 
                                    title="Permanently Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <h3 class="text-lg font-medium text-slate-900 mb-2">No trashed tours</h3>
                                <p class="text-slate-400 text-sm">All tours are active. Nothing to restore or delete.</p>
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
        @else
        <div class="p-12 text-center">
            <svg class="w-12 h-12 text-slate-400 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <h3 class="text-lg font-medium text-slate-900 mb-2">No trashed tours</h3>
            <p class="text-slate-400 text-sm mb-4">All tours are active. Nothing to restore or delete.</p>
            <a href="{{ route('console.tours.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                View All Tours
            </a>
        </div>
        @endif
    </div>

    <!-- Confirmation Modal -->
    <div
        x-show="showConfirmModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        @keydown.escape.window="closeModal()">
        <div class="fixed inset-0 bg-black/50" @click="closeModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl p-6 max-w-sm mx-4 z-10">
            <h3 class="text-lg text-center font-semibold text-slate-900 mb-2">
                <span x-show="selectedAction === 'restore'">Restore Tour?</span>
                <span x-show="selectedAction === 'delete'">Permanently Delete Tour?</span>
            </h3>
            <p class="text-sm text-slate-600 mb-4">
                <span x-show="selectedAction === 'restore'">This tour will be restored and will be visible again.</span>
                <span x-show="selectedAction === 'delete'">This tour will be permanently deleted and cannot be recovered.</span>
            </p>
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="closeModal()"
                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 cursor-pointer">
                    Cancel
                </button>
                <form method="POST" :action="selectedAction === 'restore' ? (selectedTour ? restoreUrl.replace('PLACEHOLDER', selectedTour.id) : '#') : (selectedTour ? destroyUrl.replace('PLACEHOLDER', selectedTour.id) : '#')"
                    @submit="$event.preventDefault(); window.showLoading('Processing...', 'Updating Tour'); $el.submit();">
                    @csrf
                    <input type="hidden" name="_method" :value="selectedAction === 'delete' ? 'DELETE' : 'POST'">
                    <button
                        type="submit"
                        :disabled="!selectedTour"
                        :class="selectedAction === 'restore' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'"
                        class="px-4 py-2 text-sm font-medium text-white rounded-lg cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="selectedAction === 'restore'">Restore</span>
                        <span x-show="selectedAction === 'delete'">Delete Forever</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
