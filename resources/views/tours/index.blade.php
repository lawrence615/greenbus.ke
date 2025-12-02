@extends('layouts.app')

@section('title', $city->name . ' tours')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => $city->name . ' tours'],
    ]" />
@endsection

@section('content')
    <section class="max-w-6xl mx-auto px-4 pb-10">
        <header class="mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-wide text-emerald-700 mb-1">City tours</p>
                <h1 class="text-2xl font-semibold">{{ $city->name }} tours</h1>
                <p class="text-sm text-slate-600 mt-1">Browse available city tours and pick the one that fits your time in Nairobi.</p>
            </div>
        </header>

        @if ($tours->isEmpty())
            <p class="text-sm text-slate-600">No tours have been published for this city yet.</p>
        @else
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($tours as $tour)
                    <article class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                        <div class="h-40 bg-slate-200">
                            @php
                                $cover = $tour->images->firstWhere('is_cover', true) ?? $tour->images->first();
                            @endphp
                            @if ($cover)
                                <img src="{{ $cover->path }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">Tour photo coming soon</div>
                            @endif
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <h2 class="font-semibold mb-1 text-sm md:text-base">{{ $tour->title }}</h2>
                            @if ($tour->category)
                                <p class="text-[11px] text-emerald-700 font-semibold mb-1">{{ $tour->category->name }}</p>
                            @endif
                            <p class="text-xs text-slate-600 mb-2 line-clamp-3">{{ $tour->short_description }}</p>
                            <p class="text-sm font-semibold text-emerald-700 mb-3">
                                From KES {{ number_format($tour->base_price_adult) }} per adult
                            </p>
                            <a href="{{ route('tours.show', [$city, $tour]) }}" class="mt-auto inline-flex items-center justify-center px-4 py-2 rounded-full bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">
                                View details & book
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $tours->links() }}
            </div>
        @endif
    </section>
@endsection
