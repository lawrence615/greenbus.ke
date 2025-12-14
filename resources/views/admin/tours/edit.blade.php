@extends('layouts.admin')

@section('title', 'Edit Tour')
@section('page-title', 'Edit Tour')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor { min-height: 150px; }
    .ql-container { font-size: 14px; border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; }
    .ql-toolbar { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; }
    .sortable-ghost { opacity: 0.4; }
    .sortable-drag { opacity: 1; }
    .drag-handle { cursor: grab; }
    .drag-handle:active { cursor: grabbing; }
</style>
@endpush

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl" x-data="tourEditForm()">
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('console.tours.show', $tour) }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Tour
        </a>
    </div>

    <form method="POST" action="{{ route('console.tours.update', $tour) }}" class="space-y-6" @submit="syncEditors" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="font-semibold text-slate-900">Basic Information</h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Location Select -->
                    <div class="space-y-2">
                        <label for="location_id" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-emerald-50 text-emerald-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            Location <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="location_id" id="location_id" class="w-full appearance-none rounded-lg border border-slate-300 bg-white px-4 py-2.5 pr-10 text-sm text-slate-900 shadow-sm transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none" required>
                                <option value="">Select a location</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id', $tour->location_id) == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('location_id')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Category Select -->
                    <div class="space-y-2">
                        <label for="tour_category_id" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-blue-50 text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </span>
                            Category
                        </label>
                        <div class="relative">
                            <select name="tour_category_id" id="tour_category_id" class="w-full appearance-none rounded-lg border border-slate-300 bg-white px-4 py-2.5 pr-10 text-sm text-slate-900 shadow-sm transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('tour_category_id', $tour->tour_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('tour_category_id')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <!-- Title Input -->
                <div class="space-y-2">
                    <label for="title" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                        <span class="flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 text-amber-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </span>
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $tour->title) }}" placeholder="Enter tour title" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none" required>
                    @error('title')
                    <p class="text-sm text-red-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Short Description</label>
                    <div id="short_description_editor" class="bg-white rounded-lg border border-slate-300"></div>
                    <input type="hidden" name="short_description" id="short_description" value="{{ old('short_description', $tour->short_description) }}">
                    @error('short_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Full Description</label>
                    <div id="description_editor" class="bg-white rounded-lg border border-slate-300"></div>
                    <input type="hidden" name="description" id="description" value="{{ old('description', $tour->description) }}">
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
                    <!-- Duration -->
                    <div class="space-y-2">
                        <label for="duration_text" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-purple-50 text-purple-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="8" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-width="2" d="M12 8v4l2.5 1.5"/>
                                </svg>
                            </span>
                            Duration
                        </label>
                        <input type="text" name="duration_text" id="duration_text" value="{{ old('duration_text', $tour->duration_text) }}" placeholder="e.g. 4 hours" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                        @error('duration_text')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div class="space-y-2">
                        <label for="starts_at_time" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-sky-50 text-sky-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            Start Time
                        </label>
                        <input type="time" name="starts_at_time" id="starts_at_time" value="{{ old('starts_at_time', $tour->starts_at_time) }}" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                        @error('starts_at_time')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Meeting Point -->
                    <div class="space-y-2">
                        <label for="meeting_point" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-rose-50 text-rose-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            Meeting Point
                        </label>
                        <input type="text" name="meeting_point" id="meeting_point" value="{{ old('meeting_point', $tour->meeting_point) }}" placeholder="e.g. Hotel lobby" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                        @error('meeting_point')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">What's Included</label>
                    <div id="included_editor" class="bg-white rounded-lg border border-slate-300"></div>
                    <input type="hidden" name="included" id="included" value="{{ old('included', $tour->included) }}">
                    @error('included')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Important Information</label>
                    <div id="important_information_editor" class="bg-white rounded-lg border border-slate-300"></div>
                    <input type="hidden" name="important_information" id="important_information" value="{{ old('important_information', $tour->important_information) }}">
                    @error('important_information')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Itinerary -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="font-semibold text-slate-900">Itinerary</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4" id="itinerary-list" x-ref="itineraryList">
                    <template x-for="(item, index) in itinerary" :key="item.id || index">
                        <div class="flex gap-3 p-5 bg-gradient-to-br from-slate-50 to-white rounded-xl border border-slate-200 shadow-sm itinerary-item" :data-index="index">
                            <!-- Drag Handle & Step Icon -->
                            <div class="shrink-0 flex flex-col items-center gap-2">
                                <!-- Drag Handle -->
                                <div class="drag-handle p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded cursor-grab active:cursor-grabbing" title="Drag to reorder">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                    </svg>
                                </div>
                                
                                <!-- Step Icon based on type -->
                                <div class="w-10 h-10 rounded-full text-white flex items-center justify-center shadow-md"
                                    :class="{
                                        'bg-gradient-to-br from-green-500 to-green-600': item.type === 'start',
                                        'bg-gradient-to-br from-blue-500 to-blue-600': item.type === 'transit',
                                        'bg-gradient-to-br from-emerald-500 to-emerald-600': item.type === 'stopover',
                                        'bg-gradient-to-br from-amber-500 to-amber-600': item.type === 'activity' || !item.type,
                                        'bg-gradient-to-br from-red-500 to-red-600': item.type === 'end'
                                    }">
                                    <!-- Start icon (play) -->
                                    <svg x-show="item.type === 'start'" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86a1 1 0 00-1.5.86z"/>
                                    </svg>
                                    <!-- Transit icon (bus) -->
                                    <svg x-show="item.type === 'transit'" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H6V6h12v5z"/>
                                    </svg>
                                    <!-- Stopover icon (circle) -->
                                    <svg x-show="item.type === 'stopover'" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="12" cy="12" r="6"/>
                                    </svg>
                                    <!-- Activity icon (pin) - default -->
                                    <svg x-show="item.type === 'activity' || !item.type" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/>
                                    </svg>
                                    <!-- End icon (flag) -->
                                    <svg x-show="item.type === 'end'" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M5 21V4h1v1h12l-3 4 3 4H6v8H5z"/>
                                    </svg>
                                </div>
                                
                                <!-- Move Buttons -->
                                <div class="flex flex-col gap-0.5">
                                    <button type="button" @click="moveItineraryItem(index, -1)" :disabled="index === 0" class="p-1 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors disabled:opacity-30 disabled:cursor-not-allowed" title="Move up">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    </button>
                                    <button type="button" @click="moveItineraryItem(index, 1)" :disabled="index === itinerary.length - 1" class="p-1 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors disabled:opacity-30 disabled:cursor-not-allowed" title="Move down">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Form Fields -->
                            <div class="flex-1 space-y-4">
                                <!-- Step Type & Title Row -->
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <!-- Step Type -->
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                            <span class="flex items-center justify-center w-5 h-5 rounded bg-purple-50 text-purple-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                            </span>
                                            Type
                                        </label>
                                        <select :name="'itinerary[' + index + '][type]'" x-model="item.type" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                                            <option value="activity">Activity</option>
                                            <option value="start">Start</option>
                                            <option value="transit">Transit</option>
                                            <option value="stopover">Stopover</option>
                                            <option value="end">End</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Step Title -->
                                    <div class="space-y-2 md:col-span-3">
                                        <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                            <span class="flex items-center justify-center w-5 h-5 rounded bg-amber-50 text-amber-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                            </span>
                                            Step Title <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'itinerary[' + index + '][title]'" x-model="item.title" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none" placeholder="e.g. Hotel Pickup">
                                    </div>
                                </div>
                                
                                <!-- Description -->
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                        <span class="flex items-center justify-center w-5 h-5 rounded bg-blue-50 text-blue-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                            </svg>
                                        </span>
                                        Description
                                    </label>
                                    <textarea :name="'itinerary[' + index + '][description]'" x-model="item.description" rows="2" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none resize-none" placeholder="Describe this step..."></textarea>
                                </div>
                            </div>
                            
                            <!-- Delete Button -->
                            <div class="shrink-0">
                                <button type="button" @click="removeItineraryItem(index)" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Remove step">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                <div x-show="itinerary.length === 0" class="text-center py-12 text-slate-500">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-600 mb-1">No itinerary steps yet</p>
                    <p class="text-xs text-slate-400">Click the button below to add your first step</p>
                </div>
                
                <!-- Add Step Button (at bottom) -->
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <button type="button" @click="addItineraryItem()" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-medium hover:bg-emerald-100 transition-colors border border-dashed border-emerald-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Step
                    </button>
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
                    <!-- Adult Price -->
                    <div class="space-y-2">
                        <label for="base_price_adult" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-emerald-50 text-emerald-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            Adult Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-medium">KES</span>
                            <input type="number" name="base_price_adult" id="base_price_adult" value="{{ old('base_price_adult', $tour->base_price_adult) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" required>
                        </div>
                        @error('base_price_adult')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Child Price -->
                    <div class="space-y-2">
                        <label for="base_price_child" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-blue-50 text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m9 5.197v1"/>
                                </svg>
                            </span>
                            Child Price
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-medium">KES</span>
                            <input type="number" name="base_price_child" id="base_price_child" value="{{ old('base_price_child', $tour->base_price_child ?? 0) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        </div>
                        @error('base_price_child')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Infant Price -->
                    <div class="space-y-2">
                        <label for="base_price_infant" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-pink-50 text-pink-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            Infant Price
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-medium">KES</span>
                            <input type="number" name="base_price_infant" id="base_price_infant" value="{{ old('base_price_infant', $tour->base_price_infant ?? 0) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        </div>
                        <p class="text-xs text-slate-500">Set to 0 for free</p>
                        @error('base_price_infant')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Tour Images -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="font-semibold text-slate-900">Tour Images</h2>
                <p class="text-sm text-slate-500 mt-1">Manage tour images. Click on an image to set it as cover.</p>
            </div>
            <div class="p-6">
                <div x-data="imageEditor()" class="space-y-6">
                    <!-- Existing Images -->
                    @if($tour->images->count() > 0)
                    <div>
                        <h3 class="text-sm font-medium text-slate-700 mb-3">Current Images</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach($tour->images as $image)
                            <div class="relative group aspect-square rounded-lg overflow-hidden border-2 transition-colors"
                                :class="coverImageId === {{ $image->id }} ? 'border-emerald-500 ring-2 ring-emerald-500/20' : (deleteImages.includes({{ $image->id }}) ? 'border-red-500 opacity-50' : 'border-slate-200')">
                                <img src="{{ $image->url }}" class="w-full h-full object-cover" alt="Tour image">

                                <!-- Cover Badge -->
                                <div x-show="coverImageId === {{ $image->id }}" class="absolute top-2 left-2 px-2 py-1 bg-emerald-500 text-white text-xs font-medium rounded">
                                    Cover
                                </div>

                                <!-- Delete Badge -->
                                <div x-show="deleteImages.includes({{ $image->id }})" class="absolute top-2 left-2 px-2 py-1 bg-red-500 text-white text-xs font-medium rounded">
                                    Will be deleted
                                </div>

                                <!-- Overlay Actions -->
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <button type="button" @click="setCover({{ $image->id }})" x-show="coverImageId !== {{ $image->id }} && !deleteImages.includes({{ $image->id }})"
                                        class="p-2 bg-white rounded-full text-slate-700 hover:bg-emerald-50 hover:text-emerald-600" title="Set as cover">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                    <button type="button" @click="toggleDelete({{ $image->id }})"
                                        class="p-2 bg-white rounded-full text-slate-700 hover:bg-red-50 hover:text-red-600" :title="deleteImages.includes({{ $image->id }}) ? 'Restore' : 'Delete'">
                                        <svg x-show="!deleteImages.includes({{ $image->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <svg x-show="deleteImages.includes({{ $image->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Upload New Images -->
                    <div>
                        <h3 class="text-sm font-medium text-slate-700 mb-3">Add New Images</h3>
                        <div
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop($event)"
                            :class="isDragging ? 'border-emerald-500 bg-emerald-50' : 'border-slate-300 hover:border-slate-400'"
                            class="border-2 border-dashed rounded-xl p-6 text-center transition-colors cursor-pointer"
                            @click="$refs.fileInput.click()"
                        >
                            <input
                                type="file"
                                name="images[]"
                                multiple
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="hidden"
                                x-ref="fileInput"
                                @change="handleFiles($event.target.files)"
                            >
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <p class="text-sm text-slate-600">Drop images here or click to upload</p>
                                <p class="text-xs text-slate-500">JPEG, PNG, WebP up to 5MB each</p>
                            </div>
                        </div>

                        <!-- New Image Previews -->
                        <div x-show="previews.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <template x-for="(preview, index) in previews" :key="index">
                                <div class="relative group aspect-square rounded-lg overflow-hidden border-2 border-slate-200">
                                    <img :src="preview.url" class="w-full h-full object-cover">
                                    <div class="absolute top-2 left-2 px-2 py-1 bg-blue-500 text-white text-xs font-medium rounded">New</div>
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button" @click="removeNewImage(index)"
                                            class="p-2 bg-white rounded-full text-slate-700 hover:bg-red-50 hover:text-red-600" title="Remove">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" name="cover_image_id" :value="coverImageId">
                    <template x-for="id in deleteImages" :key="id">
                        <input type="hidden" name="delete_images[]" :value="id">
                    </template>
                </div>

                @error('images')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Publication Status -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="font-semibold text-slate-900">Publication Status</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-start p-4 rounded-lg border-2 cursor-pointer transition-colors" :class="status === 'draft' ? 'border-slate-400 bg-slate-50' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="status" value="draft" x-model="status" class="sr-only">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center" :class="status === 'draft' ? 'bg-slate-600 text-white' : 'bg-slate-200 text-slate-500'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900">Draft</p>
                                <p class="text-sm text-slate-500">Save as draft, not visible to customers</p>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4" x-show="status === 'draft'">
                            <svg class="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </label>
                    <label class="relative flex items-start p-4 rounded-lg border-2 cursor-pointer transition-colors" :class="status === 'published' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:border-slate-300'">
                        <input type="radio" name="status" value="published" x-model="status" class="sr-only">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center" :class="status === 'published' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900">Published</p>
                                <p class="text-sm text-slate-500">Make visible to customers immediately</p>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4" x-show="status === 'published'">
                            <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </label>
                </div>
                @error('status')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Additional Options -->
                <div class="mt-6 pt-6 border-t border-slate-200 space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_daily" id="is_daily" value="1" {{ old('is_daily', $tour->is_daily) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-slate-700">Daily tour (runs every day)</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured', $tour->featured) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-slate-700">Featured tour (show on homepage)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 shadow-sm cursor-pointer">
                Update Tour
            </button>
            <a href="{{ route('console.tours.show', $tour) }}" class="px-6 py-2.5 bg-white text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 border border-slate-300 cursor-pointer">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
@php
    $itineraryData = $tour->itineraryItems->map(fn($item) => [
        'id' => $item->id,
        'type' => $item->type ?? 'activity',
        'title' => $item->title,
        'description' => $item->description
    ]);
@endphp
<script>
function tourEditForm() {
    return {
        status: '{{ old('status', $tour->status) }}',
        itinerary: @json($itineraryData),
        editors: {},
        sortable: null,
        nextId: 1,
        
        init() {
            this.$nextTick(() => {
                this.initEditors();
                this.initSortable();
            });
        },
        
        initSortable() {
            const container = this.$refs.itineraryList;
            if (!container) return;
            
            const self = this;
            this.sortable = new Sortable(container, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    if (evt.oldIndex !== evt.newIndex) {
                        const item = self.itinerary.splice(evt.oldIndex, 1)[0];
                        self.itinerary.splice(evt.newIndex, 0, item);
                    }
                }
            });
        },
        
        initEditors() {
            const toolbarOptions = [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ];
            
            const editorConfigs = [
                { id: 'short_description_editor', input: 'short_description' },
                { id: 'description_editor', input: 'description' },
                { id: 'includes_editor', input: 'includes' },
                { id: 'important_information_editor', input: 'important_information' }
            ];
            
            editorConfigs.forEach(config => {
                const container = document.getElementById(config.id);
                if (container) {
                    const quill = new Quill('#' + config.id, {
                        theme: 'snow',
                        modules: { toolbar: toolbarOptions }
                    });
                    
                    // Set initial content from hidden input
                    const initialContent = document.getElementById(config.input).value;
                    if (initialContent) {
                        quill.root.innerHTML = initialContent;
                    }
                    
                    this.editors[config.input] = quill;
                }
            });
        },
        
        syncEditors() {
            Object.keys(this.editors).forEach(inputId => {
                const quill = this.editors[inputId];
                const input = document.getElementById(inputId);
                if (quill && input) {
                    input.value = quill.root.innerHTML;
                }
            });
        },
        
        addItineraryItem() {
            this.itinerary.push({ id: 'new-' + this.nextId++, type: 'activity', title: '', description: '' });
        },
        
        removeItineraryItem(index) {
            this.itinerary.splice(index, 1);
        },
        
        moveItineraryItem(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= this.itinerary.length) return;
            
            const item = this.itinerary.splice(index, 1)[0];
            this.itinerary.splice(newIndex, 0, item);
        }
    }
}

function imageEditor() {
    return {
        isDragging: false,
        previews: [],
        files: [],
        deleteImages: [],
        coverImageId: {{ $tour->images->where('is_cover', true)->first()?->id ?? $tour->images->first()?->id ?? 'null' }},
        maxSize: 5 * 1024 * 1024, // 5MB

        handleDrop(event) {
            this.isDragging = false;
            const files = event.dataTransfer.files;
            this.handleFiles(files);
        },

        handleFiles(fileList) {
            const newFiles = Array.from(fileList).filter(file => {
                if (!file.type.match(/^image\/(jpeg|png|jpg|webp)$/)) {
                    alert(`${file.name} is not a supported image format`);
                    return false;
                }
                if (file.size > this.maxSize) {
                    alert(`${file.name} is too large (max 5MB)`);
                    return false;
                }
                return true;
            });

            newFiles.forEach(file => {
                this.files.push(file);
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews.push({
                        url: e.target.result,
                        name: file.name
                    });
                };
                reader.readAsDataURL(file);
            });

            this.updateFileInput();
        },

        removeNewImage(index) {
            this.files.splice(index, 1);
            this.previews.splice(index, 1);
            this.updateFileInput();
        },

        setCover(imageId) {
            this.coverImageId = imageId;
        },

        toggleDelete(imageId) {
            const index = this.deleteImages.indexOf(imageId);
            if (index > -1) {
                this.deleteImages.splice(index, 1);
            } else {
                this.deleteImages.push(imageId);
                // If deleting the cover, reset cover to another image
                if (this.coverImageId === imageId) {
                    const remainingImages = @json($tour->images->pluck('id')).filter(id => !this.deleteImages.includes(id));
                    this.coverImageId = remainingImages[0] || null;
                }
            }
        },

        updateFileInput() {
            const dt = new DataTransfer();
            this.files.forEach(file => dt.items.add(file));
            this.$refs.fileInput.files = dt.files;
        }
    }
}
</script>
@endpush
