@extends('layouts.admin')

@section('title', $tour->title)
@section('page-title', 'Bespoke Tour Details')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }

    .modal-overlay {
        backdrop-filter: blur(2px);
    }

    /* Remove increment/decrement arrows on number inputs */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
        appearance: none;
    }
</style>
@endpush

@section('content')
<div x-data="{ pricing: pricingModal(), publish: publishModal(), deletePricing: deletePricingModal(), shareLink: shareLinkManager() }" class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('console.tours.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tours
        </a>

        <div class="flex items-center gap-2">
            <button
                type="button"
                @click="publish.openModal(@json($tour), '{{ $tour->status === 'published' ? 'draft' : 'publish' }}')"
                class="inline-flex items-center gap-2 px-4 py-2 {{ $tour->status === 'published' ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }} rounded-lg text-sm font-medium cursor-pointer">
                {{ $tour->status === 'published' ? 'Unpublish' : 'Publish' }}
            </button>

            <a href="{{ route('console.tours.bespoke.edit', $tour) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Bespoke Tour
            </a>

            <!-- <button type="button" @click="pricing.openModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Manage Pricing
            </button> -->

            <a href="{{ route('console.tours.itinerary.index', $tour) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Manage Itinerary
            </a>

            <a href="{{ route('console.tours.multimedia.index', $tour) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Manage Images
            </a>

            @if($tour->share_token && $tour->isShareLinkValid())
                <button type="button" @click="shareLink.copyShareUrl('{{ $tour->share_url }}')" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copy Share Link
                </button>
            @else
                <button type="button" @click="shareLink.generateShareLink({{ $tour->id }})" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                    </svg>
                    Generate Share Link
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
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
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-600/20">Bespoke</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">{{ $tour->code }}</span>
                            </div>
                            <h1 class="text-2xl font-bold text-slate-900 mt-2">{{ $tour->title }}</h1>
                            <p class="text-slate-500 mt-1">{{ $tour->location->name ?? 'N/A' }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $tour->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst($tour->status) }}
                        </span>
                    </div>
                </div>
            </div>

            @if($tour->description)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="font-semibold text-slate-900 mb-4">Description</h2>
                <div class="prose prose-slate max-w-none">
                    {!! $tour->description !!}
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Overview</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Tour Type</span>
                        <span class="font-medium text-slate-900">Bespoke</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Location</span>
                        <span class="font-medium text-slate-900">{{ $tour->location->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Category</span>
                        <span class="font-medium text-slate-900">{{ $tour->category->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Created</span>
                        <span class="font-medium text-slate-900">{{ optional($tour->created_at)->format('M j, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-900">Pricing</h3>
                    <button type="button" @click="pricing.openModal()" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 cursor-pointer">Manage</button>
                </div>

                @if($tour->pricings->isEmpty())
                <div class="space-y-3 mb-4">
                    <div class="animate-pulse flex items-center justify-between">
                        <div class="h-4 w-24 bg-slate-200 rounded"></div>
                        <div class="h-4 w-16 bg-slate-200 rounded"></div>
                    </div>
                    <div class="animate-pulse flex items-center justify-between">
                        <div class="h-4 w-20 bg-slate-200 rounded"></div>
                        <div class="h-4 w-14 bg-slate-200 rounded"></div>
                    </div>
                    <div class="animate-pulse flex items-center justify-between">
                        <div class="h-4 w-28 bg-slate-200 rounded"></div>
                        <div class="h-4 w-20 bg-slate-200 rounded"></div>
                    </div>
                </div>

                <p class="text-sm text-slate-600">No pricing has been added for this tour yet.</p>
                <div class="mt-4">
                    <button type="button" @click="pricing.openModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 cursor-pointer">
                        Add Pricing
                    </button>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 font-medium text-slate-700 text-left">Person</th>
                                <th class="px-4 py-3 font-medium text-slate-700 text-left">Price</th>
                                <th class="px-4 py-3 font-medium text-slate-700 text-center">Discount</th>
                                <th class="px-4 py-3 font-medium text-slate-700 text-center">Discount</th>
                                <th class="px-4 py-3 font-medium text-slate-700 text-center w-20"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($tour->pricings as $pricing)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 font-medium text-slate-900">{{ ucfirst($pricing->person_type) }}</td>
                                <td class="px-4 py-4 text-right text-slate-900 font-medium">${{ number_format($pricing->price, 2) }}</td>
                                <td class="px-4 py-4 text-right text-slate-600">${{ number_format($pricing->discount ?? 0, 2) }}</td>
                                <td class="px-4 py-4 text-right">
                                    @if(($pricing->discounted_price ?? 0) > 0 && ($pricing->discounted_price ?? 0) < ($pricing->price ?? 0))
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800">
                                            ${{ number_format($pricing->discounted_price, 2) }}
                                        </span>
                                        @else
                                        <span class="font-medium text-slate-900">${{ number_format($pricing->price, 2) }}</span>
                                        @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button type="button" @click="open = !open" class="inline-flex items-center justify-center w-8 h-8 rounded-lg hover:bg-slate-100 transition-colors">
                                            <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        <div x-show="open" @click.away="open = false" x-cloak
                                            class="absolute right-0 z-10 mt-1 w-48 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                            role="menu">
                                            <div class="py-1">
                                                <button type="button" class="flex items-center w-full px-4 py-2 text-sm text-slate-700 hover:bg-slate-100" role="menuitem">
                                                    <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </button>
                                                <button type="button" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50" role="menuitem" @click="deletePricing.openModal({{ $pricing->toJson() }})">
                                                    <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <button type="button" @click="pricing.openModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 cursor-pointer">
                        Add Pricing
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Pricing Form Modal --}}
    <div
        x-show="pricing.open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        @keydown.escape.window="pricing.closeModal()">
        <div class="fixed inset-0 bg-black/50 modal-overlay" @click="pricing.closeModal()"></div>

        <div class="relative w-full max-w-2xl mx-4">
            <div class="bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Add Pricing</h3>
                        <p class="text-sm text-slate-600 mt-1">{{ $tour->title }}</p>
                    </div>
                    <button type="button" @click="pricing.closeModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ url('/console/tours/' . $tour->slug . '/pricing/store') }}" method="POST" @submit.prevent="pricing.submit($event)" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tour_id" value="{{ $tour->id }}" />

                    <div class="p-6">
                        <div x-show="pricing.successMessage" x-text="pricing.successMessage" class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"></div>
                        <div x-show="!pricing.successMessage && Object.keys(pricing.errors).length > 0" class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            Please fix the highlighted fields below.
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="person_type" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                                    Person Type <span class="text-red-500">*</span>
                                </label>
                                <select id="person_type" name="person_type" required :class="{ 'border-red-500': pricing.hasError('person_type') }" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none">
                                    <option value="">Select type</option>
                                    <option value="adult" {{ old('person_type') === 'adult' ? 'selected' : '' }}>Adult</option>
                                    <option value="senior" {{ old('person_type') === 'senior' ? 'selected' : '' }}>Senior</option>
                                    <option value="youth" {{ old('person_type') === 'youth' ? 'selected' : '' }}>Youth</option>
                                    <option value="child" {{ old('person_type') === 'child' ? 'selected' : '' }}>Child</option>
                                    <option value="infant" {{ old('person_type') === 'infant' ? 'selected' : '' }}>Infant</option>
                                    <option value="individual" {{ old('person_type') === 'individual' ? 'selected' : '' }}>Individual</option>
                                </select>
                                <p x-show="pricing.hasError('person_type')" x-text="pricing.getError('person_type')" class="text-xs text-red-500"></p>
                            </div>

                            <div class="space-y-1.5">
                                <label for="price" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                                    Price (USD) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="price" name="price" x-model.number="pricing.price" required min="0" value="{{ old('price') }}" :class="{ 'border-red-500': pricing.hasError('price') }" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none" />
                                <p x-show="pricing.hasError('price')" x-text="pricing.getError('price')" class="text-xs text-red-500"></p>
                            </div>

                            <div class="space-y-1.5">
                                <label for="discount" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                                    Discount (USD)
                                </label>
                                <input type="number" id="discount" name="discount" x-model.number="pricing.discount" min="0" value="{{ old('discount', 0) }}" :class="{ 'border-red-500': pricing.hasError('discount') }" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none" />
                                <p x-show="pricing.hasError('discount')" x-text="pricing.getError('discount')" class="text-xs text-red-500"></p>
                            </div>

                            <div class="space-y-1.5">
                                <label for="discounted_price" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                                    Discounted Price (USD)
                                </label>
                                <input type="number" id="discounted_price" name="discounted_price" readonly :value="pricing.discountedPrice" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600 shadow-sm cursor-not-allowed" />
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" @click="pricing.closeModal()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                                Cancel
                            </button>
                            <button type="submit" :disabled="pricing.submitting" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg x-show="pricing.submitting" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="pricing.submitting ? 'Saving...' : 'Save Pricing'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Publish Confirm Dialog --}}
    <div
        x-show="publish.showConfirmModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        @keydown.escape.window="publish.closeModal()">
        <div class="fixed inset-0 bg-black/50" @click="publish.closeModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl p-6 max-w-sm mx-4 z-10">
            <h3 class="text-lg text-center font-semibold text-slate-900 mb-2">
                <span x-text="publish.selectedAction === 'draft' ? 'Unpublish Tour?' : 'Publish Tour?'"></span>
            </h3>
            <p class="text-sm text-slate-600 mb-4">
                <span x-show="publish.selectedAction === 'draft'">This tour will no longer be visible to the public.</span>
                <span x-show="publish.selectedAction === 'publish'">This tour will become visible to the public.</span>
            </p>
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="publish.closeModal()"
                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 cursor-pointer">
                    Cancel
                </button>
                <form method="POST" :action="`/console/tours/${publish.selectedTour.slug}/toggle-status`"
                    @submit="$event.preventDefault(); window.showLoading('Toggling tour status...', 'Updating Tour'); $el.submit();">
                    @csrf
                    @method('PATCH')
                    <button
                        type="submit"
                        :class="publish.selectedAction === 'draft' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-600 hover:bg-green-700'"
                        class="px-4 py-2 text-sm font-medium text-white rounded-lg cursor-pointer">
                        <span x-text="publish.selectedAction === 'draft' ? 'Unpublish' : 'Publish'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Pricing Confirm Modal --}}
    <div
        x-show="deletePricing.showConfirmModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        @keydown.escape.window="deletePricing.closeModal()">
        <div class="fixed inset-0 bg-black/50" @click="deletePricing.closeModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl p-6 max-w-sm mx-4 z-10">
            <h3 class="text-lg text-center font-semibold text-slate-900 mb-2">Delete Pricing?</h3>
            <p class="text-sm text-slate-600 mb-4">This action cannot be undone. The pricing for <span x-text="deletePricing.selectedPricing?.person_type" class="font-medium"></span> will be permanently deleted.</p>
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="deletePricing.closeModal()"
                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 cursor-pointer">
                    Cancel
                </button>
                <button
                    type="button"
                    @click="deletePricing.confirmDelete()"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 cursor-pointer">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pricingModal', () => ({
            open: @json((bool) $errors -> any()),
            submitting: false,
            errors: {},
            successMessage: '',
            price: 0,
            discount: 0,
            get discountedPrice() {
                return Math.max(0, this.price - this.discount);
            },
            openModal() {
                this.open = true;
            },
            closeModal() {
                this.open = false;
                this.errors = {};
                this.successMessage = '';
                this.price = 0;
                this.discount = 0;
            },
            async submit(event) {
                const form = event.target;
                const formData = new FormData(form);
                // Set discounted_price calculated value
                formData.set('discounted_price', this.discountedPrice);
                this.submitting = true;
                this.errors = {};
                this.successMessage = '';

                try {
                    const response = await axios.post(form.action, formData, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });

                    this.successMessage = 'Pricing added successfully!';
                    setTimeout(() => {
                        this.closeModal();
                        window.location.reload(); // simple refresh to show updated pricing card
                    }, 800);
                } catch (error) {
                    if (error.response?.status === 422 && error.response.data.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        this.successMessage = 'Something went wrong. Please try again.';
                    }
                } finally {
                    this.submitting = false;
                }
            },
            hasError(field) {
                return this.errors[field] && this.errors[field].length > 0;
            },
            getError(field) {
                return this.errors[field]?.[0] || '';
            },
        }));

        Alpine.data('publishModal', () => ({
            showConfirmModal: false,
            selectedTour: null,
            selectedAction: '',
            openModal(tour, action) {
                this.selectedTour = tour;
                this.selectedAction = action;
                this.showConfirmModal = true;
            },
            closeModal() {
                this.showConfirmModal = false;
                this.selectedTour = null;
                this.selectedAction = '';
            },
        }));

        Alpine.data('deletePricingModal', () => ({
            showConfirmModal: false,
            selectedPricing: null,
            openModal(pricing) {
                this.selectedPricing = pricing;
                this.showConfirmModal = true;
            },
            closeModal() {
                this.showConfirmModal = false;
                this.selectedPricing = null;
            },
            async confirmDelete() {
                if (!this.selectedPricing) return;
                try {
                    const response = await axios.delete(`/console/tours/{{ $tour->slug }}/pricing/${this.selectedPricing.id}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });
                    if (response.data.success) {
                        this.closeModal();
                        window.location.reload();
                    } else {
                        alert(response.data.message || 'Failed to delete pricing.');
                    }
                } catch (error) {
                    alert('Unable to delete pricing. Please try again.');
                }
            },
        }));

        Alpine.data('shareLinkManager', () => ({
            async generateShareLink(tourId) {
                try {
                    const response = await axios.post(`/console/tours/bespoke/${tourId}/share`, {}, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });
                    if (response.data.success) {
                        window.location.reload();
                    } else {
                        alert(response.data.message || 'Failed to generate share link.');
                    }
                } catch (error) {
                    alert('Unable to generate share link. Please try again.');
                }
            },
            async copyShareUrl(url) {
                try {
                    await navigator.clipboard.writeText(url);
                    // Show a temporary success message
                    const button = event.target.closest('button');
                    const originalText = button.innerHTML;
                    button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Copied!';
                    button.classList.remove('bg-green-600', 'hover:bg-green-700');
                    button.classList.add('bg-green-700');
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.classList.remove('bg-green-700');
                        button.classList.add('bg-green-600', 'hover:bg-green-700');
                    }, 2000);
                } catch (error) {
                    alert('Failed to copy link. Please copy manually: ' + url);
                }
            },
        }));
    });
</script>
@endpush