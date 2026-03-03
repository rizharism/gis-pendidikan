@extends('admin.layout.layout')

@section('content')
    <div class="space-y-6" x-data="{
        loading: false,
        searchLoading: false,
        tableLoading: false,
        search: '{{ $search ?? '' }}',
    
        async loadData(url) {
            this.tableLoading = true;
    
            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
    
                const data = await response.json();
    
                if (data.success) {
                    // Update table HTML
                    this.$refs.tableBody.innerHTML = data.html;
                    // Update pagination
                    this.$refs.pagination.innerHTML = data.pagination;
                    // Update info text
                    this.$refs.tableInfo.innerHTML = data.info + (data.search ? ` <span class='text-indigo-600 font-bold'>(Hasil pencarian: \'${data.search}\')</span>` : '');
                    // Update search state
                    this.search = data.search;
                }
            } catch (error) {
                console.error('AJAX Error:', error);
            } finally {
                this.tableLoading = false;
            }
        },
    
        async performSearch(event) {
            event.preventDefault();
            this.searchLoading = true;
    
            const form = event.target;
            const formData = new FormData(form);
            const searchValue = formData.get('search');
            const url = form.action +
                '?search=' + encodeURIComponent(searchValue);
    
            await this.loadData(url);
    
            this.searchLoading = false;
        },
    
        async resetSearch() {
            this.search = '';
            this.searchLoading = true;
            await this.loadData('{{ route('admin.education-facility') }}');
            this.searchLoading = false;
        }
    }">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Data Fasilitas Pendidikan</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">Kelola informasi sekolah dan institusi pendidikan.</p>
            </div>
            <a href="{{ route('admin.education-facility.create') }}"
                class="inline-flex items-center gap-2 bg-brand-dark hover:bg-brand-accent text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-lg shadow-brand-dark/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Tambah Sekolah
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
                 class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-sm font-bold flex items-center justify-between gap-3 animate-fade-in-down">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                 class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm font-bold flex items-center justify-between gap-3 animate-fade-in-down">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <!-- Search & Filter Bar -->
            <div class="p-6 border-b border-slate-50 dark:border-slate-700">
                <form method="GET" action="{{ route('admin.education-facility') }}" class="flex gap-2"
                    @submit="performSearch">

                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="search" x-model="search" placeholder="Cari nama sekolah atau alamat..."
                            class="pl-10 w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 dark:placeholder-slate-400 focus:border-brand-accent focus:ring-brand-accent text-sm py-2.5">
                    </div>

                    {{-- Search Button with Loading Spinner --}}
                    <button type="submit"
                        class="bg-brand-dark hover:bg-brand-accent text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-md flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="searchLoading">

                        {{-- Default Icon --}}
                        <svg x-show="!searchLoading" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>

                        {{-- Loading Spinner --}}
                        <svg x-show="searchLoading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        <span x-text="searchLoading ? 'Mencari...' : 'Cari'"></span>
                    </button>

                    {{-- Clear Button (only when searching) --}}
                    <button type="button" x-show="search" @click="resetSearch" :disabled="searchLoading"
                        class="bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-500 text-slate-700 dark:text-slate-200 font-bold py-2.5 px-4 rounded-xl transition-all flex items-center gap-2 disabled:opacity-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        Reset
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto relative">
                {{-- Table Loading Overlay --}}
                <div x-show="tableLoading" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm z-10 flex items-center justify-center">
                    <div class="page-loader-spinner"></div>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-700/50">
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700 italic">
                                Nama Fasilitas Pendidikan</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700 italic">
                                Alamat</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700 italic">
                                Jenjang</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700 italic text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody x-ref="tableBody" class="divide-y divide-slate-50 dark:divide-slate-700">
                        @include('admin.education-facility._table', [
                            'facilities' => $facilities,
                            'search' => $search,
                        ])
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-50 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-700/30">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p x-ref="tableInfo" class="text-xs text-slate-500 dark:text-slate-400 font-medium italic">
                        Menampilkan {{ $facilities->firstItem() ?? 0 }} - {{ $facilities->lastItem() ?? 0 }}
                        dari {{ $facilities->total() }} data
                        @if ($search)
                            <span class="text-indigo-600 font-bold">(Hasil pencarian: "{{ $search }}")</span>
                        @endif
                    </p>
                    <div x-ref="pagination" class="flex items-center gap-2"
                        @click.prevent="
                        const link = $event.target.closest('a');
                        if (link && link.href) {
                            $event.preventDefault();
                            loadData(link.href);
                        }
                    ">
                        {{ $facilities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

