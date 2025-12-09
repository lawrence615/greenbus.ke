@extends('layouts.app')

@section('title', 'Accept Invitation – Greenbus')

@section('content')
<section class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-semibold text-emerald-700">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-600 text-white font-bold text-lg">
                    GB
                </span>
            </a>
            <h1 class="text-2xl font-semibold text-slate-900 mt-4">Welcome to GreenBus!</h1>
            <p class="text-sm text-slate-600 mt-1">Set up your password to activate your account</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <!-- User Info -->
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg mb-6">
                <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-medium text-slate-900">{{ $user->name }}</p>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('invite.accept.store', $token) }}" class="space-y-4">
                @csrf

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autofocus
                        class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-1">Must be at least 8 characters</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="w-full rounded-md border border-slate-300 bg-white text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="••••••••"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition cursor-pointer"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Activate Account
                </button>
            </form>

            <div class="mt-6 p-3 bg-amber-50 border border-amber-100 rounded-lg">
                <div class="flex gap-2">
                    <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="text-xs text-amber-800">
                        <p class="font-medium">Security Notice</p>
                        <p class="mt-0.5">This invitation link will expire in 7 days. If you didn't expect this invitation, please ignore this page.</p>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-sm text-slate-600 mt-6">
            Already have an account? <a href="{{ route('login') }}" class="text-emerald-700 hover:underline">Sign in</a>
        </p>
    </div>
</section>
@endsection
