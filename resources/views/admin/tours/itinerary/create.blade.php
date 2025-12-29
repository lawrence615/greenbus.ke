@extends('layouts.admin')

@section('title', 'Add Itinerary Item - ' . $tour->title)

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('console.dashboard') }}" class="text-slate-500 hover:text-slate-700">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ route('console.tours.index') }}" class="ml-1 text-slate-500 hover:text-slate-700 md:ml-2">
                                        Tours
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ $tour->admin_show_route }}" class="ml-1 text-slate-500 hover:text-slate-700 md:ml-2">
                                        {{ $tour->title }}
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ route('console.tours.itinerary.index', $tour) }}" class="ml-1 text-slate-500 hover:text-slate-700 md:ml-2">
                                        Itinerary
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 text-slate-500 md:ml-2">Add Item</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl font-bold text-slate-900 mt-2">Add Itinerary Item</h1>
                    <p class="text-slate-600 mt-1">Add a new itinerary item to "{{ $tour->title }}"</p>
                </div>
                <a href="{{ route('console.tours.itinerary.index', $tour) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Itinerary
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <form action="{{ route('console.tours.itinerary.store', $tour) }}" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    <!-- Type and Title Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-slate-700 mb-1">
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                <option value="">Select type</option>
                                <option value="start">Start</option>
                                <option value="transit">Transit</option>
                                <option value="stopover">Stopover</option>
                                <option value="activity" selected>Activity</option>
                                <option value="end">End</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required 
                                   value="{{ old('title') }}"
                                   placeholder="e.g. Hotel Pickup, City Tour, Lunch Break"
                                   class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  placeholder="Provide details about this itinerary item..."
                                  class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration Fields -->
                    <div class="border-t border-slate-200 pt-6">
                        <h3 class="text-lg font-medium text-slate-900 mb-4">Duration (Optional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="duration_value" class="block text-sm font-medium text-slate-700 mb-1">
                                    Duration Value
                                </label>
                                <input type="number" id="duration_value" name="duration_value" 
                                       min="0"
                                       value="{{ old('duration_value') }}"
                                       placeholder="e.g. 30, 2, 45"
                                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                @error('duration_value')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="duration_unit" class="block text-sm font-medium text-slate-700 mb-1">
                                    Duration Unit
                                </label>
                                <select id="duration_unit" name="duration_unit" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                                    <option value="minutes" {{ old('duration_unit') == 'minutes' ? 'selected' : '' }}>Minutes</option>
                                    <option value="hours" {{ old('duration_unit') == 'hours' ? 'selected' : '' }}>Hours</option>
                                </select>
                                @error('duration_unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">
                            Specify how long this activity takes. Leave empty if duration is not applicable.
                        </p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 rounded-b-xl">
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('console.tours.itinerary.index', $tour) }}" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                            Add Itinerary Item
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
