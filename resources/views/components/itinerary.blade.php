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
                    <div class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white shadow-sm">
                        @if ($loop->first)
                        {{-- Play / start icon --}}
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86a1 1 0 00-1.5.86z" />
                        </svg>
                        @elseif ($loop->last)
                        {{-- Flag / finish icon --}}
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 21V4h1v1h12l-3 4 3 4H6v8H5z" />
                        </svg>
                        @else
                        {{-- Pin icon for middle stops --}}
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z" />
                        </svg>
                        @endif
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