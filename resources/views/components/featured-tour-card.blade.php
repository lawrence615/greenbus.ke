@props(['tour', 'city' => null])

@php
    $city = $city ?? $tour->city;
    $cover = $tour->images->firstWhere('is_cover', true) ?? $tour->images->first();
@endphp

<article class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
    <div class="h-40 bg-slate-200">
        @if ($cover)
            <img src="{{ $cover->url }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">Tour photo coming soon</div>
        @endif
    </div>
    <div class="p-4 flex-1 flex flex-col">
        <h3 class="font-semibold mb-1 text-sm md:text-base">{{ $tour->title }}</h3>
        @if ($tour->category)
            <p class="text-[11px] text-emerald-700 font-semibold mb-1">{{ $tour->category->name }}</p>
        @endif
        <p class="text-xs text-slate-600 mb-2 line-clamp-3">{!! $tour->short_description !!}</p>
        <p class="text-sm font-semibold text-emerald-700 mb-3">
            From KES {{ number_format($tour->base_price_adult) }} per adult
        </p>
        <a href="{{ route('tours.show', [$city, $tour]) }}" class="mt-auto inline-flex items-center justify-center px-4 py-2 rounded-full bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">
            View details & book
        </a>
    </div>
</article>
