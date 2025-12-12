@extends('layouts.admin')

@section('title', 'Testimonials')
@section('page-title', 'Testimonials')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-500">Manage customer testimonials displayed on the website</p>
        </div>
        <a href="{{ route('console.testimonials.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Testimonial
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form method="GET" action="{{ route('console.testimonials.index') }}" class="flex flex-col lg:flex-row lg:items-end gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1">
                <!-- Search -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Author name, content..."
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"
                    >
                </div>
                
                <!-- Status -->
                <div class="space-y-1.5">
                    <label class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </label>
                    <select name="is_active" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer">
                        <option value="">All Statuses</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 shadow-sm cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'is_active']))
                <a href="{{ route('console.testimonials.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 border border-slate-300 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Testimonials Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-b from-slate-50 to-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Author
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                Content
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Rating
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <div class="flex items-center gap-2 font-semibold text-slate-700 uppercase text-xs tracking-wider">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Status
                            </div>
                        </th>
                        <th class="px-4 py-3 text-right">
                            <span class="font-semibold text-slate-700 uppercase text-xs tracking-wider">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($testimonials as $testimonial)
                    <tr class="hover:bg-emerald-50/50 transition-colors duration-150 group">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white font-semibold text-sm shrink-0">
                                    {{ $testimonial->initial }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900">{{ $testimonial->author_name }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $testimonial->author_location ?? 'Location not set' }}
                                        @if($testimonial->tour_name)
                                        · {{ $testimonial->tour_name }}
                                        @endif
                                    </p>
                                    @if($testimonial->travel_type)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 mt-1">
                                        {{ $testimonial->travel_type }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <p class="text-slate-600 line-clamp-2 max-w-md">{{ Str::limit($testimonial->content, 120) }}</p>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <div class="flex items-center gap-0.5 text-amber-500">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $testimonial->rating)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-semibold
                                {{ $testimonial->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $testimonial->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-0.5">
                                <a href="{{ route('console.testimonials.edit', $testimonial) }}" class="p-1.5 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors duration-150" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <div x-data="{ showConfirm: false }" class="inline relative">
                                    <button 
                                        type="button" 
                                        @click="showConfirm = true"
                                        class="p-1.5 rounded-lg transition-colors duration-150 cursor-pointer {{ $testimonial->is_active ? 'text-amber-600 hover:text-amber-700 hover:bg-amber-50' : 'text-blue-600 hover:text-blue-700 hover:bg-blue-50' }}" 
                                        title="{{ $testimonial->is_active ? 'Deactivate' : 'Activate' }}"
                                    >
                                        @if($testimonial->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        @endif
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
                                                {{ $testimonial->is_active ? 'Deactivate Testimonial?' : 'Activate Testimonial?' }}
                                            </h3>
                                            <p class="text-sm text-center text-slate-600 mb-4">
                                                @if($testimonial->is_active)
                                                    This testimonial will no longer be visible to the public.
                                                @else
                                                    This testimonial will become visible to the public.
                                                @endif
                                            </p>
                                            <div class="flex justify-end gap-3">
                                                <button 
                                                    type="button" 
                                                    @click="showConfirm = false"
                                                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 cursor-pointer"
                                                >
                                                    Cancel
                                                </button>
                                                <form method="POST" action="{{ route('console.testimonials.toggle-status', $testimonial) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button 
                                                        type="submit" 
                                                        class="px-4 py-2 text-sm font-medium text-white rounded-lg {{ $testimonial->is_active ? 'bg-amber-500 hover:bg-amber-600' : 'bg-green-600 hover:bg-green-700' }} cursor-pointer"
                                                    >
                                                        {{ $testimonial->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div x-data="{ showConfirm: false }" class="inline relative">
                                    <button 
                                        type="button" 
                                        @click="showConfirm = true"
                                        class="p-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-150 cursor-pointer" 
                                        title="Delete"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
                                                Delete Testimonial?
                                            </h3>
                                            <p class="text-sm text-center text-slate-600 mb-4">
                                                Are you sure you want to delete this testimonial? This action cannot be undone.
                                            </p>
                                            <div class="flex justify-end gap-3">
                                                <button 
                                                    type="button" 
                                                    @click="showConfirm = false"
                                                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 cursor-pointer"
                                                >
                                                    Cancel
                                                </button>
                                                <form method="POST" action="{{ route('console.testimonials.destroy', $testimonial) }}">
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium">No testimonials found</p>
                                <p class="text-slate-400 text-sm mt-1">Add your first testimonial to get started</p>
                                <a href="{{ route('console.testimonials.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Testimonial
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($testimonials->hasPages())
        <div class="px-4 py-4 border-t border-slate-200">
            {{ $testimonials->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
