@extends('layouts.app')

@section('title', 'Buy tickets – ' . $tour->title)

@section('content')
<section class="max-w-3xl mx-auto px-4 py-10">
    <nav class="text-xs text-slate-500 mb-3" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('tours.index', $city) }}" class="hover:underline">{{ $city->name }} tours</a></li>
            <li>/</li>
            <li><a href="{{ route('tours.show', [$city, $tour]) }}" class="hover:underline">{{ $tour->title }}</a></li>
            <li>/</li>
            <li><span class="text-slate-600">Buy tickets</span></li>
        </ol>
    </nav>

    <h1 class="text-2xl font-semibold mb-1">Buy tickets for {{ $tour->title }}</h1>
    <p class="text-sm text-slate-600 mb-6">Fill in your details to buy tickets for this tour. You’ll see payment options on the next step in a later version.</p>

    <form method="POST" action="{{ route('bookings.store', [$city, $tour]) }}" class="space-y-6 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        @csrf

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="date" class="block text-xs font-semibold text-slate-700 mb-1">Tour date</label>
                <input type="date" id="date" name="date" value="{{ old('date') }}" class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                @error('date')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="time" class="block text-xs font-semibold text-slate-700 mb-1">Preferred time (optional)</label>
                <input type="time" id="time" name="time" value="{{ old('time', $tour->starts_at_time) }}" class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                @error('time')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label for="adults" class="block text-xs font-semibold text-slate-700 mb-1">Adults</label>
                <div class="flex items-center rounded-md border border-slate-300 bg-white overflow-hidden">
                    <button type="button" onclick="updateTraveller('adults', -1, 1)" class="px-3 py-2 text-slate-700 hover:bg-slate-100 text-sm cursor-pointer">-</button>
                    <input type="number" min="1" id="adults" name="adults" value="{{ old('adults', 1) }}" class="w-full border-0 text-center text-sm focus:outline-none" required>
                    <button type="button" onclick="updateTraveller('adults', 1, 1)" class="px-3 py-2 text-slate-700 hover:bg-slate-100 text-sm cursor-pointer">+</button>
                </div>
                @error('adults')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="children" class="block text-xs font-semibold text-slate-700 mb-1">Children</label>
                <div class="flex items-center rounded-md border border-slate-300 bg-white overflow-hidden">
                    <button type="button" onclick="updateTraveller('children', -1, 0)" class="px-3 py-2 text-slate-700 hover:bg-slate-100 text-sm cursor-pointer">-</button>
                    <input type="number" min="0" id="children" name="children" value="{{ old('children', 0) }}" class="w-full border-0 text-center text-sm focus:outline-none">
                    <button type="button" onclick="updateTraveller('children', 1, 0)" class="px-3 py-2 text-slate-700 hover:bg-slate-100 text-sm cursor-pointer">+</button>
                </div>
                @error('children')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="infants" class="block text-xs font-semibold text-slate-700 mb-1">Infants</label>
                <div class="flex items-center rounded-md border border-slate-300 bg-white overflow-hidden">
                    <button type="button" onclick="updateTraveller('infants', -1, 0)" class="px-3 py-2 text-slate-700 hover:bg-slate-100 text-sm cursor-pointer">-</button>
                    <input type="number" min="0" id="infants" name="infants" value="{{ old('infants', 0) }}" class="w-full border-0 text-center text-sm focus:outline-none">
                    <button type="button" onclick="updateTraveller('infants', 1, 0)" class="px-3 py-2 text-slate-700 hover:bg-slate-100 text-sm cursor-pointer">+</button>
                </div>
                @error('infants')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="customer_name" class="block text-xs font-semibold text-slate-700 mb-1">Full name</label>
                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                @error('customer_name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="customer_email" class="block text-xs font-semibold text-slate-700 mb-1">Email address</label>
                <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                @error('customer_email')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="customer_phone" class="block text-xs font-semibold text-slate-700 mb-1">Phone / WhatsApp (with country code)</label>
                <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="e.g. +254 700 000 000">
                @error('customer_phone')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="pickup_location" class="block text-xs font-semibold text-slate-700 mb-1">Hotel / pickup location (optional)</label>
                <input type="text" id="pickup_location" name="pickup_location" value="{{ old('pickup_location') }}" class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="e.g. Hotel name in Nairobi">
                @error('pickup_location')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="special_requests" class="block text-xs font-semibold text-slate-700 mb-1">Special requests (optional)</label>
            <textarea id="special_requests" name="special_requests" rows="3" class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('special_requests') }}</textarea>
            @error('special_requests')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-slate-600">
            <div class="flex items-center gap-2">
                <a href="{{ route('tours.show', [$city, $tour]) }}" class="inline-flex items-center px-4 py-2 rounded-full border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                    Cancel and go back
                </a>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                    Confirm booking details
                </button>
            </div>
        </div>

        <p>
            By submitting, you agree to our
            <a href="{{ url('/terms') }}" target="_blank" rel="noopener" class="text-emerald-700 underline hover:text-emerald-800">
                simple booking terms and policies
            </a>.
        </p> -->

        <!-- <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-600">
            <p>
                By submitting, you agree to our
                <a href="{{ url('/terms') }}" target="_blank" rel="noopener"
                    class="text-emerald-700 underline hover:text-emerald-800">
                    simple booking terms and policies
                </a>.
            </p>

            <div class="flex items-center gap-2">
                <a href="{{ route('tours.show', [$city, $tour]) }}"
                    class="inline-flex items-center px-4 py-2 rounded-full border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                    Cancel and go back
                </a>
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                    Confirm booking details
                </button>
            </div>
        </div> -->

        <div class="mt-4 space-y-3 text-xs text-slate-600">
            <div class="flex flex-col sm:flex-row sm:justify-end gap-2">
                <a href="{{ route('tours.show', [$city, $tour]) }}"
                   class="inline-flex items-center justify-center px-4 py-2 rounded-full border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                    Cancel and go back
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                    Confirm booking details
                </button>
            </div>
                        <p>
                By submitting, you agree to our
                <a href="{{ url('/terms') }}" target="_blank" rel="noopener"
                   class="text-emerald-700 underline hover:text-emerald-800">
                    simple booking terms and policies
                </a>.
            </p>
        </div>
    </form>

    <script>
        function updateTraveller(field, delta, min) {
            const input = document.getElementById(field);
            if (!input) return;
            const current = parseInt(input.value || '0', 10);
            const next = Math.max(min, current + delta);
            input.value = next;
        }
    </script>
</section>
@endsection