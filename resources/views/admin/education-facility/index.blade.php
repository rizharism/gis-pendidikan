@extends('admin.layout.layout')

@section('content')
    <div class="space-y-6" x-data="{
        loading: false,
        searchLoading: false,
        tableLoading: false,
        search: '{{ $search ?? '' }}',

        // ── Detail Modal ─────────────────────────────────
        showDetail:   false,
        detailLoading: false,
        detail:       null,
        carouselIdx:  0,
        showVideo:    false,

        get ytEmbed() {
            if (!this.detail?.video_url) return '';
            const m = this.detail.video_url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
            return m ? 'https://www.youtube.com/embed/' + m[1] + '?autoplay=1' : '';
        },
        get ytThumb() {
            if (!this.detail?.video_url) return '';
            const m = this.detail.video_url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
            return m ? 'https://img.youtube.com/vi/' + m[1] + '/mqdefault.jpg' : '';
        },

        openDetail(url) {
            this.detail      = null;
            this.carouselIdx = 0;
            this.showVideo   = false;
            this.showDetail  = true;
            this.detailLoading = true;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(d => { this.detail = d; })
                .finally(() => { this.detailLoading = false; });
        },
        // ─────────────────────────────────────────────────

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
    }"
    @open-detail.window="openDetail($event.detail.url)">
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
    {{-- ================================================================ --}}
    {{-- DETAIL MODAL --}}
    {{-- ================================================================ --}}
    <div x-show="showDetail" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="showDetail = false; showVideo = false;">

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col"
             @click.stop>

            {{-- ── Loading State ── --}}
            <template x-if="detailLoading">
                <div class="flex-1 flex items-center justify-center py-24">
                    <div class="w-10 h-10 border-4 border-brand-accent border-t-transparent rounded-full animate-spin"></div>
                </div>
            </template>

            {{-- ── Content ── --}}
            <template x-if="!detailLoading && detail">
                <div class="flex flex-col max-h-[90vh] overflow-hidden">

                    {{-- Header --}}
                    <div class="px-6 pt-6 pb-4 flex items-start justify-between gap-4 shrink-0">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-black text-slate-800 dark:text-white leading-tight" x-text="detail.name"></h3>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                <span class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400" x-text="detail.klas"></span>
                                <template x-if="detail.accreditation">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400" x-text="'Akreditasi ' + detail.accreditation"></span>
                                </template>
                                <template x-if="detail.school_code">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300" x-text="'NPSN: ' + detail.school_code"></span>
                                </template>
                            </div>
                        </div>
                        <button type="button" @click="showDetail = false; showVideo = false;"
                            class="shrink-0 p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- ── Scrollable body ── --}}
                    <div class="flex-1 overflow-y-auto px-6 pb-6 space-y-5">

                        {{-- Info Grid (shown first) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Address --}}
                            <div class="sm:col-span-2 p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 space-y-1">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Alamat</p>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200" x-text="detail.address || '—'"></p>
                            </div>

                            {{-- Principal --}}
                            <template x-if="detail.principal_name">
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 space-y-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Kepala Sekolah</p>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-200" x-text="detail.principal_name"></p>
                                </div>
                            </template>

                            {{-- Capacity --}}
                            <template x-if="detail.student_capacity || detail.teacher_count">
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 space-y-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Kapasitas</p>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-200">
                                        <template x-if="detail.student_capacity"><span x-text="detail.student_capacity + ' Siswa'"></span></template>
                                        <template x-if="detail.student_capacity && detail.teacher_count"><span class="text-slate-300"> · </span></template>
                                        <template x-if="detail.teacher_count"><span x-text="detail.teacher_count + ' Guru'"></span></template>
                                    </p>
                                </div>
                            </template>

                            {{-- Phone --}}
                            <template x-if="detail.phone">
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 space-y-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Telepon</p>
                                    <a :href="'tel:' + detail.phone" class="text-sm font-medium text-brand-accent hover:underline" x-text="detail.phone"></a>
                                </div>
                            </template>

                            {{-- Email --}}
                            <template x-if="detail.email">
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 space-y-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Email</p>
                                    <a :href="'mailto:' + detail.email" class="text-sm font-medium text-brand-accent hover:underline" x-text="detail.email"></a>
                                </div>
                            </template>

                            {{-- Website --}}
                            <template x-if="detail.website">
                                <div class="sm:col-span-2 p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 space-y-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Website</p>
                                    <a :href="detail.website" target="_blank" rel="noopener"
                                        class="text-sm font-medium text-brand-accent hover:underline break-all" x-text="detail.website"></a>
                                </div>
                            </template>

                            {{-- Opening Hours --}}
                            <template x-if="detail.opening_hours && detail.opening_hours.length > 0">
                                <div class="sm:col-span-2 p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 space-y-2">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Jam Operasional</p>
                                    <div class="space-y-1">
                                        <template x-for="(h, i) in detail.opening_hours" :key="i">
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="font-semibold text-slate-700 dark:text-slate-200" x-text="h.day"></span>
                                                <span class="font-mono text-xs text-slate-500" x-text="h.open + ' – ' + h.close"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            {{-- Description --}}
                            <template x-if="detail.description">
                                <div class="sm:col-span-2 p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 space-y-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Deskripsi</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" x-text="detail.description"></p>
                                </div>
                            </template>
                        </div>

                        {{-- ── Media Section (bottom, 2-col grid) ── --}}
                        <template x-if="(detail.gallery && detail.gallery.length > 0) || (detail.video_url && ytEmbed)">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Media</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">

                                    {{-- LEFT: Image Carousel --}}
                                    <template x-if="detail.gallery && detail.gallery.length > 0">
                                        <div class="space-y-2">
                                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Galeri Foto</p>
                                            {{-- Main Image --}}
                                            <div class="relative rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-700 aspect-video">
                                                <img :src="detail.gallery[carouselIdx]" class="w-full h-full object-cover">

                                                {{-- Arrows --}}
                                                <template x-if="detail.gallery.length > 1">
                                                    <div>
                                                        <button type="button"
                                                            @click="carouselIdx = carouselIdx === 0 ? detail.gallery.length - 1 : carouselIdx - 1"
                                                            class="absolute left-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-black/40 hover:bg-black/60 text-white rounded-full flex items-center justify-center transition-colors">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                                        </button>
                                                        <button type="button"
                                                            @click="carouselIdx = (carouselIdx + 1) % detail.gallery.length"
                                                            class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-black/40 hover:bg-black/60 text-white rounded-full flex items-center justify-center transition-colors">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <span class="absolute bottom-1.5 right-2 bg-black/50 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                                                    x-text="(carouselIdx + 1) + ' / ' + detail.gallery.length"></span>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- RIGHT: YouTube Video --}}
                                    <template x-if="detail.video_url && ytEmbed">
                                        <div class="space-y-2">
                                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Video</p>
                                            <template x-if="!showVideo">
                                                <button type="button" @click="showVideo = true"
                                                    class="w-full relative rounded-2xl overflow-hidden group aspect-video block">
                                                    <img :src="ytThumb" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors flex items-center justify-center">
                                                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform">
                                                            <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                        </div>
                                                    </div>
                                                    <span class="absolute bottom-1.5 left-2 bg-black/60 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">Putar Video</span>
                                                </button>
                                            </template>
                                            <template x-if="showVideo">
                                                <div class="rounded-2xl overflow-hidden aspect-video">
                                                    <iframe :src="ytEmbed" class="w-full h-full" frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen></iframe>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                </div>
                            </div>
                        </template>

                    </div>


                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center shrink-0">
                        <a :href="detail.edit_url"
                            class="inline-flex items-center gap-2 text-xs font-bold text-brand-accent hover:text-brand-dark bg-blue-50 dark:bg-brand-accent/20 hover:bg-blue-100 dark:hover:bg-brand-accent/30 px-4 py-2 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Edit Data
                        </a>
                        <button type="button" @click="showDetail = false; showVideo = false;"
                            class="text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 px-4 py-2 rounded-xl transition-colors">
                            Tutup
                        </button>
                    </div>

                </div>
            </template>
        </div>
    </div>

</div>
@endsection

