@extends('layouts.admin')

@section('title', 'Edit Testimonial')
@section('page-title', 'Edit Testimonial')

@section('content')
<div class="max-w-3xl">
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('console.testimonials.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Testimonials
        </a>
    </div>

    <form method="POST" action="{{ route('console.testimonials.update', $testimonial) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Author Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Author Name -->
                <div class="space-y-1.5">
                    <label for="author_name" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Author Name <span class="text-red-500">*</span>
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full ml-8 mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Customer's name or first name only</span>
                        </span>
                    </label>
                    <input 
                        type="text" 
                        name="author_name" 
                        id="author_name"
                        value="{{ old('author_name', $testimonial->author_name) }}"
                        placeholder="e.g., Anna"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('author_name') border-red-500 @enderror"
                        required
                    >
                    @error('author_name')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Author Location -->
                <div class="space-y-1.5">
                    <label for="author_location" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Location
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Customer's country or location</span>
                        </span>
                    </label>
                    <input 
                        type="text" 
                        name="author_location" 
                        id="author_location"
                        value="{{ old('author_location', $testimonial->author_location) }}"
                        placeholder="e.g., Germany"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('author_location') border-red-500 @enderror"
                    >
                    @error('author_location')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Author Date -->
                <div class="space-y-1.5">
                    <label for="author_date" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Date
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full ml-8 mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">When the testimonial was given</span>
                        </span>
                    </label>
                    <input 
                        type="date" 
                        name="author_date" 
                        id="author_date"
                        value="{{ old('author_date', $testimonial->author_date?->format('Y-m-d')) }}"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('author_date') border-red-500 @enderror"
                    >
                    @error('author_date')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Author Cover -->
                <div class="space-y-1.5">
                    <label for="author_cover" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Cover Image URL
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Link to author's profile image or cover photo</span>
                        </span>
                    </label>
                    <input 
                        type="url" 
                        name="author_cover" 
                        id="author_cover"
                        value="{{ old('author_cover', $testimonial->author_cover) }}"
                        placeholder="https://example.com/image.jpg"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('author_cover') border-red-500 @enderror"
                    >
                    @error('author_cover')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link to Tour -->
                <div class="space-y-1.5">
                    <label for="tour_id" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Link to Tour
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full ml-8 mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Link this testimonial to an existing tour</span>
                        </span>
                    </label>
                    <select 
                        name="tour_id" 
                        id="tour_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer @error('tour_id') border-red-500 @enderror"
                    >
                        <option value="">-- No tour linked --</option>
                        @foreach($tours as $tour)
                            <option value="{{ $tour->id }}" {{ old('tour_id', $testimonial->tour_id) == $tour->id ? 'selected' : '' }}>{{ $tour->title }}</option>
                        @endforeach
                    </select>
                    @error('tour_id')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tour Name (Custom) -->
                <div class="space-y-1.5">
                    <label for="tour_name" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Tour Name (Custom)
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Use if tour is not in the system</span>
                        </span>
                    </label>
                    <input 
                        type="text" 
                        name="tour_name" 
                        id="tour_name"
                        value="{{ old('tour_name', $testimonial->tour_name) }}"
                        placeholder="e.g., Nairobi highlights"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('tour_name') border-red-500 @enderror"
                    >
                    @error('tour_name')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Travel Type -->
                <div class="space-y-1.5">
                    <label for="travel_type" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Travel Type
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">How the customer traveled (solo, couple, family, etc.)</span>
                        </span>
                    </label>
                    <select 
                        name="travel_type" 
                        id="travel_type"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer @error('travel_type') border-red-500 @enderror"
                    >
                        <option value="">Select travel type</option>
                        <option value="Solo traveller" {{ old('travel_type', $testimonial->travel_type) === 'Solo traveller' ? 'selected' : '' }}>Solo traveller</option>
                        <option value="Couple" {{ old('travel_type', $testimonial->travel_type) === 'Couple' ? 'selected' : '' }}>Couple</option>
                        <option value="Family with children" {{ old('travel_type', $testimonial->travel_type) === 'Family with children' ? 'selected' : '' }}>Family with children</option>
                        <option value="Friends group" {{ old('travel_type', $testimonial->travel_type) === 'Friends group' ? 'selected' : '' }}>Friends group</option>
                        <option value="Business traveller" {{ old('travel_type', $testimonial->travel_type) === 'Business traveller' ? 'selected' : '' }}>Business traveller</option>
                        <option value="Local guest" {{ old('travel_type', $testimonial->travel_type) === 'Local guest' ? 'selected' : '' }}>Local guest</option>
                    </select>
                    @error('travel_type')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Testimonial Content</h2>
            
            <!-- Content -->
            <div class="space-y-1.5 mb-4">
                <label for="content" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                    Testimonial <span class="text-red-500">*</span>
                    <span class="relative group">
                        <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">The customer's feedback or review text</span>
                    </span>
                </label>
                <textarea 
                    name="content" 
                    id="content"
                    rows="4"
                    placeholder="What did the customer say about their experience?"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('content') border-red-500 @enderror"
                    required
                >{{ old('content', $testimonial->content) }}</textarea>
                @error('content')
                <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rating -->
            <div class="space-y-1.5">
                <label for="rating" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                    Rating <span class="text-red-500">*</span>
                    <span class="relative group">
                        <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Star rating given by the customer (1-5)</span>
                    </span>
                </label>
                <select 
                    name="rating" 
                    id="rating"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer @error('rating') border-red-500 @enderror"
                    required
                >
                    <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>★★★★★ (5 stars)</option>
                    <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>★★★★☆ (4 stars)</option>
                    <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>★★★☆☆ (3 stars)</option>
                    <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>★★☆☆☆ (2 stars)</option>
                    <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>★☆☆☆☆ (1 star)</option>
                </select>
                @error('rating')
                <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Settings</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Sort Order -->
                <div class="space-y-1.5">
                    <label for="sort_order" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Sort Order
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full ml-8 mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Controls display order; lower numbers appear first</span>
                        </span>
                    </label>
                    <input 
                        type="number" 
                        name="sort_order" 
                        id="sort_order"
                        value="{{ old('sort_order', $testimonial->sort_order) }}"
                        min="0"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('sort_order') border-red-500 @enderror"
                    >
                    <p class="text-xs text-slate-500">Lower numbers appear first</p>
                    @error('sort_order')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Status
                        <span class="relative group">
                            <svg class="w-4 h-4 text-slate-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 px-2 py-1 text-xs text-white bg-slate-800 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">Only active testimonials are shown publicly</span>
                        </span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                        >
                        <span class="text-sm text-slate-700">Active (visible on website)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('console.testimonials.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 cursor-pointer">
                Update Testimonial
            </button>
        </div>
    </form>
</div>
@endsection
