@props([
    'label',
    'value',
    'subtitle' => null,
    'iconBgClass' => 'bg-slate-50 text-slate-700',
    'borderClass' => 'border-slate-100',
])

<div class="bg-white rounded-2xl border {{ $borderClass }} shadow-sm p-3.5 flex items-center gap-3 transition hover:shadow-md">
    <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $iconBgClass }} shrink-0">
        {{ $icon ?? '' }}
    </div>
    <div>
        <p class="text-[11px] font-semibold tracking-wide text-slate-900 uppercase mb-0.5">{{ $label }}</p>
        @if ($subtitle)
            <p class="text-[11px] text-slate-500 mb-0.5">{{ $subtitle }}</p>
        @endif
        <p class="text-xs text-slate-700">{{ $value }}</p>
    </div>
</div>
