@extends('layouts.app')

@section('title', 'Login – Greenbus')

@section('content')
<section class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-semibold text-emerald-700">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-600 text-white font-bold text-lg">
                    GB
                </span>
            </a>
            <h1 class="text-2xl font-semibold text-slate-900 mt-4">Welcome back</h1>
            <p class="text-sm text-slate-600 mt-1">Sign in to access your dashboard</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="you@example.com"
                    >
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input
                            type="checkbox"
                            name="remember"
                            class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                        >
                        <span>Remember me</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition cursor-pointer"
                >
                    Sign in
                </button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-white px-3 text-slate-500">Or continue with</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <button
                    type="button"
                    disabled
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm font-medium cursor-not-allowed"
                    title="Google login coming soon"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                </button>

                <button
                    type="button"
                    disabled
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm font-medium cursor-not-allowed"
                    title="X login coming soon"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </button>

                <button
                    type="button"
                    disabled
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 text-sm font-medium cursor-not-allowed"
                    title="Facebook login coming soon"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </button>
            </div>

            <p class="text-center text-xs text-slate-500 mt-4">
                Social login options coming soon
            </p>
        </div>

        <p class="text-center text-sm text-slate-600 mt-6">
            <a href="{{ route('home') }}" class="text-emerald-700 hover:underline">← Back to home</a>
        </p>
    </div>
</section>
@endsection
