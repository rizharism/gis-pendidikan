@extends('admin.layout.layout')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Pengguna</h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Kelola akun dan hak akses pengguna sistem.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 bg-brand-dark hover:bg-brand-accent text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-lg shadow-brand-dark/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pengguna
            </a>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-sm font-bold flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 transition-colors p-1 rounded-lg hover:bg-emerald-100 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm font-bold flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-600 hover:text-rose-800 transition-colors p-1 rounded-lg hover:bg-rose-100 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Search Bar -->
            <div class="p-6 border-b border-slate-50">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau email pengguna..."
                            class="pl-10 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                    </div>
                    <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-md flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.users.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 px-4 rounded-xl transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 italic">Nama</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 italic">Email</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 italic">Role</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 italic text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-brand-accent font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-700 text-sm">{{ $user->name }}</span>
                                        @if($user->id === auth()->id())
                                            <span class="text-[10px] bg-blue-100 text-brand-accent font-bold px-2 py-0.5 rounded-full">Anda</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @if($user->role === 'super-admin')
                                        <span class="inline-flex items-center gap-1 text-xs font-bold bg-brand-dark/10 text-brand-dark px-3 py-1 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1l2.545 5.157L18 7.118l-4 3.897.944 5.505L10 13.875l-4.944 2.645L6 10.015 2 7.118l5.455-.961L10 1z" clip-rule="evenodd"/></svg>
                                            Super Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-bold bg-slate-100 text-slate-600 px-3 py-1 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                                            Admin
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-accent bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-slate-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <p class="text-sm font-bold">Tidak ada pengguna ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500 font-medium italic">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
                        dari {{ $users->total() }} pengguna
                        @if($search)
                            <span class="text-brand-accent font-bold">(Hasil pencarian: "{{ $search }}")</span>
                        @endif
                    </p>
                    <div class="flex items-center gap-2">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

