@extends('admin.layout.layout')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Data Fasilitas Pendidikan</h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Kelola informasi sekolah dan institusi pendidikan.</p>
            </div>
            <a href="{{ route('admin.education-facility.create') }}"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Sekolah
            </a>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Search & Filter Bar (Minimalist) -->
            <div class="p-6 border-b border-slate-50 flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" placeholder="Cari nama sekolah atau alamat..." 
                           class="pl-10 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                </div>
                <select class="rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                    <option value="">Semua Jenjang</option>
                    <option value="SMA">SMA/SMK/MA</option>
                    <option value="SMP">SMP/MTS</option>
                    <option value="SD">SD/MI</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 italic">Nama Fasilitas</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 italic">Alamat</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 italic">Jenjang</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 italic">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 italic text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <!-- Example row -->
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-xs shrink-0">
                                        SMA
                                    </div>
                                    <div class="text-sm font-bold text-slate-800">SMA Negeri 1 Blitar</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-500 max-w-xs truncate">Jl. Pemuda No. 123, Kota Blitar</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">SMA/SMK</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="#" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Simple Pagination -->
            <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30 flex items-center justify-between">
                <p class="text-xs text-slate-500 font-medium italic">Menampilkan 1 dari 42 data</p>
                <div class="flex gap-1">
                    <button class="p-2 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 transition-colors disabled:opacity-50" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <button class="p-2 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
