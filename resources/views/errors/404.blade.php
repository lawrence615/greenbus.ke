@extends('layouts.app')

@section('title', 'Page not found')

@section('content')
<x-error-page
    code="404"
    badge="Page not found"
    title="We couldn't find that page"
    description="The link you followed might be broken, expired, or the tour is no longer available. You can go back to the homepage or explore our Nairobi city tours."
    iconBgClass="bg-emerald-50 text-emerald-700"
    badgeBgClass="bg-emerald-50 text-emerald-700"
    badgeCircleClass="bg-emerald-600 text-white"
>
    <x-slot:icon>
        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2.75C8.824 2.75 6.25 5.324 6.25 8.5C6.25 12.578 11.2 17.727 11.412 17.945C11.744 18.289 12.256 18.289 12.588 17.945C12.8 17.727 17.75 12.578 17.75 8.5C17.75 5.324 15.176 2.75 12 2.75Z" fill="#059669"/>
            <circle cx="12" cy="8.5" r="2.25" fill="white"/>
            <path d="M6 18.5C6 17.395 6.895 16.5 8 16.5H16C17.105 16.5 18 17.395 18 18.5V19.25C18 19.802 17.552 20.25 17 20.25H7C6.448 20.25 6 19.802 6 19.25V18.5Z" fill="#10B981"/>
        </svg>
    </x-slot:icon>

    <x-slot:actions>
        <x-back-home-button />
        <a href="{{ route('tours.index', ['city' => 'nairobi']) }}" class="inline-flex items-center justify-center px-5 py-3 rounded-full border border-emerald-200 text-sm font-semibold text-emerald-800 bg-white hover:bg-emerald-50">
            Browse Nairobi tours
        </a>
    </x-slot:actions>

    <x-slot:footer>
        If you typed the address manually, please check the spelling and try again.
    </x-slot:footer>
</x-error-page>
@endsection
