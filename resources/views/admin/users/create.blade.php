@extends('layouts.admin')

@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
<div class="max-w-3xl" x-data="{ passwordOption: '{{ old('password_option', 'auto') }}' }">
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('console.users.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Users
        </a>
    </div>

    <form method="POST" action="{{ route('console.users.store') }}" class="space-y-6">
        @csrf

        <!-- User Information -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">User Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="space-y-1.5">
                    <label for="name" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}"
                        placeholder="Full name"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('name') border-red-500 @enderror"
                        required
                    >
                    @error('name')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        placeholder="user@example.com"
                        autocomplete="off"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="space-y-1.5 md:col-span-2 max-w-xs">
                    <label for="role" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="role" 
                        id="role" 
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none cursor-pointer @error('role') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Password Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Password</h2>
            
            <div class="space-y-4">
                <!-- Auto-generate password option -->
                <label class="flex items-start gap-3 p-4 border rounded-lg cursor-pointer transition-colors"
                    :class="passwordOption === 'auto' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:bg-slate-50'">
                    <input 
                        type="radio" 
                        name="password_option" 
                        value="auto"
                        x-model="passwordOption"
                        class="mt-0.5 text-emerald-600 focus:ring-emerald-500"
                    >
                    <div>
                        <p class="text-sm font-medium text-slate-900">Automatically generate a strong password</p>
                        <p class="text-xs text-slate-500 mt-0.5">A secure random password will be generated. The user will receive an email with a link to sign in.</p>
                    </div>
                </label>

                <!-- Create password option -->
                <label class="flex items-start gap-3 p-4 border rounded-lg cursor-pointer transition-colors"
                    :class="passwordOption === 'manual' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:bg-slate-50'">
                    <input 
                        type="radio" 
                        name="password_option" 
                        value="manual"
                        x-model="passwordOption"
                        class="mt-0.5 text-emerald-600 focus:ring-emerald-500"
                    >
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-900">Create password</p>
                        <p class="text-xs text-slate-500 mt-0.5">Set a password manually for this user.</p>
                    </div>
                </label>

                <!-- Manual password fields -->
                <div x-show="passwordOption === 'manual'" x-cloak class="pl-7 space-y-4" x-data="{ showPassword: false }">
                    <div class="space-y-1.5 max-w-xs">
                        <label for="password" class="flex items-center gap-1.5 text-sm font-medium text-slate-700">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'"
                                name="password" 
                                id="password" 
                                placeholder="Enter password"
                                autocomplete="new-password"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 pr-10 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none @error('password') border-red-500 @enderror"
                                :required="passwordOption === 'manual'"
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600"
                            >
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-2">
                        <input 
                            type="checkbox" 
                            name="require_password_change" 
                            value="1"
                            {{ old('require_password_change', true) ? 'checked' : '' }}
                            class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                        >
                        <span class="text-sm text-slate-600">Ask user to change their password when they sign in</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Invitation Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Invitation</h2>
            
            <div class="space-y-4">
                <label class="flex items-start gap-3 p-4 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors">
                    <input 
                        type="checkbox" 
                        name="send_invite" 
                        value="1"
                        {{ old('send_invite', true) ? 'checked' : '' }}
                        class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                    >
                    <div>
                        <p class="text-sm font-medium text-slate-900">Send invitation email</p>
                        <p class="text-xs text-slate-500 mt-0.5">The user will receive an email notification about their new account.</p>
                    </div>
                </label>

                <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg">
                    <div class="flex gap-2">
                        <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-xs text-blue-800">
                            <p class="font-medium">What happens when you send an invitation?</p>
                            <ul class="mt-1 space-y-0.5 list-disc list-inside">
                                <li>The user receives a welcome email with login instructions</li>
                                <li>If auto-generated password is selected, they'll get a secure sign-in link</li>
                                <li>The invitation link expires after 7 days</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('console.users.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 cursor-pointer">
                Create User
            </button>
        </div>
    </form>
</div>
@endsection
