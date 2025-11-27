@props([
    'code',
    'badge',
    'title',
    'description',
    'iconBgClass' => 'bg-slate-50 text-slate-700',
    'badgeBgClass' => 'bg-slate-50 text-slate-700',
    'badgeCircleClass' => 'bg-slate-500 text-white',
])

<section class="max-w-4xl mx-auto px-4 py-20 flex flex-col items-center justify-center text-center min-h-[60vh]">
    <div class="mb-6 flex items-center justify-center">
        <div class="flex h-20 w-20 items-center justify-center rounded-full {{ $iconBgClass }} shadow-sm">
            {{ $icon ?? '' }}
        </div>
    </div>

    <div class="mb-6 inline-flex items-center justify-center rounded-full px-5 py-2 text-xs md:text-sm font-medium {{ $badgeBgClass }}">
        <span class="mr-2 inline-flex h-8 w-8 items-center justify-center rounded-full {{ $badgeCircleClass }} text-sm md:text-base">{{ $code }}</span>
        {{ $badge }}
    </div>

    <h1 class="text-2xl md:text-3xl font-semibold text-slate-900 mb-3">{{ $title }}</h1>
    <p class="text-sm md:text-base text-slate-600 mb-8 max-w-xl">
        {{ $description }}
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        {{ $actions ?? '' }}
    </div>

    @if (isset($footer))
        <div class="mt-8 text-[11px] text-slate-400">
            {{ $footer }}
        </div>
    @endif
</section>
