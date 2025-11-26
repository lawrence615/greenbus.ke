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

        <footer id="contact" class="border-t bg-white mt-12">
            <div class="max-w-6xl mx-auto px-4 py-8 grid gap-6 md:grid-cols-3 text-sm">
                <div>
                    <h3 class="font-semibold mb-2">About Greenbus</h3>
                    <p class="text-slate-600">City tours curated for visitors to Nairobi. Local guides, safe buses, and transparent pricing.</p>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Contact</h3>
                    <p class="text-slate-600">Email: info@greenbus.ke</p>
                    <p class="text-slate-600">Phone / WhatsApp: +254 700 000 000</p>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Practical</h3>
                    <p class="text-slate-600">All prices in Kenyan Shillings (KES). Instant confirmation for online bookings.</p>
                </div>
            </div>
            <div class="border-t py-4 text-center text-xs text-slate-500 bg-slate-50">
                &copy; {{ date('Y') }} Greenbus City Tours. All rights reserved.
            </div>
        </footer>
    </body>
</html>
