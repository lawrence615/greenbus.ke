@extends('layouts.app')

@section('title', 'Too many requests')

@section('content')
<x-error-page
    code="429"
    badge="Too many requests"
    title="Let's slow down a little"
    description="You've made too many requests in a short period. Please wait a moment and then try again."
    iconBgClass="bg-violet-50 text-violet-700"
    badgeBgClass="bg-violet-50 text-violet-700"
    badgeCircleClass="bg-violet-500 text-white"
>
    <x-slot:icon>
        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="9" fill="#8B5CF6" fill-opacity="0.1" />
            <path d="M9.75 8.75H14.25C14.6642 8.75 15 9.08579 15 9.5C15 9.91421 14.6642 10.25 14.25 10.25H9.75C9.33579 10.25 9 9.91421 9 9.5C9 9.08579 9.33579 8.75 9.75 8.75Z" fill="#7C3AED" />
            <path d="M9.75 12.25H13.25C13.6642 12.25 14 12.5858 14 13C14 13.4142 13.6642 13.75 13.25 13.75H9.75C9.33579 13.75 9 13.4142 9 13C9 12.5858 9.33579 12.25 9.75 12.25Z" fill="#7C3AED" />
        </svg>
    </x-slot:icon>

    <x-slot:actions>
        <x-back-home-button />
    </x-slot:actions>
</x-error-page>
@endsection
