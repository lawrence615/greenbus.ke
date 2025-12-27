@extends('layouts.admin')

@section('title', 'Add Pricing - ' . $tour->title)
@section('page-title', 'Add Pricing')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl" x-data="{ open: false }">
    <div class="mb-6">
        <a href="{{ route('console.tours.bespoke.show', $tour) }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Tour
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-start justify-between gap-6">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Pricing</h2>
                <p class="text-sm text-slate-600 mt-1">Add pricing options for "{{ $tour->title }}".</p>
            </div>
            <button type="button" @click="open = true" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
                Add Pricing
            </button>
        </div>

        <p class="text-sm text-slate-600 mt-4">
            You can also manage pricing directly from the tour details page.
        </p>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                Please fix the highlighted fields below.
            </div>
        @endif
    </div>

    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        @keydown.escape.window="open = false"
    >
        <div class="fixed inset-0 bg-black/50" @click="open = false"></div>

        <div class="relative w-full max-w-2xl mx-4">
            <div class="bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Add Pricing</h3>
                        <p class="text-sm text-slate-600 mt-1">{{ $tour->title }}</p>
                    </div>
                    <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ url('/console/tours/' . $tour->slug . '/pricing') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="person_type" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                                    Person Type <span class="text-red-500">*</span>
                                </label>
                                <select id="person_type" name="person_type" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none @error('person_type') border-red-500 @enderror">
                                    <option value="">Select type</option>
                                    <option value="adult" {{ old('person_type') === 'adult' ? 'selected' : '' }}>Adult</option>
                                    <option value="senior" {{ old('person_type') === 'senior' ? 'selected' : '' }}>Senior</option>
                                    <option value="youth" {{ old('person_type') === 'youth' ? 'selected' : '' }}>Youth</option>
                                    <option value="child" {{ old('person_type') === 'child' ? 'selected' : '' }}>Child</option>
                                    <option value="infant" {{ old('person_type') === 'infant' ? 'selected' : '' }}>Infant</option>
                                    <option value="individual" {{ old('person_type') === 'individual' ? 'selected' : '' }}>Individual</option>
                                </select>
                                @error('person_type')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="price" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                                    Price <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="price" name="price" required min="0" value="{{ old('price') }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none @error('price') border-red-500 @enderror" />
                                @error('price')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="discount" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                                    Discount
                                </label>
                                <input type="number" id="discount" name="discount" min="0" value="{{ old('discount', 0) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none @error('discount') border-red-500 @enderror" />
                                @error('discount')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="discounted_price" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                                    Discounted Price
                                </label>
                                <input type="number" id="discounted_price" name="discounted_price" min="0" value="{{ old('discounted_price', 0) }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none @error('discounted_price') border-red-500 @enderror" />
                                @error('discounted_price')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 cursor-pointer">
                                Save Pricing
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
