<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>GIS Pendidikan - Admin</title>
</head>

<body class="bg-slate-50 font-sans antialiased text-slate-900" x-data="{ sidebarOpen: true, mobileMenuOpen: false, pageLoaded: false }" x-init="setTimeout(() => pageLoaded = true, 500)">

    <!-- Page Loader Overlay -->
    <div x-show="!pageLoaded"
         x-transition:leave="transition ease-out duration-500"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="page-loader">
        <div class="page-loader-spinner"></div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            class="bg-slate-900 text-slate-300 transition-all duration-300 ease-in-out z-40 fixed inset-y-0 left-0 lg:static lg:inset-0"
            :class="{ 'w-64': sidebarOpen, 'w-20': !sidebarOpen, '-translate-x-full lg:translate-x-0': !mobileMenuOpen }">
            @include('admin.layout.sidebar')
        </aside>

        <!-- Overlay for Mobile Sidebar -->
        <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
            <!-- Top Navigation Bar -->
            <header class="bg-white border-b border-slate-200 sticky top-0 z-20 h-16 shrink-0 flex items-center">
                <div class="flex-1 flex items-center justify-between px-4 sm:px-6">
                    <div class="flex items-center gap-4">
                        <!-- Desktop Sidebar Toggle -->
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="hidden lg:flex p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
                            <svg class="w-6 h-6" :class="{ 'rotate-180': !sidebarOpen }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                            </svg>
                        </button>

                        <!-- Mobile Sidebar Toggle -->
                        <button @click="mobileMenuOpen = true"
                            class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <h1 class="text-lg font-bold text-slate-800 tracking-tight flex items-center gap-2">
                            <span class="p-1.5 bg-indigo-600 rounded-lg text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                    </path>
                                </svg>
                            </span>
                            <span x-show="sidebarOpen" x-transition class="hidden sm:inline">GIS Pendidikan</span>
                        </h1>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false"
                            @close.stop="open = false">
                            <button @click="open = ! open"
                                class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-200">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-semibold text-slate-800 leading-none">
                                        {{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider mt-1">
                                        Admin</p>
                                </div>
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4F46E5&color=fff&bold=true"
                                    class="w-9 h-9 rounded-lg object-cover ring-2 ring-white" alt="Avatar">
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 overflow-hidden ring-4 ring-slate-900/5"
                                style="display: none;">

                                <div class="p-2">
                                    <div class="px-4 py-3 border-b border-slate-100 mb-1">
                                        <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    <a href="{{ route('admin.profile') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-xl transition-colors group">
                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        <span>Profil Saya</span>
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full gap-3 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 rounded-xl transition-colors group">
                                            <svg class="w-4 h-4 text-rose-400 group-hover:text-rose-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
                                            </svg>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>

</html>
