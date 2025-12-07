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
                    class="hover:text-emerald-700 {{ request()->routeIs('home') ? 'text-emerald-700 font-semibold' : 'text-slate-700' }}">
                    Home
                </a>
                @isset($city)
                <a
                    href="{{ route('tours.index', $city) }}"
                    class="hover:text-emerald-700 {{ request()->routeIs('tours.*') || request()->routeIs('bookings.*') ? 'text-emerald-700 font-semibold' : 'text-slate-700' }}">
                    Tours
                </a>
                @endisset
                <a
                    href="#contact"
                    class="hover:text-emerald-700 text-slate-700">
                    Contact
                </a>
                <a
                    href="{{ route('home') }}#how-it-works"
                    class="hover:text-emerald-700 text-slate-700">
                    How it works
                </a>
            </nav>
            <a href="{{ isset($city) ? route('tours.index', $city) : '#tours' }}" class="hidden sm:inline-flex items-center px-4 py-2 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                Book a Nairobi Tour
            </a>
        </div>
    </header>

    <main class="flex-1">
        @hasSection('breadcrumb')
        <div class="max-w-6xl mx-auto px-4 pt-10">
            @yield('breadcrumb')
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
                        <a href="{{ route('home') }}#reviews" class="hover:text-emerald-700">Guest reviews</a>
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
                        <span>Happytribetravel@gmail.com</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                            <span class="text-[11px]">📞</span>
                        </span>
                        <span>+254 726 455 000<br><span class="text-xs text-slate-500">Call or WhatsApp between 08:00 – 20:00 EAT</span></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                            <span class="text-[11px]">📍</span>
                        </span>
                        <span>Lotus Plaza, Parklands Nairobi</span>
                    </li>
                </ul>
                <div class="flex items-center gap-3 pt-2">
                    <a href="https://www.instagram.com/happytribe.travel/?igsh=cDJ4Y3g5MTBvNXRm&utm_source=qr#" target="_blank" rel="noopener noreferrer" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 hover:bg-emerald-100" aria-label="Instagram">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://www.youtube.com/@motherlanddiaries" target="_blank" rel="noopener noreferrer" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 hover:bg-emerald-100" aria-label="YouTube">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/in/dennisconsole/?trk=universal-search-cluster" target="_blank" rel="noopener noreferrer" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 hover:bg-emerald-100" aria-label="LinkedIn">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                </div>
            </div>

            <div class="space-y-3">
                <h3 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Practical info</h3>
                <ul class="space-y-2 text-slate-600">
                    <li>All prices in Kenyan Shillings (KES).</li>
                    <li>Instant confirmation for online bookings.</li>
                    <li>Free cancellation up to 24 hours before departure.</li>
                </ul>
                <div class="pt-1 text-xs text-slate-500">
                    Powered by <a href="https://happytribe.ke/" target="_blank" rel="noopener noreferrer" class="hover:text-emerald-700">Happy Tribe Travel</a>.
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

<script>
    @if(session('success'))
    window.__flashSuccess = @json(session('success'));
    @endif
    @if(session('error'))
    window.__flashError = @json(session('error'));
    @endif
    @if(session('status'))
    window.__flashInfo = @json(session('status'));
    @endif
</script>

</html>