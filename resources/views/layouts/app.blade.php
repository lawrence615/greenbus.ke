<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name', 'Greenbus'))</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">
        <header class="border-b bg-white/80 backdrop-blur sticky top-0 z-20">
            <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-emerald-700">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-600 text-white font-bold">
                        GB
                    </span>
                    <span>Greenbus City Tours</span>
                </a>
                <nav class="flex items-center gap-4 text-sm">
                    <a
                        href="{{ route('home') }}"
                        class="hover:text-emerald-700 {{ request()->routeIs('home') ? 'text-emerald-700 font-semibold' : 'text-slate-700' }}"
                    >
                        Home
                    </a>
                    @isset($city)
                        <a
                            href="{{ route('tours.index', $city) }}"
                            class="hover:text-emerald-700 {{ request()->routeIs('tours.*') || request()->routeIs('bookings.*') ? 'text-emerald-700 font-semibold' : 'text-slate-700' }}"
                        >
                            Tours
                        </a>
                    @endisset
                    <a
                        href="#contact"
                        class="hover:text-emerald-700 text-slate-700"
                    >
                        Contact
                    </a>
                    <a
                        href="{{ route('home') }}#how-it-works"
                        class="hover:text-emerald-700 text-slate-700"
                    >
                        How it works
                    </a>
                </nav>
                <a href="{{ isset($city) ? route('tours.index', $city) : '#tours' }}" class="hidden sm:inline-flex items-center px-4 py-2 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                    Book a Nairobi Tour
                </a>
            </div>
        </header>

        <main class="flex-1">
            @if (session('status'))
                <div class="bg-emerald-50 border-b border-emerald-200">
                    <div class="max-w-6xl mx-auto px-4 py-2 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <footer id="contact" class="mt-12 border-t bg-white/95">
            <div class="max-w-6xl mx-auto px-4 py-10 lg:py-12 grid gap-8 md:grid-cols-4 text-sm">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-emerald-600 text-white font-bold">
                            GB
                        </span>
                        <div>
                            <p class="font-semibold text-slate-900">Greenbus City Tours</p>
                            <p class="text-xs text-emerald-700">Nairobi, Kenya</p>
                        </div>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        Small-group city tours designed for visitors to Nairobi. Trusted local guides, safe modern buses, and transparent pricing.
                    </p>
                    <a href="{{ isset($city) ? route('tours.index', $city) : '#tours' }}" class="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-emerald-700">
                        <span>Browse Nairobi tours</span>
                        <span class="text-[11px]">→</span>
                    </a>
                </div>

                <div class="space-y-3">
                    <h3 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Explore</h3>
                    <ul class="space-y-2 text-slate-600">
                        <li>
                            <a href="{{ route('home') }}#how-it-works" class="hover:text-emerald-700">How Greenbus works</a>
                        </li>
                        <li>
                            @isset($city)
                                <a href="{{ route('tours.index', $city) }}" class="hover:text-emerald-700">All Nairobi tours</a>
                            @else
                                <a href="#tours" class="hover:text-emerald-700">Featured tours</a>
                            @endisset
                        </li>
                        <li>
                            <a href="#reviews" class="hover:text-emerald-700">Guest reviews</a>
                        </li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h3 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Contact</h3>
                    <ul class="space-y-2 text-slate-600">
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                                <span class="text-[11px]">@</span>
                            </span>
                            <span>info@greenbus.ke</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                                <span class="text-[11px]">📞</span>
                            </span>
                            <span>+254 700 000 000<br><span class="text-xs text-slate-500">Call or WhatsApp between 08:00 – 20:00 EAT</span></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                                <span class="text-[11px]">📍</span>
                            </span>
                            <span>Nairobi city centre pick‑up points for all tours.</span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h3 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Practical info</h3>
                    <ul class="space-y-2 text-slate-600">
                        <li>All prices in Kenyan Shillings (KES).</li>
                        <li>Instant confirmation for online bookings.</li>
                        <li>Free cancellation up to 24 hours before departure.</li>
                    </ul>
                    <div class="pt-1 text-xs text-slate-500">
                        Powered by Happy Tribe Travel.
                    </div>
                </div>
            </div>
            <div class="border-t bg-slate-50/80">
                <div class="max-w-6xl mx-auto flex flex-col items-center justify-between gap-3 px-4 py-4 text-xs text-slate-500 sm:flex-row">
                    <p>&copy; {{ date('Y') }} Greenbus City Tours. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="#contact" class="hover:text-emerald-700">Contact</a>
                        <span class="h-3 w-px bg-slate-300"></span>
                        <span>Secure payments · Licensed local operator</span>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
