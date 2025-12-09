@props(['items', 'city'])

<section class="border border-slate-100 rounded-xl bg-white/60 p-4">
    <h2 class="text-base font-semibold mb-3">Itinerary</h2>
    <p class="text-xs sm:text-sm text-slate-600 mb-4">
        Times and exact order may vary slightly depending on traffic and opening hours, but a typical route for this {{ strtolower($city->name) }} city tour looks like this:
    </p>

    <div>
        <ol class="text-xs sm:text-sm text-slate-700">
            @foreach ($items as $item)
            <li class="flex">
                @php
                    $type = $item->type ?? 'activity';
                    $borderColor = match($type) {
                        'start' => 'border-green-600',
                        'transit' => 'border-blue-600',
                        'stopover' => 'border-emerald-600',
                        'end' => 'border-red-600',
                        default => 'border-amber-600',
                    };
                    $textColor = match($type) {
                        'start' => 'text-green-600',
                        'transit' => 'text-blue-600',
                        'stopover' => 'text-emerald-600',
                        'end' => 'text-red-600',
                        default => 'text-amber-600',
                    };
                    $lineColor = match($type) {
                        'start' => 'bg-green-600',
                        'transit' => 'bg-blue-600',
                        'stopover' => 'bg-emerald-600',
                        'end' => 'bg-red-600',
                        default => 'bg-amber-600',
                    };
                @endphp
                <div class="flex flex-col items-center mr-3">
                    <div class="flex items-center justify-center w-7 h-7 rounded-full border-2 {{ $borderColor }} bg-white {{ $textColor }}">
                        @if ($type === 'start')
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5.14v13.72a1 1 0 001.5.86l11-6.86a1 1 0 000-1.72l-11-6.86a1 1 0 00-1.5.86z" />
                        </svg>
                        @elseif ($type === 'transit')
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H6V6h12v5z"/>
                        </svg>
                        @elseif ($type === 'stopover')
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="12" r="6"/>
                        </svg>
                        @elseif ($type === 'end')
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 21V4h1v1h12l-3 4 3 4H6v8H5z" />
                        </svg>
                        @else
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z" />
                        </svg>
                        @endif
                    </div>
                    @unless ($loop->last)
                    <div class="w-1.5 flex-1 {{ $lineColor }} min-h-[40px]"></div>
                    @endunless
                </div>
                <div class="pb-6 flex-1" x-data="{ expanded: false }">
                    <p class="font-semibold text-slate-800">{{ $item->title }}</p>
                    @if ($item->description)
                    @php $descLength = Str::length($item->description); @endphp
                    <p class="text-slate-500">
                        <span x-show="!expanded">{{ $descLength > 180 ? Str::limit($item->description, 180, '') : $item->description }}@if ($descLength > 180)... <button type="button" @click="expanded = true" class="text-emerald-600 hover:text-emerald-700 font-medium cursor-pointer">Show more</button>@endif</span>
                        <span x-show="expanded" x-cloak>{{ $item->description }} @if ($descLength > 180)<button type="button" @click="expanded = false" class="text-emerald-600 hover:text-emerald-700 font-medium cursor-pointer">Show less</button>@endif</span>
                    </p>
                    @endif
                </div>
            </li>
            @endforeach
        </ol>
    </div>
</section>