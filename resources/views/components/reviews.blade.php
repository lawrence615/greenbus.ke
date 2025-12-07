@props(['testimonials'])

<section id="reviews" class="bg-slate-900 text-slate-50 mt-8">
    <div class="max-w-6xl mx-auto px-4 py-12 grid gap-10 md:grid-cols-3 items-start">

        <!-- Average rating -->
        <div class="space-y-4 md:space-y-5 md:col-span-1">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-300">Reviews</p>
            <h2 class="text-2xl md:text-3xl font-semibold">
                Travellers love starting Nairobi with Greenbus
            </h2>
            <div class="flex items-center gap-3 text-sm">
                <div class="flex items-center gap-0.5 text-emerald-300 text-base">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <p class="text-slate-200">
                    4.8 / 5 from 120+ guests
                </p>
            </div>
            <p class="text-xs text-slate-300 max-w-xs">
                Feedback from visitors who used Greenbus for their first day in Nairobi.
            </p>
        </div>

        <!-- Actual reviews -->
        <div class="md:col-span-2 overflow-hidden">
            @if($testimonials->count() > 0)
                <div class="relative" x-data="{}">
                    <div class="overflow-hidden" id="reviews-carousel">
                        <div class="flex transition-transform duration-500 ease-out w-full" data-review-track>
                            @foreach($testimonials->chunk(2) as $index => $chunk)
                                <div class="w-full shrink-0 px-1">
                                    <div class="grid gap-3 md:grid-cols-2">
                                        @foreach($chunk as $testimonial)
                                            <article class="bg-slate-800 rounded-xl p-5 border border-slate-700 h-full flex flex-col">
                                                <div class="flex items-center gap-3 mb-3">
                                                    @if($testimonial->author_cover)
                                                        <img 
                                                            src="{{ $testimonial->author_cover }}" 
                                                            alt="{{ $testimonial->author_name }}"
                                                            class="w-8 h-8 rounded-full object-cover"
                                                        >
                                                    @else
                                                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-semibold">
                                                            {{ $testimonial->initial }}
                                                        </div>
                                                    @endif
                                                    <div class="text-xs min-w-0 flex-1">
                                                        <p class="font-semibold text-slate-50 truncate">{{ $testimonial->author_name }}</p>
                                                        <p class="text-slate-300 truncate max-w-[220px]">
                                                            {{ $testimonial->author_location }}@if($testimonial->tour_name) · {{ $testimonial->tour_name }}@elseif($testimonial->tour) · {{ $testimonial->tour->title }}@endif
                                                        </p>
                                                    </div>
                                                </div>
                                                <p class="text-sm mb-3 line-clamp-4">"{{ $testimonial->content }}"</p>
                                                <p class="text-[11px] text-slate-400 mt-auto">
                                                    {{ $testimonial->author_date ? $testimonial->author_date->format('F j, Y') : $testimonial->created_at->format('F j, Y') }}
                                                </p>
                                            </article>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($testimonials->count() > 2)
                        <div class="flex justify-center gap-2 mt-4" data-review-dots>
                            @foreach($testimonials->chunk(2) as $index => $chunk)
                                <button 
                                    type="button" 
                                    class="w-2 h-2 rounded-full {{ $index === 0 ? 'bg-emerald-500' : 'bg-emerald-900' }}" 
                                    aria-label="Go to review {{ $index + 1 }}"
                                ></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="flex items-center justify-center h-48 text-slate-400">
                    <p>No reviews available yet.</p>
                </div>
            @endif
        </div>
    </div>
</section>
