@extends('admin.layout.layout')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Dashboard Overview</h2>
                <p class="text-slate-500 mt-2 font-medium italic">Selamat datang kembali, {{ Auth::user()->name }}! Ringkasan data fasilitas pendidikan.</p>
            </div>
            <div class="hidden md:flex items-center gap-3">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                </span>
                <p class="text-sm font-bold text-slate-600 tracking-wide uppercase italic">Sistem Aktif</p>
            </div>
        </div>

        <!-- Dashboard Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Total Card -->
            <div class="relative bg-gradient-to-br from-indigo-600 to-indigo-700 p-6 rounded-3xl shadow-xl shadow-indigo-200 overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col h-full">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <p class="text-indigo-100 text-xs font-black uppercase tracking-widest">Total Fasilitas</p>
                    <h3 class="text-3xl font-black text-white mt-1 tracking-tight">{{ $stats['total'] }}</h3>
                </div>
            </div>

            <!-- SD Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300 border border-rose-100 uppercase font-black text-xs">
                        SD
                    </div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Sekolah Dasar</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1 tracking-tight">{{ $stats['sd'] }}</h3>
                </div>
            </div>

            <!-- SMP Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 border border-indigo-100 uppercase font-black text-xs">
                        SMP
                    </div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Menengah Pertama</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1 tracking-tight">{{ $stats['smp'] }}</h3>
                </div>
            </div>

            <!-- SMA Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300 border border-amber-100 uppercase font-black text-xs">
                        SMA
                    </div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Menengah Atas</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1 tracking-tight">{{ $stats['sma'] }}</h3>
                </div>
            </div>

            <!-- Universitas Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 border border-emerald-100 uppercase font-black text-xs">
                        UNIV
                    </div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Perguruan Tinggi</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1 tracking-tight">{{ $stats['univ'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Placeholder for Visual Data -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h4 class="text-lg font-bold text-slate-800 tracking-tight mb-4">Sebaran Wilayah</h4>
                <div class="h-64 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center">
                    <p class="text-slate-400 font-medium italic">Chart Area Placeholder</p>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h4 class="text-lg font-bold text-slate-800 tracking-tight mb-4">Aktivitas Terakhir</h4>
                <div class="space-y-4">
                    @for($i = 0; $i < 4; $i++)
                    <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Menambahkan SMP Negeri 1</p>
                            <p class="text-xs text-slate-500">2 jam yang lalu</p>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection
