@extends('layouts.admin')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('title', 'Manage Itinerary - ' . $tour->title)

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
                            <a href="{{ $tour->admin_show_route }}" class="ml-1 text-slate-500 hover:text-slate-700 md:ml-2">
                                {{ $tour->title }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-slate-500 md:ml-2">Itinerary</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="flex space-x-3">
            <a href="{{ $tour->admin_show_route }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Tour
            </a>
            <a href="{{ route('console.tours.multimedia.index', $tour) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Manage Images
            </a>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<!-- @if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
    <div class="flex">
        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
    <div class="flex">
        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        <p class="ml-3 text-sm text-red-800">{{ session('error') }}</p>
    </div>
</div>
@endif -->

<!-- Itinerary Items -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200">
        <div class="flex items-center justify-between">
            <!-- <h2 class="text-lg font-semibold text-slate-900">Itinerary Items</h2> -->
            <div class="px-6 py-4 border-b border-slate-200">
                <h1 class="text-2xl font-bold text-slate-900 mt-2">Manage Itinerary</h1>
                <p class="text-slate-600 mt-1">Add and organize tour itinerary items for "{{ $tour->title }}"</p>
            </div>
            <a href="{{ route('console.tours.itinerary.create', $tour) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Item
            </a>
        </div>
    </div>

    <div class="p-6">
        @if($itineraryItems->count() > 0)
        <div class="space-y-4" id="itinerary-items">
            @foreach($itineraryItems as $index => $item)
            <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow" data-item-id="{{ $item->id }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($item->type === 'start') bg-green-100 text-green-800
                                                @elseif($item->type === 'end') bg-red-100 text-red-800
                                                @elseif($item->type === 'activity') bg-blue-100 text-blue-800
                                                @elseif($item->type === 'transit') bg-yellow-100 text-yellow-800
                                                @else bg-slate-100 text-slate-800
                                                @endif">
                                {{ ucfirst($item->type) }}
                            </span>
                            @if($item->duration_value && $item->duration_unit)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $item->duration_value }} {{ $item->duration_unit }}
                            </span>
                            @endif
                        </div>
                        <h3 class="text-lg font-medium text-slate-900">{{ $item->title }}</h3>
                        @if($item->description)
                        <p class="text-slate-600 mt-1">{{ Str::limit($item->description, 150) }}</p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <button type="button" class="drag-handle p-2 text-slate-400 hover:text-slate-600 cursor-move">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <a href="{{ route('console.tours.itinerary.edit', [$tour, $item]) }}" class="p-2 text-slate-400 hover:text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <div x-data="{ showConfirm: false }" class="inline relative">
                            <button @click="showConfirm = true" class="p-2 text-slate-400 hover:text-red-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>

                            <!-- Confirmation Modal -->
                            <div 
                                x-show="showConfirm" 
                                x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center"
                                @keydown.escape.window="showConfirm = false"
                            >
                                <div class="fixed inset-0 bg-black/50" @click="showConfirm = false"></div>
                                <div class="relative bg-white rounded-xl shadow-xl p-6 max-w-sm mx-4 z-10">
                                    <h3 class="text-lg text-center font-semibold text-slate-900 mb-2">
                                        Delete Itinerary Item?
                                    </h3>
                                    <p class="text-sm text-center text-slate-600 mb-4">
                                        Are you sure you want to delete "{{ $item->title }}"? This action cannot be undone.
                                    </p>
                                    <div class="flex justify-end gap-3">
                                        <button 
                                            type="button" 
                                            @click="showConfirm = false"
                                            class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 cursor-pointer"
                                        >
                                            Cancel
                                        </button>
                                        <form method="POST" action="{{ route('console.tours.itinerary.destroy', [$tour, $item]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 cursor-pointer"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-slate-900">No itinerary items</h3>
            <p class="mt-1 text-sm text-slate-500">Get started by adding your first itinerary item.</p>
            <div class="mt-6">
                <a href="{{ route('console.tours.itinerary.create', $tour) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add First Item
                </a>
            </div>
        </div>
        @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortable = new Sortable(document.getElementById('itinerary-items'), {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                const itemIds = Array.from(document.querySelectorAll('#itinerary-items [data-item-id]')).map(el => el.dataset.itemId);

                fetch('{{ route("console.tours.itinerary.reorder", $tour) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            item_ids: itemIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            // Revert the order if the update failed
                            sortable.sort(evt.oldIndex);
                            alert('Failed to update item order. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        sortable.sort(evt.oldIndex);
                        alert('Failed to update item order. Please try again.');
                    });
            }
        });
    });
</script>
</div>
@endsection