<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name', 'Greenbus'))</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Alpine Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">
    <header class="border-b bg-white/80 backdrop-blur sticky top-0 z-20" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <!-- <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-emerald-700">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-600 text-white font-bold">
                    GB
                </span>
                <span>Greenbus Location Tours</span>
            </a> -->
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Greenbus Location Tours" class="h-8 sm:h-8 w-auto"><span class="font-semibold text-emerald-700">Greenbus Location Tours</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-4 text-sm">
                <!-- Home Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        @click.away="open = false"
                        class="flex items-center gap-1 hover:text-emerald-700 {{ request()->routeIs('home') ? 'text-emerald-700 font-semibold' : 'text-slate-700' }}">
                        Home
                        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-cloak
                        x-transition
                        class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                        <a
                            href="#contact"
                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                            Contact
                        </a>
                        <a
                            href="{{ route('home') }}#how-it-works"
                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                            How it works
                        </a>
                    </div>
                </div>

                <!-- Tours - Always show if there are active locations -->
                @if(isset($location))
                <a
                    href="{{ route('tours.index', $location) }}"
                    class="hover:text-emerald-700 {{ request()->routeIs('tours.*') || request()->routeIs('bookings.*') ? 'text-emerald-700 font-semibold' : 'text-slate-700' }}">
                    Tours
                </a>
                @elseif(isset($activeCities) && $activeCities->count() > 0)
                <div class="relative" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        @click.away="open = false"
                        class="flex items-center gap-1 hover:text-emerald-700 {{ request()->routeIs('tours.*') || request()->routeIs('bookings.*') ? 'text-emerald-700 font-semibold' : 'text-slate-700' }}">
                        Tours
                        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-cloak
                        x-transition
                        class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                        @foreach($activeCities as $activeCity)
                        <a
                            href="{{ route('tours.index', $activeCity->slug) }}"
                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                            {{ $activeCity->name }} Tours
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                <a
                    href="{{ route('faqs.index') }}"
                    class="hover:text-emerald-700 {{ request()->routeIs('faqs.*') ? 'text-emerald-700 font-semibold' : 'text-slate-700' }}">
                    FAQs
                </a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                <a href="{{ route('dashboard') }}" class="hidden md:inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                @else
                @if(isset($location))
                <a href="{{ route('tours.index', $location) }}" class="hidden md:inline-flex items-center px-4 py-2 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                    Book a {{ $location->name }} Tour
                </a>
                @elseif(isset($activeCities) && $activeCities->count() === 1)
                <a href="{{ route('tours.index', $activeCities->first()) }}" class="hidden md:inline-flex items-center px-4 py-2 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                    Book a {{ $activeCities->first()->name }} Tour
                </a>
                @elseif(isset($activeCities) && $activeCities->count() > 1)
                <div class="hidden md:block relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                        Book a Tour
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                        @foreach($activeCities as $activeCity)
                        <a href="{{ route('tours.index', $activeCity->slug) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                            {{ $activeCity->name }} Tours
                        </a>
                        @endforeach
                    </div>
                </div>
                @else
                <a href="#tours" class="hidden md:inline-flex items-center px-4 py-2 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                    Book a Tour
                </a>
                @endif
                @endauth

                <!-- Mobile Menu Button -->
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100"
                    aria-label="Toggle menu">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div
            x-show="mobileMenuOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="md:hidden border-t bg-white">
            <nav class="max-w-6xl mx-auto px-4 py-3 flex flex-col gap-1">
                <!-- Home with sub-items -->
                <div x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                        Home
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="pl-6 space-y-1">
                        <a
                            href="#contact"
                            @click="mobileMenuOpen = false"
                            class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">
                            Contact
                        </a>
                        <a
                            href="{{ route('home') }}#how-it-works"
                            @click="mobileMenuOpen = false"
                            class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">
                            How it works
                        </a>
                    </div>
                </div>

                <!-- Tours - Always show if there are active locations -->
                @if(isset($location))
                <a
                    href="{{ route('tours.index', $location) }}"
                    @click="mobileMenuOpen = false"
                    class="px-3 py-2 rounded-lg {{ request()->routeIs('tours.*') || request()->routeIs('bookings.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    Tours
                </a>
                @elseif(isset($activeCities) && $activeCities->count() > 0)
                <div x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-lg {{ request()->routeIs('tours.*') || request()->routeIs('bookings.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                        Tours
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="pl-6 space-y-1">
                        @foreach($activeCities as $activeCity)
                        <a
                            href="{{ route('tours.index', $activeCity->slug) }}"
                            @click="mobileMenuOpen = false"
                            class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">
                            {{ $activeCity->name }} Tours
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                <a
                    href="{{ route('faqs.index') }}"
                    @click="mobileMenuOpen = false"
                    class="px-3 py-2 rounded-lg {{ request()->routeIs('faqs.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    FAQ
                </a>
                @auth
                <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="mt-2 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                @else
                @if(isset($location))
                <a href="{{ route('tours.index', $location) }}" @click="mobileMenuOpen = false" class="mt-2 inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                    Book a {{ $location->name }} Tour
                </a>
                @elseif(isset($activeCities) && $activeCities->count() === 1)
                <a href="{{ route('tours.index', $activeCities->first()) }}" @click="mobileMenuOpen = false" class="mt-2 inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                    Book a {{ $activeCities->first()->name }} Tour
                </a>
                @elseif(isset($activeCities) && $activeCities->count() > 1)
                <div class="mt-2 space-y-1">
                    <p class="text-xs text-slate-500 font-medium px-3">Book a Tour</p>
                    @foreach($activeCities as $activeCity)
                    <a href="{{ route('tours.index', $activeCity->slug) }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-emerald-700 bg-emerald-50 hover:bg-emerald-100 font-medium">
                        {{ $activeCity->name }} Tours
                    </a>
                    @endforeach
                </div>
                @else
                <a href="#tours" @click="mobileMenuOpen = false" class="mt-2 inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow hover:bg-emerald-700">
                    Book a Tour
                </a>
                @endif
                @endauth
            </nav>
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
        <!-- Desktop Footer (md and up) -->
        <div class="hidden md:grid max-w-6xl mx-auto px-4 py-10 lg:py-12 gap-8 md:grid-cols-4 text-sm">
            <div class="space-y-3">
                <h3 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">About</h3>
                <p class="text-slate-600 leading-relaxed">
                    A licensed tours and safaris company offering memorable, sustainable, and community-supportive travel experiences across Kenya. Explore the country the HAPPY way.
                </p>
                @if(isset($location))
                <a href="{{ route('tours.index', $location) }}" class="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-emerald-700">
                    <span>Browse {{ $location->name }} tours</span>
                    <span class="text-[11px]">→</span>
                </a>
                @elseif(isset($activeCities) && $activeCities->count() >= 1)
                <a href="{{ route('tours.index', $activeCities->first()) }}" class="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-emerald-700">
                    <span>Browse {{ $activeCities->first()->name }} tours</span>
                    <span class="text-[11px]">→</span>
                </a>
                @else
                <a href="#tours" class="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-emerald-700">
                    <span>Browse tours</span>
                    <span class="text-[11px]">→</span>
                </a>
                @endif
            </div>

            <div class="space-y-3">
                <h3 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Explore</h3>
                <ul class="space-y-2 text-slate-600">
                    <li>
                        <a href="{{ route('home') }}#how-it-works" class="hover:text-emerald-700">How Greenbus works</a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#reviews" class="hover:text-emerald-700">Guest reviews</a>
                    </li>
                    <li>
                        @if(isset($location))
                        <a href="{{ route('tours.index', $location) }}" class="hover:text-emerald-700">All {{ $location->name }} tours</a>
                        @elseif(isset($activeCities) && $activeCities->count() >= 1)
                        <a href="{{ route('tours.index', $activeCities->first()) }}" class="hover:text-emerald-700">All {{ $activeCities->first()->name }} tours</a>
                        @else
                        <a href="#tours" class="hover:text-emerald-700">Featured tours</a>
                        @endif
                    </li>
                    <li>
                        <a href="{{ route('faqs.index') }}" class="hover:text-emerald-700">FAQs</a>
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
                        <span>+254 726 455 000</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                            <span class="text-[11px]">📍</span>
                        </span>
                        <span>Lotus Plaza, Parklands Nairobi</span>
                    </li>
                </ul>
                <div class="flex items-center gap-3 pt-2">
                    <a href="https://www.instagram.com/happytribe.travel/?igsh=cDJ4Y3g5MTBvNXRm&utm_source=qr#" target="_blank" rel="noopener noreferrer" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-linear-to-br from-purple-500 via-pink-500 to-orange-400 text-white hover:from-purple-600 hover:via-pink-600 hover:to-orange-500 transition-all duration-200" aria-label="Instagram">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    <a href="https://www.youtube.com/@motherlanddiaries" target="_blank" rel="noopener noreferrer" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 transition-colors duration-200" aria-label="YouTube">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/in/dennisconsole/?trk=universal-search-cluster" target="_blank" rel="noopener noreferrer" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200" aria-label="LinkedIn">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="space-y-3">
                <h3 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Practical info</h3>
                <ul class="space-y-2 text-slate-600">
                    <li>All prices in USD.</li>
                    <li>Instant confirmation for online bookings.</li>
                    <li>Free cancellation up to 24 hours before departure.</li>
                </ul>
                <div class="pt-1 text-xs text-slate-500">
                    Powered by <a href="https://happytribe.ke/" target="_blank" rel="noopener noreferrer" class="font-semibold text-[#ffc107] hover:text-yellow-600 transition-colors duration-200">Happy Tribe Travel</a>
                </div>
            </div>
        </div>

        <!-- Mobile Footer with Tabs (below md) -->
        <div class="md:hidden" x-data="{ activeTab: 'about' }">
            <!-- Tab Content -->
            <div class="px-4 py-6 min-h-[180px] text-sm">
                <!-- About Tab -->
                <div x-show="activeTab === 'about'" x-cloak class="space-y-3">
                    <h3 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">About</h3>
                    <p class="text-slate-600 leading-relaxed">
                        A licensed tours and safaris company offering memorable, sustainable, and community-supportive travel experiences across Kenya. Explore the country the HAPPY way.
                    </p>
                    @if(isset($location))
                    <a href="{{ route('tours.index', $location) }}" class="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-emerald-700">
                        <span>Browse {{ $location->name }} tours</span>
                        <span class="text-[11px]">→</span>
                    </a>
                    @elseif(isset($activeCities) && $activeCities->count() >= 1)
                    <a href="{{ route('tours.index', $activeCities->first()) }}" class="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-emerald-700">
                        <span>Browse {{ $activeCities->first()->name }} tours</span>
                        <span class="text-[11px]">→</span>
                    </a>
                    @else
                    <a href="#tours" class="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-emerald-700">
                        <span>Browse tours</span>
                        <span class="text-[11px]">→</span>
                    </a>
                    @endif
                </div>

                <!-- Explore Tab -->
                <div x-show="activeTab === 'explore'" x-cloak class="space-y-3">
                    <h3 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Explore</h3>
                    <ul class="space-y-2 text-slate-600">
                        <li>
                            <a href="{{ route('home') }}#how-it-works" class="hover:text-emerald-700">How Greenbus works</a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}#reviews" class="hover:text-emerald-700">Guest reviews</a>
                        </li>
                        <li>
                            @if(isset($location))
                            <a href="{{ route('tours.index', $location) }}" class="hover:text-emerald-700">All {{ $location->name }} tours</a>
                            @elseif(isset($activeCities) && $activeCities->count() >= 1)
                            <a href="{{ route('tours.index', $activeCities->first()) }}" class="hover:text-emerald-700">All {{ $activeCities->first()->name }} tours</a>
                            @else
                            <a href="#tours" class="hover:text-emerald-700">Featured tours</a>
                            @endif
                        </li>
                        <li>
                            <a href="{{ route('faqs.index') }}" class="hover:text-emerald-700">FAQs</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Tab -->
                <div x-show="activeTab === 'contact'" x-cloak class="space-y-3">
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
                            <span>+254 726 455 000</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                                <span class="text-[11px]">📍</span>
                            </span>
                            <span>Lotus Plaza, Parklands Nairobi</span>
                        </li>
                    </ul>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="https://www.instagram.com/happytribe.travel/?igsh=cDJ4Y3g5MTBvNXRm&utm_source=qr#" target="_blank" rel="noopener noreferrer" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-linear-to-br from-purple-500 via-pink-500 to-orange-400 text-white hover:from-purple-600 hover:via-pink-600 hover:to-orange-500 transition-all duration-200" aria-label="Instagram">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                        <a href="https://www.youtube.com/@motherlanddiaries" target="_blank" rel="noopener noreferrer" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 transition-colors duration-200" aria-label="YouTube">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/in/dennisconsole/?trk=universal-search-cluster" target="_blank" rel="noopener noreferrer" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200" aria-label="LinkedIn">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Info Tab -->
                <div x-show="activeTab === 'info'" x-cloak class="space-y-3">
                    <h3 class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Practical info</h3>
                    <ul class="space-y-2 text-slate-600">
                        <li>All prices in USD.</li>
                        <li>Instant confirmation for online bookings.</li>
                        <li>Free cancellation up to 24 hours before departure.</li>
                    </ul>
                    <div class="pt-1 text-xs text-slate-500">
                        Powered by <a href="https://happytribe.ke/" target="_blank" rel="noopener noreferrer" class="font-semibold text-[#ffc107] hover:text-yellow-600 transition-colors duration-200">Happy Tribe Travel</a>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="grid grid-cols-4 border-t bg-slate-50">
                <button
                    @click="activeTab = 'about'"
                    :class="activeTab === 'about' ? 'text-emerald-600 border-t-2 border-emerald-600 bg-white' : 'text-slate-500'"
                    class="flex flex-col items-center gap-1 py-3 text-[10px] font-medium uppercase tracking-wide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    About
                </button>
                <button
                    @click="activeTab = 'explore'"
                    :class="activeTab === 'explore' ? 'text-emerald-600 border-t-2 border-emerald-600 bg-white' : 'text-slate-500'"
                    class="flex flex-col items-center gap-1 py-3 text-[10px] font-medium uppercase tracking-wide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    Explore
                </button>
                <button
                    @click="activeTab = 'contact'"
                    :class="activeTab === 'contact' ? 'text-emerald-600 border-t-2 border-emerald-600 bg-white' : 'text-slate-500'"
                    class="flex flex-col items-center gap-1 py-3 text-[10px] font-medium uppercase tracking-wide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contact
                </button>
                <button
                    @click="activeTab = 'info'"
                    :class="activeTab === 'info' ? 'text-emerald-600 border-t-2 border-emerald-600 bg-white' : 'text-slate-500'"
                    class="flex flex-col items-center gap-1 py-3 text-[10px] font-medium uppercase tracking-wide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Info
                </button>
            </div>
        </div>
        <div class="border-t bg-slate-50/80">
            <div class="max-w-6xl mx-auto flex flex-col items-center justify-between gap-3 px-4 py-4 text-xs text-slate-500 sm:flex-row">
                <p>&copy; {{ date('Y') }} Greenbus Location Tours. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#contact" class="hover:text-emerald-700">Contact</a>
                    <span class="h-3 w-px bg-slate-300"></span>
                    <span>Secure payments · Licensed local operator</span>
                </div>
            </div>
        </div>
    </footer>

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

    @stack('scripts')
</body>