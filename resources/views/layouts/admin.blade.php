<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

    <!-- Top navigation bar -->
    <nav class="bg-white border-b border-gray-200 fixed top-0 inset-x-0 z-30 h-16">
        <div class="flex items-center justify-between h-full px-4 sm:px-6">

            <!-- Left: App name + mobile sidebar toggle -->
            <div class="flex items-center gap-3">
                <button
                    id="sidebar-toggle"
                    type="button"
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-label="Toggle sidebar"
                    onclick="document.getElementById('sidebar').classList.toggle('hidden')"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold text-gray-900 tracking-tight">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <span class="hidden sm:inline-block text-xs font-medium bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Admin</span>
            </div>

            <!-- Right: User name + logout -->
            <div class="flex items-center gap-4">
                @auth
                    <span class="hidden sm:block text-sm text-gray-600">
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="text-sm text-gray-600 hover:text-gray-900 px-3 py-1.5 rounded-md hover:bg-gray-100 transition"
                        >
                            Log out
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page wrapper: sidebar + content -->
    <div class="flex pt-16 min-h-screen">

        <!-- Sidebar -->
        <aside
            id="sidebar"
            class="hidden md:flex md:flex-col w-56 bg-white border-r border-gray-200 fixed top-16 bottom-0 left-0 z-20 overflow-y-auto"
        >
            <nav class="flex flex-col gap-1 p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-1">Navigation</p>

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
                    @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif
                >
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a
                    href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
                    @if(request()->routeIs('admin.users.*')) aria-current="page" @endif
                >
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Users
                </a>

                @can('manage roles')
                <a
                    href="{{ route('admin.roles.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('admin.roles.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}"
                    @if(request()->routeIs('admin.roles.*')) aria-current="page" @endif
                >
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Roles
                </a>
                @endcan
            </nav>

            <!-- Sidebar footer: link back to main app -->
            <div class="mt-auto p-4 border-t border-gray-100">
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 text-xs text-gray-500 hover:text-gray-700 transition"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to App
                </a>
            </div>
        </aside>

        <!-- Mobile sidebar overlay (click outside to close) -->
        <div
            id="sidebar-overlay"
            class="hidden fixed inset-0 bg-black bg-opacity-25 z-10 md:hidden"
            onclick="document.getElementById('sidebar').classList.add('hidden'); this.classList.add('hidden')"
        ></div>

        <!-- Main content -->
        <main class="flex-1 md:ml-56 min-h-screen">
            <div class="p-6">
                @include('partials.flash')
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Show overlay when sidebar is toggled open on mobile
        const toggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (toggle && sidebar && overlay) {
            toggle.addEventListener('click', function () {
                if (!sidebar.classList.contains('hidden')) {
                    overlay.classList.remove('hidden');
                } else {
                    overlay.classList.add('hidden');
                }
            });
        }
    </script>

</body>
</html>
