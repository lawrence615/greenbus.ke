@extends('layouts.app')

@section('title', $city->name . ' tours')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => $city->name . ' tours'],
    ]" />
@endsection

@section('content')
    <section>
        <div class="max-w-6xl mx-auto px-4 pb-10 pt-8">
            <header class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-emerald-800 mb-1">Sightseeing bus tours in {{ $city->name }}</p>
                    <h1 class="text-2xl md:text-3xl font-semibold text-slate-900">
                        {{ $city->name }} bus & city tours
                    </h1>
                    <p class="mt-2 text-sm text-slate-700 max-w-xl">
                        Hop on a comfortable Greenbus and see the best of {{ $city->name }} in one trip – from must-see
                        landmarks to hidden streets, with a local guide sharing stories along the way.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-xs sm:text-[13px] text-slate-600 justify-center md:justify-end md:text-right">
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 border border-emerald-100 shadow-sm">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-[11px] font-semibold">
                            {{ $tours->total() }}
                        </span>
                        <span>tours available in {{ $city->name }}</span>
                    </div>
                    <div class="hidden sm:flex items-center gap-2">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-900 text-white text-[11px] font-semibold">★</span>
                        <span>Perfect for first-time visitors and small groups</span>
                    </div>
                </div>
            </header>

            @if ($tours->isEmpty())
                <div class="mt-10 rounded-2xl border border-dashed border-slate-200 bg-white/70 px-6 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-800 mb-1">Tours coming soon</p>
                    <p class="text-sm text-slate-600 max-w-md mx-auto">
                        We’re still adding experiences in {{ $city->name }}. Check back soon for guided city tours,
                        local food walks and more ways to explore.
                    </p>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($tours as $tour)
                        <x-featured-tour-card :tour="$tour" :city="$city" />
                    @endforeach
                </div>

                <div class="mt-8 flex justify-between items-center text-xs text-slate-500">
                    <p>
                        Showing
                        <span class="font-semibold text-slate-700">{{ $tours->firstItem() }}</span>
                        –
                        <span class="font-semibold text-slate-700">{{ $tours->lastItem() }}</span>
                        of
                        <span class="font-semibold text-slate-700">{{ $tours->total() }}</span>
                        tours
                    </p>
                    <div class="text-right">
                        {{ $tours->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
