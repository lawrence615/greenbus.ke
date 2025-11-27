@extends('layouts.app')

@section('title', 'Something went wrong')

@section('content')
<x-error-page
    code="500"
    badge="Something went wrong"
    title="We're having a small problem"
    description="An unexpected error occurred while loading this page. Our team has been notified. Please try again in a moment or go back to the homepage."
    iconBgClass="bg-rose-50 text-rose-700"
    badgeBgClass="bg-rose-50 text-rose-700"
    badgeCircleClass="bg-rose-600 text-white"
>
    <x-slot:icon>
        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="9" fill="#F97373" fill-opacity="0.15" />
            <path d="M12 7.25C12.4142 7.25 12.75 7.58579 12.75 8V12.25C12.75 12.6642 12.4142 13 12 13C11.5858 13 11.25 12.6642 11.25 12.25V8C11.25 7.58579 11.5858 7.25 12 7.25Z" fill="#DC2626" />
            <circle cx="12" cy="15.25" r="0.9" fill="#DC2626" />
        </svg>
    </x-slot:icon>

    <x-slot:actions>
        <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center px-5 py-3 rounded-full border border-slate-200 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50">
            Go back
        </a>
        <x-back-home-button />
    </x-slot:actions>

    <x-slot:footer>
        If this keeps happening, you can contact us at info@greenbus.ke.
    </x-slot:footer>
</x-error-page>
@endsection
