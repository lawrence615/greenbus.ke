@extends('layouts.app')

@section('title', 'Page expired')

@section('content')
<x-error-page
    code="419"
    badge="Page expired"
    title="This page has expired"
    description="For your security, the page has expired. This usually happens if you leave a form open for too long. Please go back and try again."
    iconBgClass="bg-sky-50 text-sky-700"
    badgeBgClass="bg-sky-50 text-sky-700"
    badgeCircleClass="bg-sky-500 text-white"
>
    <x-slot:icon>
        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="9" fill="#0EA5E9" fill-opacity="0.1" />
            <path d="M12 7.25C12.4142 7.25 12.75 7.58579 12.75 8V11.5858L14.5303 13.3661C14.8232 13.659 14.8232 14.1339 14.5303 14.4268C14.2374 14.7197 13.7626 14.7197 13.4697 14.4268L11.4697 12.4268C11.341 12.2981 11.25 12.1326 11.25 11.9498V8C11.25 7.58579 11.5858 7.25 12 7.25Z" fill="#0284C7" />
        </svg>
    </x-slot:icon>

    <x-slot:actions>
        <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center px-5 py-3 rounded-full border border-slate-200 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50">
            Go back
        </a>
        <x-back-home-button />
    </x-slot:actions>
</x-error-page>
@endsection
