@extends('admin.layout.layout')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div>
            <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Overview</h2>
            <p class="text-slate-500 mt-2 font-medium">Ringkasan data fasilitas pendidikan di Kota Blitar.</p>
        </div>

        <!-- Dashboard Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Card -->
            <div class="relative bg-gradient-to-br from-indigo-600 to-indigo-700 p-6 rounded-3xl shadow-xl shadow-indigo-200 overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col h-full">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <p class="text-indigo-100 text-sm font-bold uppercase tracking-widest">Total Fasilitas</p>
                    <h3 class="text-4xl font-black text-white mt-1 tracking-tight">42</h3>
                </div>
            </div>

            <!-- SMA Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 border border-blue-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">SMA/SMK/MA</p>
                    <h3 class="text-4xl font-black text-slate-800 mt-1 tracking-tight">38</h3>
                </div>
            </div>

            <!-- SMP Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 border border-emerald-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">SMP/MTS</p>
                    <h3 class="text-4xl font-black text-slate-800 mt-1 tracking-tight">5</h3>
                </div>
            </div>

            <!-- SD Card -->
            <div class="relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex flex-col h-full">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300 border border-amber-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h8M11 9h8m-10-4h.01M1 1h.01M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">SD/MI</p>
                    <h3 class="text-4xl font-black text-slate-800 mt-1 tracking-tight">5</h3>
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
