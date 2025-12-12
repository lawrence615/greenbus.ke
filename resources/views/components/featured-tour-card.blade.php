@props(['tour', 'city' => null])

@php
    $city = $city ?? $tour->city;
    $cover = $tour->images->firstWhere('is_cover', true) ?? $tour->images->first();
@endphp

<article class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
    <div class="h-40 bg-slate-200 relative">
        @if ($cover)
            <img
                src="{{ $cover->url }}"
                alt="{{ $tour->title }}"
                class="w-full h-full object-cover"
                onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
            >
            <div data-fallback class="hidden absolute inset-0 flex flex-col items-center justify-center gap-1 text-xs text-slate-500 bg-slate-100 animate-pulse">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-200 text-slate-500">
                    <!-- simple camera icon -->
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 4.5L7.5 6H5A2 2 0 003 8v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-2.5L13.5 4.5H9z" stroke="currentColor" stroke-width="1.5"/>
                        <circle cx="12" cy="12" r="3.25" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </span>
                <span>Tour photo coming soon</span>
            </div>
        @else
            <div class="absolute inset-0 flex flex-col items-center justify-center gap-1 text-xs text-slate-500 bg-slate-100">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-200 text-slate-500">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 4.5L7.5 6H5A2 2 0 003 8v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-2.5L13.5 4.5H9z" stroke="currentColor" stroke-width="1.5"/>
                        <circle cx="12" cy="12" r="3.25" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </span>
                <span>Tour photo coming soon</span>
            </div>
        @endif
    </div>
    <div class="p-4 flex-1 flex flex-col">
        <h3 class="font-semibold mb-1 text-sm md:text-base">{{ Str::limit($tour->title ?? 'N/A', 38) }}</h3>
        @if ($tour->category)
            <p class="text-[11px] text-emerald-700 font-semibold mb-1">{{ $tour->category->name }}</p>
        @endif
        <p class="text-xs text-slate-600 mb-2 line-clamp-2">{!! Str::limit($tour->short_description ?? 'N/A', 90) !!}</p>
        <p class="text-sm font-semibold text-emerald-700 mb-3">
            From KES {{ number_format($tour->base_price_adult) }} per adult
        </p>
        <a href="{{ route('tours.show', [$city, $tour]) }}" class="mt-auto inline-flex items-center justify-center px-4 py-2 rounded-full bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">
            View details & book
        </a>
    </div>
</article>
