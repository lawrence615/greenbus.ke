@extends('layouts.app')

@section('title', 'Access denied')

@section('content')
<x-error-page
    code="403"
    badge="Access denied"
    title="You don't have permission to view this page"
    description="You might be trying to access a resource that requires additional permissions. If you believe this is an error, please contact us."
    iconBgClass="bg-amber-50 text-amber-700"
    badgeBgClass="bg-amber-50 text-amber-700"
    badgeCircleClass="bg-amber-500 text-white"
>
    <x-slot:icon>
        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="7" y="9" width="10" height="8" rx="2" fill="#F59E0B" fill-opacity="0.1" />
            <path d="M9.5 9C9.5 7.067 11.067 5.5 13 5.5C14.933 5.5 16.5 7.067 16.5 9V9.5H15.25V9C15.25 7.75736 14.2426 6.75 13 6.75C11.7574 6.75 10.75 7.75736 10.75 9V9.5H9.5V9Z" fill="#F59E0B" />
            <circle cx="13" cy="13" r="0.9" fill="#B45309" />
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
