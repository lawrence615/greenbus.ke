@props(['items', 'city'])

<section class="border border-slate-100 rounded-xl bg-white/60 p-4">
    <h2 class="text-base font-semibold mb-3">Itinerary</h2>
    <p class="text-xs sm:text-sm text-slate-600 mb-4">
        Times and exact order may vary slightly depending on traffic and opening hours, but a typical route for this {{ strtolower($city->name) }} city tour looks like this:
    </p>

    <div class="pl-4 sm:pl-6">
        <ol class="space-y-4 text-xs sm:text-sm text-slate-700">
            @foreach ($items as $item)
                <li class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center w-5 h-5 rounded-full bg-emerald-600 text-white shadow-sm">
                            <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.75C8.824 2.75 6.25 5.324 6.25 8.5C6.25 12.578 11.2 17.727 11.412 17.945C11.744 18.289 12.256 18.289 12.588 17.945C12.8 17.727 17.75 12.578 17.75 8.5C17.75 5.324 15.176 2.75 12 2.75Z" fill="currentColor" />
                                <circle cx="12" cy="8.5" r="2.25" fill="white" />
                            </svg>
                        </div>
                        @unless ($loop->last)
                            <div class="mt-1 w-[2px] flex-1 bg-emerald-100"></div>
                        @endunless
                    </div>
                    <div class="flex-1 flex gap-4">
                        <div class="w-16 sm:w-20 text-right text-[11px] sm:text-xs font-medium text-slate-500">
                            {{ $item->time_label }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold mb-0.5">{{ $item->title }}</p>
                            @if ($item->description)
                                <p>{{ $item->description }}</p>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>
