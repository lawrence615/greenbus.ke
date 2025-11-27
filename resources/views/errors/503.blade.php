@extends('layouts.app')

@section('title', 'Service unavailable')

@section('content')
<x-error-page
    code="503"
    badge="Service unavailable"
    title="We're doing a quick check-up"
    description="Greenbus is temporarily unavailable, usually for maintenance or a short outage. Please check back again in a few minutes."
    iconBgClass="bg-slate-50 text-slate-700"
    badgeBgClass="bg-slate-50 text-slate-700"
    badgeCircleClass="bg-slate-500 text-white"
>
    <x-slot:icon>
        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.25 10.75L12 4L18.75 10.75V18C18.75 18.6904 18.1904 19.25 17.5 19.25H6.5C5.80964 19.25 5.25 18.6904 5.25 18V10.75Z" fill="#E5E7EB" />
            <path d="M10.75 19.25V14.5H13.25V19.25" fill="#9CA3AF" />
        </svg>
    </x-slot:icon>

    <x-slot:actions>
        <x-back-home-button />
    </x-slot:actions>

    <x-slot:footer>
        If this lasts longer than expected, you can contact us at info@greenbus.ke.
    </x-slot:footer>
</x-error-page>
@endsection
