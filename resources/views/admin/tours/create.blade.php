@extends('layouts.admin')

@section('title', 'Add Tour')
@section('page-title', 'Add Tour')

@section('content')
<div class="max-w-4xl">
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('console.tours.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Tours
        </a>
    </div>

    <form method="POST" action="{{ route('console.tours.store') }}" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="font-semibold text-slate-900">Basic Information</h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="city_id" class="block text-sm font-medium text-slate-700 mb-1">City *</label>
                        <select name="city_id" id="city_id" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            <option value="">Select a city</option>
                            @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('city_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="tour_category_id" class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                        <select name="tour_category_id" id="tour_category_id" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('tour_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('tour_category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="short_description" class="block text-sm font-medium text-slate-700 mb-1">Short Description</label>
                    <textarea name="short_description" id="short_description" rows="2" maxlength="300" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('short_description') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Max 300 characters</p>
                    @error('short_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Full Description</label>
                    <textarea name="description" id="description" rows="6" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tour Details -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="font-semibold text-slate-900">Tour Details</h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="duration_text" class="block text-sm font-medium text-slate-700 mb-1">Duration</label>
                        <input type="text" name="duration_text" id="duration_text" value="{{ old('duration_text') }}" placeholder="e.g. 4 hours" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('duration_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="starts_at_time" class="block text-sm font-medium text-slate-700 mb-1">Start Time</label>
                        <input type="time" name="starts_at_time" id="starts_at_time" value="{{ old('starts_at_time') }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('starts_at_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="meeting_point" class="block text-sm font-medium text-slate-700 mb-1">Meeting Point</label>
                        <input type="text" name="meeting_point" id="meeting_point" value="{{ old('meeting_point') }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('meeting_point')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="includes" class="block text-sm font-medium text-slate-700 mb-1">What's Included</label>
                    <textarea name="includes" id="includes" rows="4" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="List what's included in the tour...">{{ old('includes') }}</textarea>
                    @error('includes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="important_information" class="block text-sm font-medium text-slate-700 mb-1">Important Information</label>
                    <textarea name="important_information" id="important_information" rows="4" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Any important notes for travelers...">{{ old('important_information') }}</textarea>
                    @error('important_information')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="font-semibold text-slate-900">Pricing (KES)</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="base_price_adult" class="block text-sm font-medium text-slate-700 mb-1">Adult Price *</label>
                        <input type="number" name="base_price_adult" id="base_price_adult" value="{{ old('base_price_adult') }}" min="0" step="1" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                        @error('base_price_adult')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="base_price_child" class="block text-sm font-medium text-slate-700 mb-1">Child Price</label>
                        <input type="number" name="base_price_child" id="base_price_child" value="{{ old('base_price_child', 0) }}" min="0" step="1" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('base_price_child')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="base_price_infant" class="block text-sm font-medium text-slate-700 mb-1">Infant Price</label>
                        <input type="number" name="base_price_infant" id="base_price_infant" value="{{ old('base_price_infant', 0) }}" min="0" step="1" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <p class="mt-1 text-xs text-slate-500">Set to 0 for free</p>
                        @error('base_price_infant')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="font-semibold text-slate-900">Settings</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_daily" id="is_daily" value="1" {{ old('is_daily') ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="is_daily" class="text-sm text-slate-700">Daily tour (runs every day)</label>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="featured" class="text-sm text-slate-700">Featured tour (show on homepage)</label>
                </div>
                <div class="pt-4">
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status *</label>
                    <select name="status" id="status" class="w-full md:w-48 rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                Create Tour
            </button>
            <a href="{{ route('console.tours.index') }}" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
