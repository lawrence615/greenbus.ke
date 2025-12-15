@extends('layouts.admin')

@section('title', 'Add Tour')
@section('page-title', 'Add Tour')

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

@section('content')
<div class="max-w-4xl" x-data="multiStepTourForm()" x-init="console.log('🚀 Alpine.js initialized!')">    
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('console.tours.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Tours
        </a>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex items-center justify-between relative">
            <!-- Step Connectors -->
            <div class="absolute left-0 top-5 w-full h-0.5 bg-slate-200 -z-10"></div>
            <div class="absolute left-0 top-5 h-0.5 bg-linear-to-r from-indigo-500 to-indigo-600 transition-all duration-500 step-connector" 
                 :style="`width: ${(currentStep - 1) / 4 * 100}%`"></div>
            
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

            <!-- Step 4: Itinerary -->
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
                <span class="text-xs font-medium text-slate-600 mt-2">Itinerary</span>
            </div>

            <!-- Step 5: Content -->
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
                <span class="text-xs font-medium text-slate-600 mt-2">Content</span>
            </div>

            <!-- Step 6: Complete -->
            <div class="flex flex-col items-center relative">
                <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold border-2 border-slate-300 bg-white"
                     :class="{
                         'active': currentStep === 6,
                         'completed': currentStep > 6
                     }">
                    <span x-show="currentStep <= 6">6</span>
                    <svg x-show="currentStep > 6" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 mt-2">Complete</span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('console.tours.store') }}" @submit="console.log('🔥 Form submit detected!'); handleFormSubmit($event)" enctype="multipart/form-data">
        @csrf
        
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
                            <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Enter an attractive tour title" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none" required>
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
                            <input type="hidden" name="short_description" id="short_description" value="{{ old('short_description') }}">
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-slate-500">Brief overview that appears in tour listings (required)</p>
                                <p id="short_description_char_count" class="text-xs text-slate-500">0 / 250 characters</p>
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
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
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
                                    <option value="{{ $category->id }}" data-duration-type="{{ $category->duration_type ?? '' }}" {{ old('tour_category_id') == $category->id ? 'selected' : '' }}>
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
                                    <option value="1" @if(old('duration_text') === '1') selected @endif>1 hour</option>
                                    <option value="2" @if(old('duration_text') === '2') selected @endif>2 hours</option>
                                    <option value="3" @if(old('duration_text') === '3') selected @endif>3 hours</option>
                                    <option value="4" @if(old('duration_text') === '4') selected @endif>4 hours</option>
                                    <option value="5" @if(old('duration_text') === '5') selected @endif>5 hours</option>
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
                                    <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days') ?? 2 }}" min="2" max="30" class="flex-1 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                    <input type="text" name="duration_text" id="duration_text_multiple" value="2 days" readonly class="flex-1 rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 shadow-sm">
                                </div>
                                <p class="text-xs text-slate-500">Specify the number of days</p>
                            </div>
                            
                            <div x-show="!durationType">
                                <input type="text" name="duration_text" id="duration_text_default" value="{{ old('duration_text') }}" placeholder="e.g. 4 hours, Full day" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
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
                                <select name="group_size_type" id="group_size_type" onchange="toggleGroupSizeInput()" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                    <option value="">Select group size</option>
                                    <option value="3" {{ old('group_size_type') == '3' ? 'selected' : '' }}>1–3 participants</option>
                                    <option value="8" {{ old('group_size_type') == '8' ? 'selected' : '' }}>4–8 participants</option>
                                    <option value="custom" {{ old('group_size_type') == 'custom' ? 'selected' : '' }}>Custom group size</option>
                                </select>
                                
                                <!-- Custom Group Size Input (hidden by default) -->
                                <div id="custom_group_size_div" class="hidden">
                                    <div class="flex items-center gap-2">
                                        <input type="number" 
                                            name="no_of_people" 
                                            id="no_of_people" 
                                            min="1" 
                                            max="100" 
                                            value="{{ old('no_of_people') }}"
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
                                <option value="09:00" {{ old('starts_at_time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                                <option value="15:00" {{ old('starts_at_time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
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
                                <option value="5" {{ old('cut_off_time') == '5' ? 'selected' : '' }}>5 minutes</option>
                                <option value="10" {{ old('cut_off_time') == '10' ? 'selected' : '' }}>10 minutes</option>
                                <option value="15" {{ old('cut_off_time') == '15' ? 'selected' : '' }}>15 minutes</option>
                                <option value="30" {{ old('cut_off_time') == '30' ? 'selected' : '' }}>30 minutes</option>
                                <option value="45" {{ old('cut_off_time') == '45' ? 'selected' : '' }}>45 minutes</option>
                                <option value="60" {{ old('cut_off_time') == '60' ? 'selected' : '' }}>60 minutes</option>
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
                            <input type="text" name="meeting_point" id="meeting_point" value="CJ's Koinange" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 shadow-sm">
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
                        Pricing (KES)
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
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-medium">KES</span>
                                <input type="number" name="base_price_adult" id="base_price_adult" value="{{ old('base_price_adult') }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" required>
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
                                <input type="number" name="base_price_child" id="base_price_child" value="{{ old('base_price_child', 0) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
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
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-sm font-medium">KES</span>
                                <input type="number" name="base_price_senior" id="base_price_senior" value="{{ old('base_price_senior', 0) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            </div>
                            @error('base_price_senior')
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
                                <input type="number" name="base_price_infant" id="base_price_infant" value="{{ old('base_price_infant', 0) }}" min="0" step="1" class="w-full rounded-lg border border-slate-300 bg-white pl-12 pr-4 py-2.5 text-sm text-slate-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
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

        <!-- Step 5: Content -->
        <div class="step-content" x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-md bg-purple-50 text-purple-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </span>
                        Tour Content
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Detailed descriptions, inclusions, and policies</p>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Full Description -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Full Description</label>
                        <div id="description_editor" class="bg-white rounded-lg border border-slate-300"></div>
                        <input type="hidden" name="description" id="description" value="{{ old('description') }}">
                        <p class="text-xs text-slate-500">Detailed tour description for customers</p>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- What's Included -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">What's Included</label>
                        <div id="includes_editor" class="bg-white rounded-lg border border-slate-300"></div>
                        <input type="hidden" name="included" id="included" value="{{ old('included') }}">
                        <p class="text-xs text-slate-500">List everything included in the tour price</p>
                        @error('included')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- What's Excluded -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">What's Excluded</label>
                        <div id="excluded_editor" class="bg-white rounded-lg border border-slate-300"></div>
                        <input type="hidden" name="excluded" id="excluded" value="{{ old('excluded') }}">
                        <p class="text-xs text-slate-500">List items not included in the tour price</p>
                        @error('excluded')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Important Information -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Important Information</label>
                        <div id="important_information_editor" class="bg-white rounded-lg border border-slate-300"></div>
                        <input type="hidden" name="important_information" id="important_information" value="{{ old('important_information') }}">
                        <p class="text-xs text-slate-500">Special requirements, recommendations, etc.</p>
                        @error('important_information')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cancellation Policy -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Cancellation Policy</label>
                        <div id="cancellation_policy_editor" class="bg-white rounded-lg border border-slate-300"></div>
                        <input type="hidden" name="cancellation_policy" id="cancellation_policy" value="{{ old('cancellation_policy') }}">
                        <p class="text-xs text-slate-500">Refund and cancellation rules</p>
                        @error('cancellation_policy')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Itinerary -->
        <div class="step-content" x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-md bg-orange-50 text-orange-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </span>
                        Tour Itinerary
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Add detailed itinerary items for your tour</p>
                </div>
                <div class="p-6">
                    <div id="itinerary-container" class="space-y-4">
                        <!-- Itinerary items will be dynamically added here -->
                    </div>
                    
                    <div class="mt-6">
                        <button type="button" onclick="addItineraryItem()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Itinerary Item
                        </button>
                    </div>
                    
                    <input type="hidden" name="itinerary" id="itinerary-data">
                </div>
            </div>
        </div>

        <!-- Step 6: Review & Complete -->
        <div class="step-content" x-show="currentStep === 6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900 flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-md bg-green-50 text-green-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        Review & Complete
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Finalize your tour settings and publish</p>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Tour Images -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-slate-900">Tour Images</h3>
                        <div x-data="imageUploader()" class="space-y-4">
                            <!-- Drop Zone -->
                            <div
                                @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                @drop.prevent="handleDrop($event)"
                                :class="isDragging ? 'border-emerald-500 bg-emerald-50' : 'border-slate-300 hover:border-slate-400'"
                                class="border-2 border-dashed rounded-xl p-8 text-center transition-colors cursor-pointer"
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
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Drop images here or click to upload</p>
                                        <p class="text-xs text-slate-500 mt-1">JPEG, PNG, WebP up to 5MB each (max 10 images)</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Previews -->
                            <div x-show="previews.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="relative group aspect-square rounded-lg overflow-hidden border-2 transition-colors"
                                        :class="coverIndex === index ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200'">
                                        <img :src="preview.url" class="w-full h-full object-cover">

                                        <!-- Cover Badge -->
                                        <div x-show="coverIndex === index" class="absolute top-2 left-2 px-2 py-1 bg-emerald-500 text-white text-xs font-medium rounded">
                                            Cover
                                        </div>

                                        <!-- Overlay Actions -->
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                            <button type="button" @click="setCover(index)" x-show="coverIndex !== index"
                                                class="p-2 bg-white rounded-full text-slate-700 hover:bg-emerald-50 hover:text-emerald-600" title="Set as cover">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                            <button type="button" @click="removeImage(index)"
                                                class="p-2 bg-white rounded-full text-slate-700 hover:bg-red-50 hover:text-red-600" title="Remove">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Hidden input for cover index -->
                            <input type="hidden" name="cover_image_index" :value="coverIndex">
                        </div>

                        @error('images')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publication Status -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-slate-900">Publication Status</h3>
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
                                        <p class="font-medium text-slate-900">Save as Draft</p>
                                        <p class="text-sm text-slate-500">Not visible to customers</p>
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
                                        <p class="font-medium text-slate-900">Publish Now</p>
                                        <p class="text-sm text-slate-500">Visible to customers immediately</p>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4" x-show="status === 'published'">
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
                                <input type="checkbox" name="is_daily" value="1" {{ old('is_daily') ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 focus:ring-2">
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
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 focus:ring-2">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0 w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">Featured Tour</p>
                                        <p class="text-sm text-slate-500">Show this tour on the homepage</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between items-center mt-8">
            <!-- Previous Button -->
            <button type="button" @click="previousStep()" x-show="currentStep > 1" 
                    class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-300 text-slate-700 rounded-lg font-medium hover:bg-slate-50 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous
            </button>
            <div x-show="currentStep === 1" class="w-6"></div> <!-- Spacer -->

            <!-- Next/Submit Button -->
            <button type="button" @click="nextStep()" x-show="currentStep < 6"
                    class="flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors cursor-pointer">
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <button type="submit" x-show="currentStep === 6"
                    class="flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Create Tour
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
function multiStepTourForm() {
    return {
        currentStep: 1,
        status: @json(old('status', 'draft')),
        editors: {},
        selectedCategory: @json(old('tour_category_id')),
        durationType: '',
        validationErrors: {},
        
        init() {
            this.$nextTick(() => {
                this.initEditors();
                this.updateDurationField();
            });
        },
        
        // Clear all validation errors
        clearValidationErrors() {
            this.validationErrors = {};
            // Remove red borders from all fields
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
            });
            // Remove all error messages
            document.querySelectorAll('[id^="error-"]').forEach(el => {
                el.remove();
            });
        },
        
        // Show validation error for a specific field
        showFieldError(fieldId, message = 'This field is required') {
            const field = document.getElementById(fieldId);
            if (field) {
                field.classList.add('border-red-500');
                setTimeout(() => field.classList.remove('border-red-500'), 3000);
                
                // Show error message below field
                this.showErrorMessage(fieldId, message);
            }
            this.validationErrors[fieldId] = message;
        },
        
        // Show error message below a field
        showErrorMessage(fieldId, message) {
            // Remove existing error message for this field
            const existingError = document.getElementById(`error-${fieldId}`);
            if (existingError) {
                existingError.remove();
            }
            
            // Find the field container
            const field = document.getElementById(fieldId);
            if (!field) return;
            
            // Create error message element
            const errorDiv = document.createElement('div');
            errorDiv.id = `error-${fieldId}`;
            errorDiv.className = 'text-sm text-red-600 flex items-center gap-1 mt-1';
            errorDiv.innerHTML = `
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                ${message}
            `;
            
            // Insert error message after the field's parent container
            let container = field.closest('.space-y-2');
            if (container) {
                container.appendChild(errorDiv);
            }
            
            // Remove error message after 5 seconds
            setTimeout(() => {
                const errorElement = document.getElementById(`error-${fieldId}`);
                if (errorElement) {
                    errorElement.remove();
                }
            }, 5000);
        },
        
        // Validate Step 1
        validateStep1() {
            console.log('=== VALIDATING STEP 1 ===');
            this.clearValidationErrors();
            let isValid = true;
            
            // Validate Title
            const title = document.getElementById('title');
            if (!title || !title.value.trim()) {
                console.log('TITLE VALIDATION FAILED: Empty title');
                this.showFieldError('title', 'Tour title is required');
                isValid = false;
            } else if (title.value.trim().length > 60) {
                console.log('TITLE VALIDATION FAILED: Too long -', title.value.trim().length);
                this.showFieldError('title', 'Tour title must not exceed 60 characters');
                isValid = false;
            } else {
                console.log('TITLE VALIDATION PASSED:', title.value.trim());
            }
            
            // Validate Short Description
            const shortDescEditor = this.editors['short_description'];
            let shortDescContent = '';
            let shortDescTextLength = 0;
            
            if (shortDescEditor) {
                // Get plain text for character count (excluding HTML tags)
                shortDescContent = shortDescEditor.getText().trim();
                shortDescTextLength = shortDescContent.length;
                
                // Also get HTML content for form submission
                const shortDescHtml = shortDescEditor.root.innerHTML.trim();
                
                // Store the HTML content in the hidden input
                const hiddenInput = document.getElementById('short_description');
                if (hiddenInput) {
                    hiddenInput.value = shortDescHtml;
                }
            }
            
            if (!shortDescContent) {
                console.log('SHORT DESC VALIDATION FAILED: Empty content');
                this.showFieldError('short_description_editor', 'Short description is required');
                isValid = false;
            } else if (shortDescTextLength > 600) {
                console.log('SHORT DESC VALIDATION FAILED: Too long -', shortDescTextLength);
                this.showFieldError('short_description_editor', `Short description must not exceed 600 characters (currently ${shortDescTextLength})`);
                isValid = false;
            } else {
                console.log('SHORT DESC VALIDATION PASSED:', shortDescTextLength, 'characters');
            }
            
            // Validate Location
            const locationId = document.getElementById('location_id');
            if (!locationId || !locationId.value) {
                console.log('LOCATION VALIDATION FAILED: Empty location');
                this.showFieldError('location_id', 'Location is required');
                isValid = false;
            } else {
                console.log('LOCATION VALIDATION PASSED:', locationId.value);
            }
            
            // Validate Category
            const categoryId = document.getElementById('tour_category_id');
            if (!categoryId || !categoryId.value) {
                console.log('CATEGORY VALIDATION FAILED: Empty category');
                this.showFieldError('tour_category_id', 'Category is required');
                isValid = false;
            } else {
                console.log('CATEGORY VALIDATION PASSED:', categoryId.value);
            }
            
            // Validate Duration
            let durationInput = null;
            let durationValue = '';
            
            // First check if duration type is set
            if (!this.durationType) {
                // No duration type selected - check the default input
                durationInput = document.getElementById('duration_text_default');
                if (durationInput) {
                    durationValue = durationInput.value;
                }
            } else if (this.durationType === 'hourly') {
                durationInput = document.getElementById('duration_text_hourly');
                if (durationInput) {
                    durationValue = durationInput.value;
                }
            } else if (this.durationType === 'multiple_days') {
                // For multiple days, check the duration_text_multiple input
                durationInput = document.getElementById('duration_text_multiple');
                if (durationInput) {
                    durationValue = durationInput.value;
                }
            } else if (this.durationType === 'half_day') {
                durationInput = document.getElementById('duration_text_half_day');
                if (durationInput) {
                    durationValue = durationInput.value;
                }
            } else if (this.durationType === 'full_day') {
                durationInput = document.getElementById('duration_text_full_day');
                if (durationInput) {
                    durationValue = durationInput.value;
                }
            }
            
            console.log('Duration validation - Type:', this.durationType, 'Input:', durationInput, 'Value:', durationValue);
            
            // For prefilled fields (half_day, full_day), check if they have the expected values
            if (this.durationType === 'half_day' && durationValue === '6 hours') {
                // This is valid - half day is properly prefilled
                console.log('Half day duration is valid');
            } else if (this.durationType === 'full_day' && durationValue === 'Full day') {
                // This is valid - full day is properly prefilled
                console.log('Full day duration is valid');
            } else if (this.durationType === 'multiple_days') {
                // For multiple days, validate that duration_text has a value like "2 days", "3 days", etc.
                if (!durationValue || !durationValue.match(/^\d+\s+days$/)) {
                    const fieldId = durationInput ? durationInput.id : 'duration_text_multiple';
                    this.showFieldError(fieldId, 'Please specify a valid number of days');
                    isValid = false;
                }
            } else if (!this.durationType && (!durationValue || !durationValue.trim())) {
                // Only validate if no duration type is set and default input is empty
                const fieldId = durationInput ? durationInput.id : 'duration_text_default';
                this.showFieldError(fieldId, 'Duration is required');
                isValid = false;
            } else if (this.durationType === 'hourly' && (!durationValue || !durationValue.trim())) {
                // For hourly tours, validate that a duration is selected
                const fieldId = durationInput ? durationInput.id : 'duration_text_hourly';
                this.showFieldError(fieldId, 'Please select a duration');
                isValid = false;
            }
            
            return isValid;
        },
        
        // Validate Step 3 (Pricing)
        validateStep3() {
            this.clearValidationErrors();
            let isValid = true;
            
            const adultPrice = document.getElementById('base_price_adult');
            if (!adultPrice || !adultPrice.value || parseFloat(adultPrice.value) <= 0) {
                this.showFieldError('base_price_adult', 'Adult price must be greater than 0');
                isValid = false;
            }
            
            return isValid;
        },
        
        // Validate Step 2 (Schedule & Logistics)
        validateStep2() {
            let isValid = true;
            
            // Validate Group Size
            const groupSizeType = document.getElementById('group_size_type');
            const noOfPeopleInput = document.getElementById('no_of_people');
            
            if (!groupSizeType || !groupSizeType.value) {
                this.showFieldError('group_size_type', 'Group size is required');
                isValid = false;
            } else if (groupSizeType.value === 'custom') {
                // For custom group size, validate the number input
                if (!noOfPeopleInput || !noOfPeopleInput.value) {
                    this.showFieldError('no_of_people', 'Custom group size is required');
                    isValid = false;
                } else {
                    const value = parseInt(noOfPeopleInput.value);
                    if (isNaN(value) || value < 1 || value > 100) {
                        this.showFieldError('no_of_people', 'Group size must be between 1 and 100');
                        isValid = false;
                    }
                }
            }
            
            // Validate Cut-off Time
            const cutOffTime = document.getElementById('cut_off_time');
            if (!cutOffTime || !cutOffTime.value) {
                this.showFieldError('cut_off_time', 'Cut-off time is required');
                isValid = false;
            }
            
            // Validate Start Time
            const startTime = document.getElementById('starts_at_time');
            if (!startTime || !startTime.value) {
                this.showFieldError('starts_at_time', 'Start time is required');
                isValid = false;
            }
            
            return isValid;
        },
        
        // Main validation function
        validateCurrentStep() {
            switch(this.currentStep) {
                case 1:
                    return this.validateStep1();
                case 2:
                    return this.validateStep2();
                case 3:
                    return this.validateStep3();
                default:
                    return true;
            }
        },
        
        updateDurationField() {
            const select = document.getElementById('tour_category_id');
            if (select && select.value) {
                const selectedOption = select.options[select.selectedIndex];
                this.durationType = selectedOption.getAttribute('data-duration-type') || '';
            } else {
                this.durationType = '';
            }
            
            // Handle multiple days duration update
            this.updateMultipleDaysDuration();
        },
        
        updateMultipleDaysDuration() {
            if (this.durationType === 'multiple_days') {
                const daysInput = document.getElementById('duration_days');
                const durationInput = document.getElementById('duration_text_multiple');
                
                if (daysInput && durationInput) {
                    daysInput.addEventListener('input', (e) => {
                        const days = e.target.value || 2;
                        durationInput.value = days + ' days';
                    });
                }
            }
        },
        
        nextStep() {
            if (this.validateCurrentStep()) {
                if (this.currentStep < 6) {
                    this.currentStep++;
                    this.scrollToTop();
                }
            }
        },
        
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                this.scrollToTop();
            }
        },
        
        scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        
        handleFormSubmit(event) {
            console.log('🔥 handleFormSubmit CALLED! 🔥');
            
            console.log('=== FORM SUBMISSION START ===');
            
            // Validate all steps before final submission
            const step1Valid = this.validateStep1();
            const step2Valid = this.validateStep2();
            const step3Valid = this.validateStep3();
            
            console.log('Validation results:', {
                step1: step1Valid,
                step2: step2Valid,
                step3: step3Valid
            });
            
            if (!step1Valid || !step2Valid || !step3Valid) {
                console.log('VALIDATION FAILED - Preventing submission');
                event.preventDefault();
                return false;
            }
            
            console.log('VALIDATION PASSED - Syncing data');
            
            // Sync all data before submission
            this.syncEditors();
            syncItineraryData();
            this.syncDurationData();
            
            // Debug: Check what will be submitted
            console.log('=== FORM DATA TO BE SUBMITTED ===');
            try {
                const formData = new FormData(event.target);
                for (let [key, value] of formData.entries()) {
                    console.log(key + ':', value);
                }
                console.log('=== END FORM DATA ===');
                
                console.log('✅ FORM SUBMITTING...');
                // Let the form submit naturally
            } catch (error) {
                console.error('❌ Error reading form data:', error);
                console.log('✅ Form will submit anyway...');
            }
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
                { id: 'includes_editor', input: 'included' },
                { id: 'excluded_editor', input: 'excluded' },
                { id: 'important_information_editor', input: 'important_information' },
                { id: 'cancellation_policy_editor', input: 'cancellation_policy' }
            ];
            
            editorConfigs.forEach(config => {
                const container = document.getElementById(config.id);
                if (container) {
                    const quill = new Quill('#' + config.id, {
                        theme: 'snow',
                        modules: { toolbar: toolbarOptions }
                    });
                    
                    // Set initial content
                    const initialContent = document.getElementById(config.input).value;
                    if (initialContent) {
                        quill.root.innerHTML = initialContent;
                    }
                    
                    // Add character counting for short description
                    if (config.input === 'short_description') {
                        const updateCharCount = () => {
                            const text = quill.getText().trim();
                            const length = text.length;
                            const charCountElement = document.getElementById('short_description_char_count');
                            if (charCountElement) {
                                charCountElement.textContent = `${length} / 600 characters`;
                                if (length > 600) {
                                    charCountElement.classList.add('text-red-600');
                                    charCountElement.classList.remove('text-slate-500');
                                } else {
                                    charCountElement.classList.remove('text-red-600');
                                    charCountElement.classList.add('text-slate-500');
                                }
                            }
                        };
                        
                        // Update count on text change
                        quill.on('text-change', updateCharCount);
                        
                        // Initial count
                        updateCharCount();
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
        
        syncDurationData() {
            // Debug: Log current duration type
            console.log('syncDurationData called, durationType:', this.durationType);
            
            // For multiple days, ensure duration_text is updated with current days value
            if (this.durationType === 'multiple_days') {
                const daysInput = document.getElementById('duration_days');
                const durationTextInput = document.getElementById('duration_text_multiple');
                
                console.log('Multiple days - daysInput:', daysInput, 'durationTextInput:', durationTextInput);
                
                if (daysInput && durationTextInput) {
                    const days = daysInput.value || 2;
                    durationTextInput.value = days + ' days';
                    console.log('Updated duration_text_multiple to:', durationTextInput.value);
                }
            } else {
                // For other duration types, ensure the visible input has the correct name
                console.log('Other duration type:', this.durationType);
                
                // Find all duration text inputs and ensure only the visible one has name="duration_text"
                const allDurationInputs = [
                    'duration_text_default',
                    'duration_text_hourly', 
                    'duration_text_half_day',
                    'duration_text_full_day',
                    'duration_text_multiple'
                ];
                
                allDurationInputs.forEach(inputId => {
                    const input = document.getElementById(inputId);
                    if (input) {
                        // Remove name attribute from hidden inputs
                        input.removeAttribute('name');
                    }
                });
                
                // Add name attribute to the visible input based on durationType
                let activeInputId = 'duration_text_default';
                if (this.durationType === 'hourly') activeInputId = 'duration_text_hourly';
                else if (this.durationType === 'half_day') activeInputId = 'duration_text_half_day';
                else if (this.durationType === 'full_day') activeInputId = 'duration_text_full_day';
                else if (this.durationType === 'multiple_days') activeInputId = 'duration_text_multiple';
                
                const activeInput = document.getElementById(activeInputId);
                if (activeInput) {
                    activeInput.setAttribute('name', 'duration_text');
                    console.log('Set name="duration_text" on:', activeInputId, 'value:', activeInput.value);
                }
            }
        }
    }
}

function imageUploader() {
    return {
        previews: [],
        coverIndex: 0,
        isDragging: false,
        
        handleDrop(event) {
            event.preventDefault();
            this.isDragging = false;
            this.handleFiles(event.dataTransfer.files);
        },
        
        handleFiles(files) {
            Array.from(files).forEach(file => {
                if (this.previews.length >= 10) return;
                
                if (file.type.startsWith('image/') && file.size <= 5 * 1024 * 1024) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previews.push({
                            url: e.target.result,
                            file: file
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        },
        
        removeImage(index) {
            this.previews.splice(index, 1);
            if (this.coverIndex >= this.previews.length) {
                this.coverIndex = Math.max(0, this.previews.length - 1);
            }
        },
        
        setCover(index) {
            this.coverIndex = index;
        }
    }
}

// Toggle custom group size input
function toggleGroupSizeInput() {
    const groupSizeType = document.getElementById('group_size_type');
    const customDiv = document.getElementById('custom_group_size_div');
    const noOfPeopleInput = document.getElementById('no_of_people');
    
    if (groupSizeType.value === 'custom') {
        customDiv.classList.remove('hidden');
        noOfPeopleInput.required = true;
    } else {
        customDiv.classList.add('hidden');
        noOfPeopleInput.required = false;
        noOfPeopleInput.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleGroupSizeInput();
    initializeItinerary();
});

// Missing function for tour selection (placeholder)
function updateTourSelection() {
    // This function is referenced but may not be needed for create form
    // Keeping it to prevent console errors
}

// Itinerary functionality
let itineraryItemCount = 0;

function initializeItinerary() {
    // Add any existing itinerary items if editing
    const container = document.getElementById('itinerary-container');
    if (!container) return;
    
    // Start with one empty item
    if (container.children.length === 0) {
        addItineraryItem();
    }
}

function addItineraryItem() {
    const container = document.getElementById('itinerary-container');
    if (!container) return;
    
    const itemId = ++itineraryItemCount;
    const itemHtml = `
        <div class="itinerary-item border border-slate-200 rounded-lg p-4 space-y-4" id="itinerary-item-${itemId}">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium text-slate-900">Itinerary Item ${itemId}</h4>
                <button type="button" onclick="removeItineraryItem(${itemId})" class="text-red-600 hover:text-red-700 text-sm">
                    Remove
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                    <select name="itinerary[${itemId}][type]" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                        <option value="">Select type</option>
                        <option value="start">Start</option>
                        <option value="transit">Transit</option>
                        <option value="stopover">Stopover</option>
                        <option value="activity">Activity</option>
                        <option value="end">End</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Title</label>
                    <input type="text" name="itinerary[${itemId}][title]" placeholder="e.g. Hotel Pickup" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea name="itinerary[${itemId}][description]" rows="3" placeholder="Detailed description of this itinerary item" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900"></textarea>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
}

function removeItineraryItem(itemId) {
    const item = document.getElementById(`itinerary-item-${itemId}`);
    if (item) {
        item.remove();
    }
}

function syncItineraryData() {
    const container = document.getElementById('itinerary-container');
    if (!container) return;
    
    // Remove existing itinerary inputs
    const existingInputs = container.querySelectorAll('input[name^="itinerary"], textarea[name^="itinerary"], select[name^="itinerary"]');
    existingInputs.forEach(input => input.remove());
    
    const itemElements = container.querySelectorAll('.itinerary-item');
    
    itemElements.forEach((element, index) => {
        const typeSelect = element.querySelector('select[name*="[type]"]');
        const titleInput = element.querySelector('input[name*="[title]"]');
        const descriptionTextarea = element.querySelector('textarea[name*="[description]"]');
        
        if (titleInput && titleInput.value.trim()) {
            // Create hidden inputs for each itinerary item
            const typeHidden = document.createElement('input');
            typeHidden.type = 'hidden';
            typeHidden.name = `itinerary[${index}][type]`;
            typeHidden.value = typeSelect ? typeSelect.value : '';
            container.appendChild(typeHidden);
            
            const titleHidden = document.createElement('input');
            titleHidden.type = 'hidden';
            titleHidden.name = `itinerary[${index}][title]`;
            titleHidden.value = titleInput.value.trim();
            container.appendChild(titleHidden);
            
            const descriptionHidden = document.createElement('input');
            descriptionHidden.type = 'hidden';
            descriptionHidden.name = `itinerary[${index}][description]`;
            descriptionHidden.value = descriptionTextarea ? descriptionTextarea.value.trim() : '';
            container.appendChild(descriptionHidden);
        }
    });
    
    console.log('Synced itinerary data for', itemElements.length, 'items');
}
</script>
@endpush
@endsection
