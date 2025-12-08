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
                    <x-featured-tour-card :tour="$tour" :city="$city" />
                @endforeach
            </div>

            <div class="mt-6">
                {{ $tours->links() }}
            </div>
        @endif
    </section>
@endsection
