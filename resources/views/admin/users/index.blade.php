@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-500">Manage system users and their roles</p>
        </div>
        <a href="{{ route('console.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add User
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" action="{{ route('console.users.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Search</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Name or email..."
                    class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                <select name="role" class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('console.users.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200">
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">User</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Email</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Role</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-700">Joined</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center text-white font-semibold text-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-slate-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            @foreach($user->roles as $role)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($role->name === 'super-admin') bg-purple-100 text-purple-700
                                @elseif($role->name === 'admin') bg-blue-100 text-blue-700
                                @else bg-slate-100 text-slate-700
                                @endif">
                                {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                            </span>
                            @endforeach
                            @if($user->roles->isEmpty())
                            <span class="text-slate-400 text-xs">No role</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('console.users.edit', $user) }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                                    Edit
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('console.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
