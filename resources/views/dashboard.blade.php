@extends('layouts.app')

@section('title', 'Dashboard – Greenbus')

@section('content')
<section class="max-w-6xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
            <p class="text-sm text-slate-600 mt-1">Welcome back, {{ auth()->user()->name }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="inline-flex items-center px-4 py-2 rounded-full border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition"
            >
                <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout
            </button>
        </form>
    </div>

    <div class="grid gap-6 md:grid-cols-3 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Quick Links</p>
                </div>
            </div>
            <div class="space-y-2">
                <a href="/horizon" class="flex items-center gap-2 text-sm text-slate-700 hover:text-emerald-700 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Horizon Queue Dashboard
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-50 text-sky-700">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Account</p>
                </div>
            </div>
            <div class="text-sm text-slate-700">
                <p class="font-medium">{{ auth()->user()->name }}</p>
                <p class="text-slate-500">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-50 text-amber-700">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Role</p>
                </div>
            </div>
            <div class="text-sm text-slate-700">
                <p class="font-medium">Administrator</p>
                <p class="text-slate-500 text-xs">Roles & permissions coming soon</p>
            </div>
        </div>
    </div>

    <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-6">
        <h2 class="text-base font-semibold text-emerald-900 mb-2">Getting Started</h2>
        <p class="text-sm text-emerald-800 mb-4">
            This is your admin dashboard. More features will be added here including booking management, tour management, and analytics.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="/horizon" class="inline-flex items-center px-4 py-2 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                View Queue Dashboard
            </a>
            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 rounded-full border border-emerald-200 text-emerald-800 text-sm font-semibold hover:bg-emerald-100 transition">
                View Public Site
            </a>
        </div>
    </div>
</section>
@endsection
