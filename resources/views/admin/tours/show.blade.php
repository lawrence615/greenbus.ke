@extends('layouts.admin')

@section('title', $tour->title)
@section('page-title', 'Tour Details')

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
    <!-- Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('console.tours.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tours
        </a>
        <div class="flex items-center gap-2">
            <div x-data="{ showConfirm: false }" class="inline relative">
                <button
                    type="button"
                    @click="openModal({{ $tour->toJson() }}, '{{ $tour->status === 'published' ? 'draft' : 'publish' }}')"
                    class="inline-flex items-center gap-2 px-4 py-2 {{ $tour->status === 'published' ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }} rounded-lg text-sm font-medium cursor-pointer">
                    {{ $tour->status === 'published' ? 'Unpublish' : 'Publish' }}
                </button>
            </div>
            <a href="{{ route('console.tours.edit', $tour) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Tour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tour Header -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                @php
                $cover = $tour->images->firstWhere('is_cover', true) ?? $tour->images->first();
                @endphp
                @if($cover)
                <div class="aspect-video w-full overflow-hidden">
                    <img src="{{ $cover->url }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
                </div>
                @else
                <div class="aspect-video w-full bg-slate-200 flex items-center justify-center">
                    <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                @endif
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900">{{ $tour->title }}</h1>
                            <p class="text-slate-500 mt-1">{{ $tour->location->name ?? 'N/A' }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $tour->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst($tour->status) }}
                        </span>
                    </div>
                    @if($tour->short_description)
                    <p class="mt-4 text-slate-600">{!! $tour->short_description !!}</p>
                    @endif
                </div>
            </div>

            <!-- Description -->
            @if($tour->description)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="font-semibold text-slate-900 mb-4">Description</h2>
                <div class="prose prose-slate max-w-none">
                    {!! $tour->description !!}
                </div>
            </div>
            @endif

            <!-- What's Included -->
            @if($tour->included)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="font-semibold text-slate-900 mb-4">What's Included</h2>
                <div class="prose prose-slate max-w-none">
                    {!! $tour->included !!}
                </div>
            </div>
            @endif

            <!-- Important Information -->
            @if($tour->important_information)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="font-semibold text-slate-900 mb-4">Important Information</h2>
                <div class="prose prose-slate max-w-none">
                    {!! $tour->important_information !!}
                </div>
            </div>
            @endif

            <!-- Itinerary -->
            @if($tour->itineraryItems->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="font-semibold text-slate-900 mb-4">Itinerary</h2>
                <div class="space-y-4">
                    @foreach($tour->itineraryItems as $item)
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-semibold">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <h3 class="font-medium text-slate-900">{{ $item->title }}</h3>
                            @if($item->description)
                            <p class="text-sm text-slate-600 mt-1">{{ $item->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Images Gallery -->
            @if($tour->images->count() > 1)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="font-semibold text-slate-900 mb-4">Gallery ({{ $tour->images->count() }} images)</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($tour->images as $image)
                    <img src="{{ $image->url }}" alt="{{ $tour->title }}" class="w-full h-32 object-cover rounded-lg">
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="font-semibold text-slate-900 mb-4">Tour Information</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-slate-500">Category</dt>
                        <dd class="font-medium text-slate-900">{{ $tour->category->name ?? 'Uncategorized' }}</dd>
                    </div>
                    @if($tour->duration_text)
                    <div>
                        <dt class="text-sm text-slate-500">Duration</dt>
                        <dd class="font-medium text-slate-900">{{ $tour->duration_text }}</dd>
                    </div>
                    @endif
                    @if($tour->starts_at_time)
                    <div>
                        <dt class="text-sm text-slate-500">Start Time</dt>
                        <dd class="font-medium text-slate-900">{{ $tour->starts_at_time }}</dd>
                    </div>
                    @endif
                    @if($tour->meeting_point)
                    <div>
                        <dt class="text-sm text-slate-500">Meeting Point</dt>
                        <dd class="font-medium text-slate-900">{{ $tour->meeting_point }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm text-slate-500">Daily Tour</dt>
                        <dd class="font-medium text-slate-900">{{ $tour->is_daily ? 'Yes' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Featured</dt>
                        <dd class="font-medium text-slate-900">{{ $tour->featured ? 'Yes' : 'No' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Pricing -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="font-semibold text-slate-900 mb-4">Pricing</h2>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Senior</dt>
                        <dd class="font-semibold text-slate-900">KES {{ number_format($tour->base_price_senior) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Adult</dt>
                        <dd class="font-semibold text-slate-900">KES {{ number_format($tour->base_price_adult) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Child</dt>
                        <dd class="font-semibold text-slate-900">KES {{ number_format($tour->base_price_child ?? 0) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-600">Infant</dt>
                        <dd class="font-semibold text-slate-900">{{ $tour->base_price_infant > 0 ? 'KES ' . number_format($tour->base_price_infant) : 'Free' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="font-semibold text-slate-900 mb-4">Metadata</h2>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Code</dt>
                        <dd class="font-mono text-slate-900">{{ $tour->code }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Slug</dt>
                        <dd class="font-mono text-slate-900 truncate max-w-[150px]" title="{{ $tour->slug }}">{{ $tour->slug }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Created</dt>
                        <dd class="text-slate-900">{{ $tour->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Updated</dt>
                        <dd class="text-slate-900">{{ $tour->updated_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 rounded-xl border border-red-200 p-6" x-data="{ showDeleteConfirm: false }">
                <h2 class="font-semibold text-red-900 mb-2">Danger Zone</h2>
                <p class="text-sm text-red-700 mb-4">Deleting this tour will remove all associated data.</p>
                <button
                    type="button"
                    @click="showDeleteConfirm = true"
                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 cursor-pointer">
                    Delete Tour
                </button>

                <!-- Delete Confirmation Modal -->
                <div
                    x-show="showDeleteConfirm"
                    x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center"
                    @keydown.escape.window="showDeleteConfirm = false">
                    <div class="fixed inset-0 bg-black/50" @click="showDeleteConfirm = false"></div>
                    <div class="relative bg-white rounded-xl shadow-xl p-6 max-w-sm mx-4 z-10">
                        <h3 class="text-lg text-center font-semibold text-slate-900 mb-2">
                            Delete Tour?
                        </h3>
                        <p class="text-sm text-slate-600 mb-4">
                            Are you sure you want to delete <strong>{{ $tour->title }}</strong>? This action cannot be undone and will remove all associated images and data.
                        </p>
                        <div class="flex justify-end gap-3">
                            <button
                                type="button"
                                @click="showDeleteConfirm = false"
                                class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 cursor-pointer">
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('console.tours.destroy', $tour) }}"
                                @submit="$event.preventDefault(); window.showLoading('Deleting tour...', 'Removing Tour'); $el.submit();">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 cursor-pointer">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                <form method="POST" :action="selectedTour ? `/console/tours/${selectedTour.slug}/toggle-status` : '#'"
                    @submit="$event.preventDefault(); window.showLoading('Toggling tour status...', 'Updating Tour'); $el.submit();">
                    @csrf
                    @method('PATCH')
                    <button
                        type="submit"
                        :disabled="!selectedTour"
                        :class="selectedAction === 'draft' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-600 hover:bg-green-700'"
                        class="px-4 py-2 text-sm font-medium text-white rounded-lg cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-text="selectedAction === 'draft' ? 'Unpublish' : 'Publish'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection