@props(['items' => []])

<nav class="text-xs text-slate-500 mb-3" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1">
        <li><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
        @foreach ($items as $item)
            <li>/</li>
            @if (isset($item['url']))
                <li><a href="{{ $item['url'] }}" class="hover:underline">{{ $item['label'] }}</a></li>
            @else
                <li><span class="text-slate-600">{{ $item['label'] }}</span></li>
            @endif
        @endforeach
    </ol>
</nav>
