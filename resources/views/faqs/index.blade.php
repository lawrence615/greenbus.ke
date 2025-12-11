@extends('layouts.app')

@section('title', 'Frequently Asked Questions')

@section('content')
<section class="max-w-6xl mx-auto px-4 py-10">
    <div class="mb-8 text-center md:text-left">
        <p class="text-xs uppercase tracking-wide text-emerald-700 mb-1">Help & support</p>
        <h1 class="text-2xl md:text-3xl font-semibold mb-2">Frequently asked questions</h1>
        <p class="text-sm text-slate-600 max-w-2xl">
            Answers to common questions about Greenbus City Tours, tickets, cancellations and what to expect on your tour.
        </p>
    </div>

    @if(!empty($categories))
    <div class="flex flex-wrap gap-2 mb-6 text-xs">
        @foreach($categories as $category)
            <a
                href="{{ route('faqs.index', ['category' => $category]) }}"
                class="inline-flex items-center rounded-full px-3 py-1 border {{ (isset($selectedCategory) && $selectedCategory === $category) ? 'border-emerald-600 bg-emerald-50 text-emerald-800' : 'border-slate-200 bg-white text-slate-600 hover:border-emerald-100 hover:text-emerald-700' }}">
                {{ $category }}
            </a>
        @endforeach
        @if(isset($selectedCategory) && $selectedCategory && !in_array($selectedCategory, $categories, true))
            <span class="text-[11px] text-slate-400">Filtered by: {{ $selectedCategory }}</span>
        @endif
    </div>
    @endif

    @if($faqs->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-200 bg-white px-4 py-10 text-center text-sm text-slate-500">
            We are still preparing our FAQs. If you have a question, please reach us via the contact details in the footer.
        </div>
    @else
        <div class="space-y-4">
            @foreach($faqs as $faq)
                <details class="group rounded-2xl border border-slate-100 bg-white px-4 py-3">
                    <summary class="flex cursor-pointer items-center justify-between gap-3 text-sm font-medium text-slate-900 list-none">
                        <span>{{ $faq->question }}</span>
                        <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-500 group-open:rotate-180 transition-transform">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>
                    <div class="mt-3 text-sm text-slate-700 prose prose-sm max-w-none">
                        {!! $faq->answer !!}
                    </div>
                    @if($faq->category)
                        <p class="mt-2 text-[11px] uppercase tracking-wide text-slate-400">{{ $faq->category }}</p>
                    @endif
                </details>
            @endforeach
        </div>
    @endif
</section>
@endsection
