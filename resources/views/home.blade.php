@extends('layouts.app')

@section('title', 'Discover Nairobi with Greenbus Location Tours')

@section('content')
<section class="bg-gradient-to-br from-emerald-50 via-emerald-100 to-slate-50">
    <div class="max-w-6xl mx-auto px-4 py-16 lg:py-24 grid gap-12 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)] items-center">
        <!-- Left: Headline & copy -->
        <div>
            <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1 text-xs font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Nairobi location tours made simple
            </span>

            <h1 class="mt-5 text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-slate-900">
                Your ticket to
                <span class="block bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-700 bg-clip-text text-transparent">
                    unforgettable Nairobi tours
                </span>
            </h1>

            <p class="mt-4 text-sm sm:text-base text-slate-700 max-w-xl">
                Join small-group or private location tours that blend must-see sights with local stories. Ride in a
                comfortable Greenbus, meet expert guides, and explore Nairobi at an easy, relaxed pace.
            </p>

            <div class="mt-6 flex flex-wrap items-center gap-3 justify-center sm:justify-start text-center sm:text-left">
                <a href="{{ isset($location) ? route('tours.index', $location) : '#tours' }}" class="inline-flex items-center px-6 py-3 rounded-full bg-emerald-600 text-white text-sm font-semibold shadow-md shadow-emerald-500/30 hover:bg-emerald-700">
                    Browse Nairobi tours
                </a>
                <a href="#how-it-works" class="inline-flex items-center px-5 py-3 rounded-full bg-white/80 text-sm font-semibold text-slate-900 border border-slate-200 hover:bg-slate-50">
                    How it works
                </a>
            </div>

            <div class="mt-5 flex flex-wrap gap-4 text-xs sm:text-[13px] text-slate-600">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-[11px] font-semibold">4.9</span>
                    <span>Rated highly by recent Greenbus guests</span>
                </div>
                <div class="flex items-center gap-2">
                    <!-- <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-800 text-[11px] font-semibold">✔</span> -->
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-900 text-white text-[11px] font-semibold">✔</span>
                    <span>Instant confirmation & mobile tickets</span>
                </div>
            </div>
        </div>

        <!-- Right: Gradient panel with layered tour images -->
        <div class="relative">
            <div class="rounded-[2rem] bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 p-1.5 shadow-xl shadow-emerald-600/40">
                <div class="relative h-[340px] sm:h-[420px] rounded-[1.7rem] bg-slate-950/10 overflow-hidden flex items-center justify-center">
                    <!-- Back image card -->
                    <div class="absolute right-2 sm:right-6 top-4 sm:top-8 w-40 sm:w-48 h-52 sm:h-60 rounded-3xl overflow-hidden shadow-[0_18px_45px_rgba(15,23,42,0.6)] bg-slate-800/20 rotate-1 origin-bottom-left">
                        <img src="{{ asset('images/greenbus_ke_2.jpg') }}" alt="Guests enjoying a Nairobi tour" class="w-full h-full object-cover">
                    </div>

                    <!-- Front image card -->
                    <div class="absolute left-2 sm:left-6 bottom-2 sm:bottom-8 w-48 sm:w-60 h-60 sm:h-72 rounded-3xl overflow-hidden shadow-[0_22px_55px_rgba(15,23,42,0.75)] bg-slate-800/20 -rotate-1 origin-top-right">
                        <img src="{{ asset('images/greenbus_ke_1.jpg') }}" alt="Greenbus location tour" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="how-it-works" class="bg-white/80 border-y border-slate-100 mt-0">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <p class="text-xs uppercase tracking-[0.15em] text-emerald-700 mb-2">Simple steps</p>
        <h2 class="text-2xl md:text-3xl font-semibold mb-6">How Greenbus tours work</h2>
        <div class="grid gap-6 md:grid-cols-4 text-sm">
            <div class="bg-emerald-50 rounded-xl shadow-sm p-5 border border-emerald-100">
                <p class="font-semibold mb-1">1. Choose your tour</p>
                <p class="text-slate-600">Browse Nairobi location tours and pick the date that works best for you.</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-100">
                <p class="font-semibold mb-1">2. Buy tickets online</p>
                <p class="text-slate-600">Enter your group details and pay securely with your card.</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-slate-100">
                <p class="font-semibold mb-1">3. Get your ticket</p>
                <p class="text-slate-600">Receive a confirmation email and PDF ticket with all the details.</p>
            </div>
            <div class="bg-emerald-50 rounded-xl shadow-sm p-5 border border-emerald-100">
                <p class="font-semibold mb-1">4. Enjoy the location</p>
                <p class="text-slate-600">Meet your guide at the pickup point and discover Nairobi.</p>
            </div>
        </div>
    </div>
</section>

<section id="tours" class="max-w-6xl mx-auto px-4 pb-12 pt-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold">Featured Nairobi tours</h2>
        @if ($hasMoreTours && isset($location))
        <a href="{{ route('tours.index', $location) }}" class="text-sm text-emerald-700 hover:underline">View all Nairobi tours</a>
        @endif
    </div>

    @if ($featuredTours->isEmpty())
    <p class="text-slate-600 text-sm">No tours are available at the moment. Please check back soon for upcoming experiences.</p>
    @else
    <div class="grid gap-6 md:grid-cols-3">
        @foreach ($featuredTours as $tour)
            <x-featured-tour-card :tour="$tour" />
        @endforeach
    </div>
    @endif
</section>

<!-- Reviews -->
<x-reviews :testimonials="$testimonials" />

<section class="bg-transparent">
    <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="text-center mb-8">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-2">Our partners</p>
            <div class="mx-auto h-0.5 w-8 rounded-full bg-emerald-400"></div>
        </div>

        <div class="grid gap-4 grid-cols-2 sm:grid-cols-4 items-center">
            <a href="https://www.getyourguide.com/happy-tribe-travel-s274530/" target="_blank" rel="noopener noreferrer" class="flex h-32 w-32 sm:h-36 sm:w-36 md:h-40 md:w-40 mx-auto items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm">
                <img src="{{ asset('images/getyourguide_logo.png') }}" alt="GetYourGuide" class="max-h-20 sm:max-h-24 md:max-h-28 w-auto object-contain">
            </a>
            <a href="https://www.tripadvisor.com/Attraction_Review-g294207-d23499279-Reviews-Happy_Tribe_Travel-Nairobi.html" target="_blank" rel="noopener noreferrer" class="flex h-32 w-32 sm:h-36 sm:w-36 md:h-40 md:w-40 mx-auto items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm">
                <img src="{{ asset('images/tripadvisor_logo.png') }}" alt="Tripadvisor" class="max-h-20 sm:max-h-24 md:max-h-28 w-auto object-contain">
            </a>
            <a href="https://www.safaribookings.com/" target="_blank" rel="noopener noreferrer" class="flex h-32 w-32 sm:h-36 sm:w-36 md:h-40 md:w-40 mx-auto items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm">
                <img src="{{ asset('images/safaribookings_logo.png') }}" alt="SafariBookings" class="max-h-20 sm:max-h-24 md:max-h-28 w-auto object-contain">
            </a>
            <a href="https://www.google.com/search?q=happy+tribe+travel&sca_esv=467a6a31ff5f6688&rlz=1C5CHFA_enKE1132KE1132&sxsrf=AE3TifObLfkFt7EBVATPnaeLmIszSejGDg%3A1762176315934&ei=O60IaZi7OIa-i-gPtOSI2AM&ved=0ahUKEwjYxN6qitaQAxUG3wIHHTQyAjsQ4dUDCBE&uact=5&oq=happy+tribe+travel&gs_lp=Egxnd3Mtd2l6LXNlcnAiEmhhcHB5IHRyaWJlIHRyYXZlbDIFEAAYgAQyBRAAGIAEMgYQABgWGB4yBhAAGBYYHjIGEAAYFhgeMgYQABgWGB4yAhAmMgsQABiABBiGAxiKBTILEAAYgAQYhgMYigUyBRAAGO8FSLQgUOkDW0ecAN4AZABAJgB0AKgAcgQqgEFMi03LjG4AQPIAQD4AQGYAgugAo0RwgIHECMYsAMYJ8ICChAAGLADGNYEGEfCAg0QABiABBiwAxhDGIoFwgIEECMYJ8ICChAAGIAEGBQYhwLCAgcQABiABBgKwgIKEAAYgAQYQxiKBcICCxAuGIAEGNEDGMcBwgIIEAAYgAQYogTCAggQABiiBBiJBcICBxAAGIAEGA3CAgYQABgNGB6YAwCIBgGQBgiSBwczLjAuNi4yoAeXM7IHBTItNi4yuAf3EMIHBjItMTAuMcgHNg&sclient=gws-wiz-serp" target="_blank" rel="noopener noreferrer" class="flex h-32 w-32 sm:h-36 sm:w-36 md:h-40 md:w-40 mx-auto items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm">
                <img src="{{ asset('images/google_logo.png') }}" alt="Google" class="max-h-20 sm:max-h-24 md:max-h-28 w-auto object-contain">
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function() {
        const container = document.querySelector('[id="reviews-carousel"]');
        if (!container) return;

        const track = container.querySelector('[data-review-track]');
        const dots = document.querySelectorAll('[data-review-dots] button');
        if (!track || !dots.length) return;

        let current = 0;
        const total = track.children.length;

        function goTo(index) {
            current = (index + total) % total;
            const offset = -current * 100;
            track.style.transform = 'translateX(' + offset + '%)';
            dots.forEach((dot, i) => {
                dot.classList.toggle('bg-emerald-500', i === current);
                dot.classList.toggle('bg-emerald-900', i !== current);
            });
        }

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                goTo(i);
                resetAuto();
            });
        });

        let auto = setInterval(() => goTo(current + 1), 7000);

        function resetAuto() {
            clearInterval(auto);
            auto = setInterval(() => goTo(current + 1), 7000);
        }

        let startX = null;
        container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        });
        container.addEventListener('touchend', (e) => {
            if (startX === null) return;
            const diff = e.changedTouches[0].clientX - startX;
            if (Math.abs(diff) > 50) {
                goTo(current + (diff < 0 ? 1 : -1));
                resetAuto();
            }
            startX = null;
        });

        goTo(0);
    })();
</script>
@endpush