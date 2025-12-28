@extends('layouts.admin')

@section('title', 'Edit Tour')
@section('page-title', 'Edit Tour')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor { min-height: 120px; }
    .ql-container { font-size: 14px; border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; }
    .ql-toolbar { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; }
    .sortable-ghost { opacity: 0.4; }
    .sortable-drag { opacity: 1; }
    .drag-handle { cursor: grab; }
    .drag-handle:active { cursor: grabbing; }
    
    /* Multi-step form styles */
    .step-indicator {
        transition: all 0.3s ease;
    }
    .step-indicator.active {
        background: linear-gradient(135deg, rgb(16 185 129), rgb(5 150 105));
        color: white;
        transform: scale(1.1);
    }
    .step-indicator.completed {
        background: linear-gradient(135deg, rgb(99 102 241), rgb(79 70 229));
        color: white;
    }
    .step-content {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .step-connector {
        transition: all 0.3s ease;
    }
    .step-connector.completed {
        background: linear-gradient(90deg, rgb(99 102 241), rgb(79 70 229));
    }
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
<div class="max-w-4xl" x-data="multiStepTourForm()" x-init="console.log('🚀 Alpine.js initialized!')">    
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('console.tours.show', $tour) }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Tour
        </a>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex items-center justify-between relative">
            <!-- Step Connectors -->
            <div class="absolute left-0 top-5 w-full h-0.5 bg-slate-200 -z-10"></div>
            <div class="absolute left-2 top-5 h-0.5 bg-linear-to-r from-indigo-500 to-indigo-600 transition-all duration-500 step-connector" 
                 :style="`width: ${(currentStep - 1) / 4 * 98}%`"></div>
            
            <!-- Step 1: Basic Info -->
            <div class="flex flex-col items-center relative">
                <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-slate-300 bg-white"
                     :class="{
                         'active': currentStep === 1,
                         'completed': currentStep > 1
                     }">
                    <span x-show="currentStep <= 1">1</span>
                    <svg x-show="currentStep > 1" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 mt-2">Basic Info</span>
            </div>

            <!-- Step 2: Details -->
            <div class="flex flex-col items-center relative">
                <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-slate-300 bg-white"
                     :class="{
                         'active': currentStep === 2,
                         'completed': currentStep > 2
                     }">
                    <span x-show="currentStep <= 2">2</span>
                    <svg x-show="currentStep > 2" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 mt-2">Details</span>
            </div>

            <!-- Step 3: Pricing -->
            <div class="flex flex-col items-center relative">
                <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-slate-300 bg-white"
                     :class="{
                         'active': currentStep === 3,
                         'completed': currentStep > 3
                     }">
                    <span x-show="currentStep <= 3">3</span>
                    <svg x-show="currentStep > 3" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 mt-2">Pricing</span>
            </div>

            <!-- Step 4: Content -->
            <div class="flex flex-col items-center relative">
                <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-slate-300 bg-white"
                     :class="{
                         'active': currentStep === 4,
                         'completed': currentStep > 4
                     }">
                    <span x-show="currentStep <= 4">4</span>
                    <svg x-show="currentStep > 4" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 mt-2">Content</span>
            </div>

            <!-- Step 5: Complete -->
            <div class="flex flex-col items-center relative">
                <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-slate-300 bg-white"
                     :class="{
                         'active': currentStep === 5,
                         'completed': currentStep > 5
                     }">
                    <span x-show="currentStep <= 5">5</span>
                    <svg x-show="currentStep > 5" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 mt-2">Complete</span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('console.tours.update', $tour) }}" @submit="console.log('🔥 Form submit detected!'); handleFormSubmit($event)" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- General Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-sm font-medium text-red-800 mb-2">Please fix the following errors:</h3>
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Step 1: Basic Information -->
        <div class="step-content" x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-md bg-emerald-50 text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        Basic Information
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Start with the essential details about your tour</p>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Tour Title and Short Description - Top Priority -->
                    <div class="space-y-6">
                        <!-- Title Input -->
                        <div class="space-y-2">
                            <label for="title" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 text-amber-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </span>
                                Tour Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $tour->title) }}" placeholder="Enter an attractive tour title" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none" required>
                            @error('title')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Short Description -->
                        <div class="space-y-2">
                            <label for="short_description" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-teal-50 text-teal-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </span>
                                Short Description <span class="text-red-500">*</span>
                            </label>
                            <div id="short_description_editor" class="bg-white rounded-lg border border-slate-300"></div>
                            <input type="hidden" name="short_description" id="short_description" value="{{ old('short_description', $tour->short_description) }}">
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-slate-500">Brief overview that appears in tour listings (required)</p>
                                <p id="short_description_char_count" class="text-xs text-slate-500">0 / 600 characters</p>
                            </div>
                            @error('short_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Location and Category -->
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
                                <select name="location_id" id="location_id" class="w-full appearance-none rounded-lg border border-slate-300 bg-white px-4 py-2.5 pr-10 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none" required>
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
                                Category <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="tour_category_id" id="tour_category_id" class="w-full appearance-none rounded-lg border border-slate-300 bg-white px-4 py-2.5 pr-10 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none" x-model="selectedCategory" @change="updateDurationField()" required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-duration-type="{{ $category->duration_type ?? '' }}" {{ old('tour_category_id', $tour->tour_category_id) == $category->id ? 'selected' : '' }}>
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

                        <!-- Duration Field -->
                        <div class="space-y-2">
                            <label for="duration_text" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-purple-50 text-purple-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="8" stroke-width="2"/>
                                        <path stroke-linecap="round" stroke-width="2" d="M12 8v4l2.5 1.5"/>
                                    </svg>
                                </span>
                                Duration <span class="text-red-500">*</span>
                            </label>
                            
                            <!-- Dynamic Duration Input -->
                            <div x-show="durationType === 'hourly'">
                                <select name="duration_text" id="duration_text_hourly" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                    <option value="">Select duration</option>
                                    <option value="1" @if(old('duration_text', $tour->duration_text) === '1') selected @endif>1 hour</option>
                                    <option value="2" @if(old('duration_text', $tour->duration_text) === '2') selected @endif>2 hours</option>
                                    <option value="3" @if(old('duration_text', $tour->duration_text) === '3') selected @endif>3 hours</option>
                                    <option value="4" @if(old('duration_text', $tour->duration_text) === '4') selected @endif>4 hours</option>
                                    <option value="5" @if(old('duration_text', $tour->duration_text) === '5') selected @endif>5 hours</option>
                                </select>
                            </div>
                            
                            <div x-show="durationType === 'half_day'">
                                <input type="text" name="duration_text" id="duration_text_half_day" value="6 hours" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 shadow-sm">
                                <p class="text-xs text-slate-500">Half day tours are typically 6 hours</p>
                            </div>
                            
                            <div x-show="durationType === 'full_day'">
                                <input type="text" name="duration_text" id="duration_text_full_day" value="Full day" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 shadow-sm">
                                <p class="text-xs text-slate-500">Full day tours typically last 8-10 hours</p>
                            </div>
                            
                            <div x-show="durationType === 'multiple_days'">
                                <div class="flex gap-2">
                                    @php
                                        $durationParts = explode(' ', old('duration_text', $tour->duration_text) ?? '2 days');
                                        $durationDays = is_numeric($durationParts[0] ?? '') ? $durationParts[0] : 2;
                                    @endphp
                                    <input type="number" name="duration_days" id="duration_days" value="{{ $durationDays }}" min="2" max="30" class="flex-1 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                    <input type="text" name="duration_text" id="duration_text_multiple" value="{{ old('duration_text', $tour->duration_text) ?? '2 days' }}" readonly class="flex-1 rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 shadow-sm">
                                </div>
                                <p class="text-xs text-slate-500">Specify the number of days</p>
                            </div>
                            
                            <div x-show="!durationType">
                                <input type="text" name="duration_text" id="duration_text_default" value="{{ old('duration_text', $tour->duration_text) }}" placeholder="e.g. 4 hours, Full day" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                            </div>
                            
                            @error('duration_text')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Tour Details -->
        <div class="step-content" x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        Tour Details
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Schedule, capacity, and logistics information</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Number of People -->
                        <div class="space-y-2">
                            <label for="no_of_people" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-indigo-50 text-indigo-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m9 5.197v1"/>
                                    </svg>
                                </span>
                                Group Size
                            </label>
                            
                            <!-- Group Size Selection -->
                            <div class="space-y-3">
                                @php
                                    $groupSizeType = old('group_size_type');
                                    if (!$groupSizeType && $tour->no_of_people) {
                                        if ($tour->no_of_people <= 3) $groupSizeType = '3';
                                        elseif ($tour->no_of_people <= 8) $groupSizeType = '8';
                                        else $groupSizeType = 'custom';
                                    }
                                @endphp
                                <select name="group_size_type" id="group_size_type" onchange="toggleGroupSizeInput()" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                    <option value="">Select group size</option>
                                    <option value="3" {{ $groupSizeType == '3' ? 'selected' : '' }}>1–3 participants</option>
                                    <option value="8" {{ $groupSizeType == '8' ? 'selected' : '' }}>4–8 participants</option>
                                    <option value="custom" {{ $groupSizeType == 'custom' ? 'selected' : '' }}>Custom group size</option>
                                </select>
                                
                                <!-- Custom Group Size Input (hidden by default) -->
                                <div id="custom_group_size_div" class="{{ $groupSizeType != 'custom' ? 'hidden' : '' }}">
                                    <div class="flex items-center gap-2">
                                        <input type="number" 
                                            name="no_of_people" 
                                            id="no_of_people" 
                                            min="1" 
                                            max="100" 
                                            value="{{ old('no_of_people', $tour->no_of_people) }}"
                                            placeholder="Enter max participants"
                                            class="flex-1 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                        <span class="text-sm text-slate-500">max participants</span>
                                    </div>
                                    <p class="text-xs text-slate-500">Enter maximum number of participants (1-100)</p>
                                </div>
                            </div>
                            
                            <p class="text-xs text-slate-500">This value determines tour capacity and booking availability</p>
                            @error('no_of_people')
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
                            <select name="starts_at_time" id="starts_at_time" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                <option value="">Select start time</option>
                                <option value="09:00" {{ old('starts_at_time', $tour->starts_at_time) == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                <option value="15:00" {{ old('starts_at_time', $tour->starts_at_time) == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                            </select>
                            @error('starts_at_time')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Cut Off Time -->
                        <div class="space-y-2">
                            <label for="cut_off_time" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-orange-50 text-orange-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </span>
                                Cut-off Time
                            </label>
                            <select name="cut_off_time" id="cut_off_time" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                <option value="">Select cut-off time</option>
                                <option value="5" {{ old('cut_off_time', $tour->cut_off_time) == '5' ? 'selected' : '' }}>5 minutes</option>
                                <option value="10" {{ old('cut_off_time', $tour->cut_off_time) == '10' ? 'selected' : '' }}>10 minutes</option>
                                <option value="15" {{ old('cut_off_time', $tour->cut_off_time) == '15' ? 'selected' : '' }}>15 minutes</option>
                                <option value="30" {{ old('cut_off_time', $tour->cut_off_time) == '30' ? 'selected' : '' }}>30 minutes</option>
                                <option value="45" {{ old('cut_off_time', $tour->cut_off_time) == '45' ? 'selected' : '' }}>45 minutes</option>
                                <option value="60" {{ old('cut_off_time', $tour->cut_off_time) == '60' ? 'selected' : '' }}>60 minutes</option>
                            </select>
                            <p class="text-xs text-slate-500">Latest time participants must arrive before the tour begins</p>
                            @error('cut_off_time')
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
                            <input type="text" name="meeting_point" id="meeting_point" value="{{ old('meeting_point', $tour->meeting_point ?? 'CJ\'s Koinange') }}" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 shadow-sm">
                            <p class="text-xs text-slate-500">Fixed meeting location for all tours</p>
                            @error('meeting_point')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Pricing -->
        <div class="step-content" x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-md bg-green-50 text-green-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        Pricing (USD)
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Set competitive prices for different customer segments</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-medium">USD</span>
                                <input type="number" name="base_price_adult" id="base_price_adult" value="{{ old('base_price_adult', $tour->base_price_adult) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" required>
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
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-medium">USD</span>
                                <input type="number" name="base_price_child" id="base_price_child" value="{{ old('base_price_child', $tour->base_price_child ?? 0) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            </div>
                            @error('base_price_child')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Senior Price -->
                        <div class="space-y-2">
                            <label for="base_price_senior" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-purple-50 text-purple-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                Senior Price
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-medium">USD</span>
                                <input type="number" name="base_price_senior" id="base_price_senior" value="{{ old('base_price_senior', $tour->base_price_senior ?? 0) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            </div>
                            @error('base_price_senior')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Youth Price -->
                        <div class="space-y-2">
                            <label for="base_price_youth" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-cyan-50 text-cyan-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                Youth Price
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-medium">USD</span>
                                <input type="number" name="base_price_youth" id="base_price_youth" value="{{ old('base_price_youth', $tour->base_price_youth ?? 0) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            </div>
                            @error('base_price_youth')
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
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-medium">USD</span>
                                <input type="number" name="base_price_infant" id="base_price_infant" value="{{ old('base_price_infant', $tour->base_price_infant ?? 0) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
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
        </div>

        <!-- Step 4: Content -->
        <div class="step-content" x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-md bg-purple-50 text-purple-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        Content Details
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Rich content descriptions and important information</p>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Full Description -->
                    <div class="space-y-2">
                        <label for="description" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-indigo-50 text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                            Full Description <span class="text-red-500">*</span>
                        </label>
                        <div id="description_editor" class="bg-white rounded-lg border border-slate-300"></div>
                        <input type="hidden" name="description" id="description" value="{{ old('description', $tour->description) }}">
                        <p class="text-xs text-slate-500">Detailed tour description, itinerary highlights, and what makes this experience special</p>
                        @error('description')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- What's Included -->
                    <div class="space-y-2">
                        <label for="included" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-green-50 text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            What's Included <span class="text-red-500">*</span>
                        </label>
                        <div id="included_editor" class="bg-white rounded-lg border border-slate-300"></div>
                        <input type="hidden" name="included" id="included" value="{{ old('included', $tour->included) }}">
                        <p class="text-xs text-slate-500">List all items, services, and amenities included in the tour price</p>
                        @error('included')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- What's Excluded -->
                    <div class="space-y-2">
                        <label for="excluded" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-red-50 text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </span>
                            What's Excluded
                        </label>
                        <div id="excluded_editor" class="bg-white rounded-lg border border-slate-300"></div>
                        <input type="hidden" name="excluded" id="excluded" value="{{ old('excluded', $tour->excluded) }}">
                        <p class="text-xs text-slate-500">Clearly state what participants should expect to pay for separately</p>
                        @error('excluded')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Important Information -->
                    <div class="space-y-2">
                        <label for="important_information" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-amber-50 text-amber-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </span>
                            Important Information
                        </label>
                        <div id="important_information_editor" class="bg-white rounded-lg border border-slate-300"></div>
                        <input type="hidden" name="important_information" id="important_information" value="{{ old('important_information', $tour->important_information) }}">
                        <p class="text-xs text-slate-500">Health requirements, physical demands, weather considerations, and other essential details</p>
                        @error('important_information')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cancellation Policy -->
                    <div class="space-y-2">
                        <label for="cancellation_policy" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <span class="flex items-center justify-center w-6 h-6 rounded-md bg-orange-50 text-orange-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </span>
                            Cancellation Policy
                        </label>
                        <div class="flex gap-2 mb-2">
                            <button type="button" onclick="applyCancellationPolicyTemplate('standard')" class="px-3 py-1 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md transition-colors">
                                Apply Standard Template
                            </button>
                            <button type="button" onclick="applyCancellationPolicyTemplate('strict')" class="px-3 py-1 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md transition-colors">
                                Apply Strict Template
                            </button>
                            <button type="button" onclick="applyCancellationPolicyTemplate('flexible')" class="px-3 py-1 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md transition-colors">
                                Apply Flexible Template
                            </button>
                        </div>
                        <div id="cancellation_policy_editor" class="bg-white rounded-lg border border-slate-300"></div>
                        <input type="hidden" name="cancellation_policy" id="cancellation_policy" value="{{ old('cancellation_policy', $tour->cancellation_policy) }}">
                        <p class="text-xs text-slate-500">Refund policies, cancellation deadlines, and rescheduling options</p>
                        @error('cancellation_policy')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 5: Review & Complete -->
        <div class="step-content" x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-md bg-indigo-50 text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        Review & Complete
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Final review and publication settings</p>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Publication Status -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-slate-900">Publication Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex items-start p-4 rounded-lg border-2 cursor-pointer transition-colors" :class="publicationStatus === 'draft' ? 'border-slate-400 bg-slate-50' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="status" value="draft" x-model="publicationStatus" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center" :class="publicationStatus === 'draft' ? 'bg-slate-600 text-white' : 'bg-slate-200 text-slate-500'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">Save as Draft</p>
                                        <p class="text-sm text-slate-500">Not visible to customers</p>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4" x-show="publicationStatus === 'draft'">
                                    <svg class="w-5 h-5 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </label>
                            <label class="relative flex items-start p-4 rounded-lg border-2 cursor-pointer transition-colors" :class="publicationStatus === 'published' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="status" value="published" x-model="publicationStatus" class="sr-only">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center" :class="publicationStatus === 'published' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">Publish Now</p>
                                        <p class="text-sm text-slate-500">Live and available for booking</p>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4" x-show="publicationStatus === 'published'">
                                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Tour Options -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-slate-900">Tour Options</h3>
                        <div class="space-y-3">
                            <!-- Daily Tour -->
                            <label class="flex items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-slate-300 cursor-pointer transition-colors">
                                <input type="checkbox" name="is_daily" value="1" {{ old('is_daily', $tour->is_daily) ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 focus:ring-2">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0 w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">Daily Tour</p>
                                        <p class="text-sm text-slate-500">This tour runs every day</p>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Featured Tour -->
                            <label class="flex items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-slate-300 cursor-pointer transition-colors">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $tour->is_featured) ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 focus:ring-2">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0 w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">Featured Tour</p>
                                        <p class="text-sm text-slate-500">Highlight this tour on the homepage</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Management Links -->
                    <div class="border-t pt-6">
                        <div class="bg-slate-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-slate-900 mb-3">Tour Management</h3>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('console.tours.itinerary.index', $tour) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Manage Itinerary
                                </a>
                                <a href="{{ route('console.tours.multimedia.index', $tour) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Manage Images
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex items-center justify-between bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <button type="button" @click="previousStep()" x-show="currentStep > 1" class="inline-flex items-center gap-2 px-4 py-2.5 text-slate-700 bg-white border border-slate-300 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous
            </button>
            <div x-show="currentStep === 1" class="w-4"></div>
            
            <button type="button" @click="nextStep()" x-show="currentStep < 5" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors cursor-pointer">
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            
            <button type="submit" x-show="currentStep === 5" class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Tour
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
// Quill editors
let shortDescriptionEditor, descriptionEditor, includedEditor, excludedEditor, importantInformationEditor, cancellationPolicyEditor;

// Initialize Quill editors
document.addEventListener('DOMContentLoaded', function() {
    // Common Quill configuration
    const quillConfig = {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                ['link', 'blockquote'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['clean']
            ]
        },
        placeholder: 'Enter content...'
    };

    // Initialize editors
    shortDescriptionEditor = new Quill('#short_description_editor', {
        ...quillConfig,
        placeholder: 'Brief overview of your tour (max 600 characters)...'
    });

    descriptionEditor = new Quill('#description_editor', {
        ...quillConfig,
        placeholder: 'Detailed tour description, itinerary highlights, and special features...'
    });

    includedEditor = new Quill('#included_editor', {
        ...quillConfig,
        placeholder: 'List all items, services, and amenities included in the tour price...'
    });

    excludedEditor = new Quill('#excluded_editor', {
        ...quillConfig,
        placeholder: 'Clearly state what participants should expect to pay for separately...'
    });

    importantInformationEditor = new Quill('#important_information_editor', {
        ...quillConfig,
        placeholder: 'Health requirements, physical demands, weather considerations, and other essential details...'
    });

    cancellationPolicyEditor = new Quill('#cancellation_policy_editor', {
        ...quillConfig,
        placeholder: 'Refund policies, cancellation deadlines, and rescheduling options...'
    });

    // Set initial content from hidden inputs
    const shortDescriptionContent = document.getElementById('short_description').value;
    if (shortDescriptionContent) {
        shortDescriptionEditor.root.innerHTML = shortDescriptionContent;
    }

    const descriptionContent = document.getElementById('description').value;
    if (descriptionContent) {
        descriptionEditor.root.innerHTML = descriptionContent;
    }

    const includedContent = document.getElementById('included').value;
    if (includedContent) {
        includedEditor.root.innerHTML = includedContent;
    }

    const excludedContent = document.getElementById('excluded').value;
    if (excludedContent) {
        excludedEditor.root.innerHTML = excludedContent;
    }

    const importantInformationContent = document.getElementById('important_information').value;
    if (importantInformationContent) {
        importantInformationEditor.root.innerHTML = importantInformationContent;
    }

    const cancellationPolicyContent = document.getElementById('cancellation_policy').value;
    if (cancellationPolicyContent) {
        cancellationPolicyEditor.root.innerHTML = cancellationPolicyContent;
    }

    // Character counter for short description
    updateCharCount();
    shortDescriptionEditor.on('text-change', updateCharCount);
});

function updateCharCount() {
    const text = shortDescriptionEditor.getText();
    const charCount = text.length - 1; // Subtract 1 for the trailing newline
    const counter = document.getElementById('short_description_char_count');
    counter.textContent = `${charCount} / 600 characters`;
    
    if (charCount > 600) {
        counter.classList.add('text-red-500');
        counter.classList.remove('text-slate-500');
    } else {
        counter.classList.remove('text-red-500');
        counter.classList.add('text-slate-500');
    }
}

// Cancellation policy templates
function applyCancellationPolicyTemplate(type) {
    let template = '';
    
    switch(type) {
        case 'standard':
            template = '<h3>Cancellation Policy</h3><ul><li><strong>24+ hours before departure:</strong> Full refund</li><li><strong>12-24 hours before departure:</strong> 50% refund</li><li><strong>Less than 12 hours before departure:</strong> No refund</li><li><strong>No-show:</strong> No refund</li></ul><p><em>Refunds will be processed within 5-7 business days.</em></p>';
            break;
        case 'strict':
            template = '<h3>Cancellation Policy</h3><ul><li><strong>48+ hours before departure:</strong> Full refund</li><li><strong>24-48 hours before departure:</strong> 25% refund</li><li><strong>Less than 24 hours before departure:</strong> No refund</li><li><strong>No-show:</strong> No refund</li></ul><p><em>All bookings are final and non-transferable.</em></p>';
            break;
        case 'flexible':
            template = '<h3>Cancellation Policy</h3><ul><li><strong>12+ hours before departure:</strong> Full refund</li><li><strong>6-12 hours before departure:</strong> 75% refund</li><li><strong>Less than 6 hours before departure:</strong> 50% refund</li><li><strong>No-show:</strong> 25% refund</li></ul><p><em>We understand plans change. Contact us for rescheduling options.</em></p>';
            break;
    }
    
    cancellationPolicyEditor.root.innerHTML = template;
}

// Group size toggle
function toggleGroupSizeInput() {
    const groupSizeSelect = document.getElementById('group_size_type');
    const customDiv = document.getElementById('custom_group_size_div');
    const customInput = document.getElementById('no_of_people');
    
    if (groupSizeSelect.value === 'custom') {
        customDiv.classList.remove('hidden');
        customInput.required = true;
    } else {
        customDiv.classList.add('hidden');
        customInput.required = false;
        
        // Set the no_of_people value based on selection
        if (groupSizeSelect.value === '3') {
            customInput.value = '3';
        } else if (groupSizeSelect.value === '8') {
            customInput.value = '8';
        }
    }
}

// Alpine.js component
function multiStepTourForm() {
    return {
        currentStep: 1,
        totalSteps: 5,
        selectedCategory: "{{ old('tour_category_id', $tour->tour_category_id) }}",
        durationType: '',
        publicationStatus: "{{ old('status', $tour->status ?? 'draft') }}",
        
        init() {
            console.log('🚀 Multi-step form initialized!');
            this.updateDurationField();
            
            // Initialize group size based on current value
            this.initGroupSize();
        },
        
        initGroupSize() {
            // Set initial group size type based on existing value
            const noOfPeople = '{{ $tour->no_of_people }}';
            if (noOfPeople) {
                const people = parseInt(noOfPeople);
                if (people <= 3) {
                    document.getElementById('group_size_type').value = '3';
                } else if (people <= 8) {
                    document.getElementById('group_size_type').value = '8';
                } else {
                    document.getElementById('group_size_type').value = 'custom';
                }
                toggleGroupSizeInput();
            }
        },
        
        updateDurationField() {
            const select = document.getElementById('tour_category_id');
            const selectedOption = select.options[select.selectedIndex];
            this.durationType = selectedOption.getAttribute('data-duration-type') || '';
            console.log('Duration type updated:', this.durationType);
            
            // Update multiple days input if needed
            if (this.durationType === 'multiple_days') {
                const daysInput = document.getElementById('duration_days');
                const textInput = document.getElementById('duration_text_multiple');
                
                daysInput.addEventListener('input', function() {
                    const days = this.value || '2';
                    textInput.value = days + ' days';
                });
            }
        },
        
        nextStep() {
            console.log('🔥 Next step clicked! Current step:', this.currentStep);
            
            // Validate current step before proceeding
            if (this.validateCurrentStep()) {
                if (this.currentStep < this.totalSteps) {
                    this.currentStep++;
                    console.log('✅ Advanced to step:', this.currentStep);
                    
                    // Scroll to top of form
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } else {
                console.log('❌ Validation failed for step:', this.currentStep);
            }
        },
        
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        
        validateCurrentStep() {
            // Basic validation - you can expand this as needed
            let isValid = true;
            
            if (this.currentStep === 1) {
                const title = document.getElementById('title').value;
                const location = document.getElementById('location_id').value;
                const category = document.getElementById('tour_category_id').value;
                
                if (!title || !location || !category) {
                    isValid = false;
                    alert('Please fill in all required fields in Basic Information.');
                }
            } else if (this.currentStep === 3) {
                const adultPrice = document.getElementById('base_price_adult').value;
                
                if (!adultPrice || adultPrice <= 0) {
                    isValid = false;
                    alert('Please enter a valid adult price.');
                }
            } else if (this.currentStep === 4) {
                const description = descriptionEditor.getText().trim();
                const included = includedEditor.getText().trim();
                
                if (!description || !included) {
                    isValid = false;
                    alert('Please fill in the required content fields.');
                }
            }
            
            return isValid;
        },
        
        handleFormSubmit(event) {
            console.log('🔥 Form submit detected!');
            
            // Sync all Quill editor content to hidden inputs
            document.getElementById('short_description').value = shortDescriptionEditor.root.innerHTML;
            document.getElementById('description').value = descriptionEditor.root.innerHTML;
            document.getElementById('included').value = includedEditor.root.innerHTML;
            document.getElementById('excluded').value = excludedEditor.root.innerHTML;
            document.getElementById('important_information').value = importantInformationEditor.root.innerHTML;
            document.getElementById('cancellation_policy').value = cancellationPolicyEditor.root.innerHTML;
            
            // Validate short description length
            const shortDescText = shortDescriptionEditor.getText();
            if (shortDescText.length - 1 > 600) {
                event.preventDefault();
                alert('Short description must be 600 characters or less.');
                return false;
            }
            
            console.log('✅ Form submitted successfully!');
            return true;
        }
    }
}
</script>
@endpush

@endsection
