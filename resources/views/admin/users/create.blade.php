@extends('layouts.admin')

@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
<div class="max-w-2xl">
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('console.users.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Users
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="font-semibold text-slate-900">Create New User</h2>
        </div>
        <form method="POST" action="{{ route('console.users.store') }}" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 @error('name') border-red-500 @enderror"
                    required
                >
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}"
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 @error('email') border-red-500 @enderror"
                    required
                >
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                <select 
                    name="role" 
                    id="role" 
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 @error('role') border-red-500 @enderror"
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
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 @error('password') border-red-500 @enderror"
                    required
                >
                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                    required
                >
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                    Create User
                </button>
                <a href="{{ route('console.users.index') }}" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
