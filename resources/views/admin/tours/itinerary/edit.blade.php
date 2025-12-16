@extends('layouts.admin')

@section('title', 'Edit Itinerary Item - ' . $tour->title)

@section('content')
<div class="space-y-6">
    <!-- Header -->
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
                            <a href="{{ route('console.tours.show', $tour) }}" class="ml-1 text-slate-500 hover:text-slate-700 md:ml-2">
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
                            <span class="ml-1 text-slate-500 md:ml-2">Edit Item</span>
                        </div>
                    </li>
                </ol>
            </nav>
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
    <div class="p-6 border-b border-slate-200">
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Edit Itinerary Item</h1>
        <p class="text-slate-600 mt-1">Update "{{ $itineraryItem->title }}" in "{{ $tour->title }}"</p>
    </div>
    <form action="{{ route('console.tours.itinerary.update', [$tour, $itineraryItem]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-6">
            <!-- Type and Title Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-slate-700 mb-1">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">
                        <option value="">Select type</option>
                        <option value="start" {{ $itineraryItem->type === 'start' ? 'selected' : '' }}>Start</option>
                        <option value="transit" {{ $itineraryItem->type === 'transit' ? 'selected' : '' }}>Transit</option>
                        <option value="stopover" {{ $itineraryItem->type === 'stopover' ? 'selected' : '' }}>Stopover</option>
                        <option value="activity" {{ $itineraryItem->type === 'activity' ? 'selected' : '' }}>Activity</option>
                        <option value="end" {{ $itineraryItem->type === 'end' ? 'selected' : '' }}>End</option>
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
                        value="{{ old('title', $itineraryItem->title) }}"
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
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none">{{ old('description', $itineraryItem->description) }}</textarea>
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
                            value="{{ old('duration_value', $itineraryItem->duration_value) }}"
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
                            <option value="minutes" {{ old('duration_unit', $itineraryItem->duration_unit) == 'minutes' ? 'selected' : '' }}>Minutes</option>
                            <option value="hours" {{ old('duration_unit', $itineraryItem->duration_unit) == 'hours' ? 'selected' : '' }}>Hours</option>
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
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <!-- Delete form moved outside main form -->
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('console.tours.itinerary.index', $tour) }}" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors cursor-pointer">
                        Update Itinerary Item
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
</div>

<!-- Delete form moved outside main form -->
<div class="mt-4" x-data="{ showDeleteModal: false }">
    <button @click="showDeleteModal = true" class="px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50">
        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        Delete
    </button>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                 @click="showDeleteModal = false">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="showDeleteModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Delete Itinerary Item
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete "{{ $itineraryItem->title }}"? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('console.tours.itinerary.destroy', [$tour, $itineraryItem]) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </form>
                    <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection