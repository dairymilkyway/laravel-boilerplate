@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500">Overview of your application.</p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

        <!-- Total Users -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Users</p>
                    <p class="mt-2 text-4xl font-bold text-gray-900">{{ $totalUsers }}</p>
                </div>
                <div class="flex-shrink-0 rounded-full bg-blue-50 p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                    View all users &rarr;
                </a>
            </div>
        </div>

        <!-- Total Roles -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Roles</p>
                    <p class="mt-2 text-4xl font-bold text-gray-900">{{ $totalRoles }}</p>
                </div>
                <div class="flex-shrink-0 rounded-full bg-purple-50 p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.roles.index') }}" class="text-sm font-medium text-purple-600 hover:text-purple-800 transition">
                    View all roles &rarr;
                </a>
            </div>
        </div>

    </div>
@endsection
