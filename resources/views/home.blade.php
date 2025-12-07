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

<section id="reviews" class="bg-slate-900 text-slate-50 mt-8">
    <div class="max-w-6xl mx-auto px-4 py-12 grid gap-10 md:grid-cols-3 items-start">
        <div class="space-y-4 md:space-y-5 md:col-span-1">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-300">Reviews</p>
            <h2 class="text-2xl md:text-3xl font-semibold">
                Travellers love starting Nairobi with Greenbus
            </h2>
            <div class="flex items-center gap-3 text-sm">
                <div class="flex items-center gap-0.5 text-emerald-300 text-base">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <p class="text-slate-200">
                    4.8 / 5 from 120+ guests
                </p>
            </div>
            <p class="text-xs text-slate-300 max-w-xs">
                Feedback from visitors who used Greenbus for their first day in Nairobi.
            </p>
        </div>

        <div class="md:col-span-2 overflow-hidden">
            <div class="relative" x-data="{}">
                <div class="overflow-hidden" id="reviews-carousel">
                    <div class="flex transition-transform duration-500 ease-out w-full" data-review-track>
                        <div class="w-full shrink-0 px-1">
                            <div class="grid gap-3 md:grid-cols-2">
                                <article class="bg-slate-800 rounded-xl p-5 border border-slate-700 h-full flex flex-col">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-semibold">
                                            A
                                        </div>
                                        <div class="text-xs">
                                            <p class="font-semibold text-slate-50">Татьяна</p>
                                            <p class="text-slate-300">Kenya · Nairobi highlights</p>
                                        </div>
                                    </div>
                                    <p class="text-sm mb-3">“It was wonderful, I learned a lot of information about the center of Nairobi, visited the local market, took a walk and admired the beautiful views”</p>
                                    <p class="text-[11px] text-slate-400 mt-auto">December 3, 2025</p>
                                </article>

                                <article class="bg-slate-800 rounded-xl p-5 border border-slate-700 h-full flex flex-col">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-semibold">
                                            M
                                        </div>
                                        <div class="text-xs">
                                            <p class="font-semibold text-slate-50">Riley</p>
                                            <p class="text-slate-300">UK · City & markets</p>
                                        </div>
                                    </div>
                                    <p class="text-sm mb-3">“
We had such a good time with Jeff! Not only did he explain the history and sites, we really felt like we got to learn about real life in Nairobi with him. Would recommend!”</p>
                                    <p class="text-[11px] text-slate-400 mt-auto">November 14, 2025</p>
                                </article>
                            </div>
                        </div>

                        <div class="w-full shrink-0 px-1">
                            <div class="grid gap-3 md:grid-cols-2">
                                <article class="bg-slate-800 rounded-xl p-5 border border-slate-700 h-full flex flex-col">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-semibold">
                                            K
                                        </div>
                                        <div class="text-xs">
                                            <p class="font-semibold text-slate-50">Jimrey</p>
                                            <p class="text-slate-300">Saudi Arabia · Museum & markets</p>
                                        </div>
                                    </div>
                                    <p class="text-sm mb-3">“This wasn’t just a tour, it felt like a friendly meetup with our amazing guide, Sharon. She took the time to show us around and explain the beauty of Nairobi’s central district, making the experience truly special.”</p>
                                    <p class="text-[11px] text-slate-400 mt-auto">December 2, 2025</p>
                                </article>

                                <article class="bg-slate-800 rounded-xl p-5 border border-slate-700 h-full flex flex-col">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-semibold">
                                            S
                                        </div>
                                        <div class="text-xs">
                                            <p class="font-semibold text-slate-50">adrienne</p>
                                            <p class="text-slate-300">UAE · Evening city lights</p>
                                        </div>
                                    </div>
                                    <p class="text-sm mb-3">“Jeff was a great tour guide. a short vur informative tour of nartobi. would definitely recommend.”</p>
                                    <p class="text-[11px] text-slate-400 mt-auto">November 10, 2025</p>
                                </article>
                            </div>
                        </div>

                        <div class="w-full shrink-0 px-1">
                            <div class="grid gap-3 md:grid-cols-2">
                                <article class="bg-slate-800 rounded-xl p-5 border border-slate-700 h-full flex flex-col">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-semibold">
                                            J
                                        </div>
                                        <div class="text-xs">
                                            <p class="font-semibold text-slate-50">Sam</p>
                                            <p class="text-slate-300">Kenya · City refresher</p>
                                        </div>
                                    </div>
                                    <p class="text-sm mb-3">“We had an amazing day. Simon was lovely and inluded everything from souvenirs to sights with plenty of cultural background and room for plenty of questions. Highly recommend.”</p>
                                    <p class="text-[11px] text-slate-400 mt-auto">November 24, 2025 </p>
                                </article>

                                <article class="bg-slate-800 rounded-xl p-5 border border-slate-700 h-full flex flex-col">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-xs font-semibold">
                                            L
                                        </div>
                                        <div class="text-xs">
                                            <p class="font-semibold text-slate-50">SHAHROL</p>
                                            <p class="text-slate-300">Sweden · Short layover tour</p>
                                        </div>
                                    </div>
                                    <p class="text-sm mb-3">“My wife and I joined the walking tour with Jeff and it was an amazing experience. Jeff was friendly, knowledgeable and very enthusiastic throughout the tour.”</p>
                                    <p class="text-[11px] text-slate-400 mt-auto">November 19, 2025</p>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center gap-2 mt-4" data-review-dots>
                    <button type="button" class="w-2 h-2 rounded-full bg-emerald-500" aria-label="Go to review 1"></button>
                    <button type="button" class="w-2 h-2 rounded-full bg-emerald-900" aria-label="Go to review 2"></button>
                    <button type="button" class="w-2 h-2 rounded-full bg-emerald-900" aria-label="Go to review 3"></button>
                </div>
            </div>
        </div>
    </div>
</section>
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