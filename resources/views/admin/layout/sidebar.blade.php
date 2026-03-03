<div class="flex flex-col h-full py-6">
    <!-- Brand / Logo Section -->
    <div class="px-6 mb-10 flex items-center gap-3">
        <div class="w-8 h-8 bg-brand-dark rounded-lg flex items-center justify-center text-white shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="font-bold text-xl text-white tracking-tight">GeoLearn</span>
    </div>

    <!-- Navigation Section -->
    <nav class="flex-1 px-3 space-y-1">
        <p x-show="sidebarOpen" class="px-4 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Utama</p>
        
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-brand-dark text-white shadow-lg shadow-brand-dark/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            <span x-show="sidebarOpen" class="font-medium text-sm">Dashboard</span>
        </a>

        <a href="{{ route('admin.education-facility') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.education-facility*') ? 'bg-brand-dark text-white shadow-lg shadow-brand-dark/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span x-show="sidebarOpen" class="font-medium text-sm">Data Pendidikan</span>
        </a>

        <a href="{{ route('map.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-slate-400 hover:bg-slate-800 hover:text-slate-100">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
            </svg>
            <span x-show="sidebarOpen" class="font-medium text-sm">Peta Sebaran</span>
        </a>

        <div class="pt-6">
            <p x-show="sidebarOpen" class="px-4 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sistem</p>

            @can('super-admin')
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users*') ? 'bg-brand-dark text-white shadow-lg shadow-brand-dark/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span x-show="sidebarOpen" class="font-medium text-sm">Data Pengguna</span>
            </a>
            @endcan

            <a href="{{ route('admin.settings') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.settings') ? 'bg-brand-dark text-white shadow-lg shadow-brand-dark/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                </svg>
                <span x-show="sidebarOpen" class="font-medium text-sm">Pengaturan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-400 hover:bg-slate-800 hover:text-slate-100">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-show="sidebarOpen" class="font-medium text-sm">Bantuan</span>
            </a>
        </div>
    </nav>

    <!-- Bottom Section -->
    <div class="px-4 mt-auto">
        @if(\App\Models\Setting::get('dev_mode') === '1')
        <div x-show="sidebarOpen" x-transition class="p-4 bg-indigo-600/10 border border-indigo-500/20 rounded-2xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-indigo-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <p class="text-[10px] font-bold text-brand-success uppercase tracking-widest mb-1 italic">Development Mode</p>
            <p class="text-xs text-white/80 leading-relaxed font-medium tex">Aplikasi dalam tahap pengembangan. Fitur-fitur yang tersedia mungkin tidak berfungsi dengan baik.</p>
        </div>
        @endif
    </div>
</div>
