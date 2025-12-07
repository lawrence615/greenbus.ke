@extends('layouts.app')

@section('title', 'Discover Nairobi with Greenbus City Tours')

@section('content')
<section class="bg-gradient-to-b from-emerald-50 to-slate-50">
    <div class="max-w-6xl mx-auto px-4 py-12 grid gap-10 md:grid-cols-2 items-center">
        <div>
            <p class="text-sm font-semibold text-emerald-700 mb-2">Nairobi City Tours</p>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                Discover Nairobi with local guides and a comfortable Greenbus.
            </h1>
            <p class="text-slate-700 mb-6">
                Perfect for first-time visitors. See the highlights, learn the stories behind the city, and travel safely
                with experienced local guides.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ isset($city) ? route('tours.index', $city) : '#tours' }}" class="inline-flex items-center px-5 py-3 rounded-full bg-emerald-600 text-white font-semibold shadow hover:bg-emerald-700">
                    View Nairobi tours & buy tickets
                </a>
                <a href="#how-it-works" class="inline-flex items-center px-5 py-3 rounded-full border border-emerald-200 text-emerald-800 font-semibold hover:bg-emerald-50">
                    How the tours work
                </a>
            </div>
        </div>
        <div class="relative">
            <div class="aspect-video rounded-3xl bg-slate-200 overflow-hidden shadow-lg">
                <img src="{{ asset('images/greenbus_ke_1.jpg') }}" alt="Nairobi city tour" class="w-full h-full object-cover">
            </div>
            <div class="absolute -bottom-4 -left-4 bg-white shadow-lg rounded-xl px-4 py-3 text-xs md:text-sm">
                <p class="font-semibold">Small or large group city tours</p>
                <p class="text-slate-600">Buy your ticket and enjoy the comfort of a Greenbus.</p>
            </div>
        </div>
    </div>
</section>

<section id="how-it-works" class="bg-white/80 border-y border-slate-100 mt-8">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <p class="text-xs uppercase tracking-[0.15em] text-emerald-700 mb-2">Simple steps</p>
        <h2 class="text-2xl md:text-3xl font-semibold mb-6">How Greenbus tours work</h2>
        <div class="grid gap-6 md:grid-cols-4 text-sm">
            <div class="bg-emerald-50 rounded-xl shadow-sm p-5 border border-emerald-100">
                <p class="font-semibold mb-1">1. Choose your tour</p>
                <p class="text-slate-600">Browse Nairobi city tours and pick the date that works best for you.</p>
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
                <p class="font-semibold mb-1">4. Enjoy the city</p>
                <p class="text-slate-600">Meet your guide at the pickup point and discover Nairobi.</p>
            </div>
        </div>
    </div>
</section>

<section id="tours" class="max-w-6xl mx-auto px-4 pb-12 pt-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold">Featured Nairobi tours</h2>
        @if ($hasMoreTours && isset($city))
        <a href="{{ route('tours.index', $city) }}" class="text-sm text-emerald-700 hover:underline">View all Nairobi tours</a>
        @endif
    </div>

    @if ($featuredTours->isEmpty())
    <p class="text-slate-600 text-sm">Tours will appear here once you add them to the system.</p>
    @else
    <div class="grid gap-6 md:grid-cols-3">
        @foreach ($featuredTours as $tour)
        <article class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="h-40 bg-slate-200">
                @php
                $cover = $tour->images->firstWhere('is_cover', true) ?? $tour->images->first();
                @endphp
                @if ($cover)
                <img src="{{ $cover->path }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">Tour photo coming soon</div>
                @endif
            </div>
            <div class="p-4 flex-1 flex flex-col">
                <h3 class="font-semibold mb-1 text-sm md:text-base">{{ $tour->title }}</h3>
                <p class="text-xs text-slate-600 mb-2 line-clamp-3">{!! $tour->short_description !!}</p>
                <p class="text-sm font-semibold text-emerald-700 mb-3">
                    From KES {{ number_format($tour->base_price_adult) }} per adult
                </p>
                <a href="{{ route('tours.show', [$tour->city, $tour]) }}" class="mt-auto inline-flex items-center justify-center px-4 py-2 rounded-full bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">
                    View details & buy tickets
                </a>
            </div>
        </article>
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