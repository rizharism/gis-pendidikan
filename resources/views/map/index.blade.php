<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="default-basemap" content="{{ \App\Models\Setting::get('default_basemap', 'osm') }}">
    <meta name="layer-control-collapsed" content="{{ \App\Models\Setting::get('layer_control_collapsed', '0') }}">
    <title>{{ \App\Models\Setting::get('app_name', 'GIS Pendidikan') }} - Peta Sekolah</title>

    {{-- Font Awesome & Awesome Markers --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css">

    @vite(['resources/css/app.css'])
</head>


<body class="bg-slate-50">
    {{-- Welcome Curtain Overlay --}}
    <div id="welcome-overlay" class="fixed inset-0 z-[9999] flex flex-col pointer-events-none overflow-hidden"
        style="display: none;">

        <!-- Top Half -->
        <div id="welcome-top"
            class="relative flex-1 bg-slate-900 pointer-events-auto transition-transform duration-[1200ms] ease-[cubic-bezier(0.87,0,0.13,1)] border-b border-white/5 shadow-2xl min-h-0 overflow-y-auto overflow-x-hidden flex flex-col justify-center">
            <div
                class="flex flex-col items-center text-center px-5 py-4 md:px-12 md:py-6 w-full max-w-3xl mx-auto gap-4">

                <!-- Title block: icon + name + subtitle -->
                <div class="flex flex-row items-center gap-3 md:gap-5">
                    <div
                        class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-2xl bg-sky-500/10 border border-sky-400/20 shrink-0">
                        <i class="fa-solid fa-map-location-dot text-xl sm:text-2xl md:text-2xl text-sky-400"></i>
                    </div>
                    <div class="text-left">
                        <h1
                            class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black text-white tracking-tight drop-shadow-md leading-tight">
                            {{ \App\Models\Setting::get('app_name', 'GIS Pendidikan') }}
                        </h1>
                        <p class="hidden sm:block text-[11px] md:text-sm text-slate-400 font-medium mt-0.5">
                            Sistem Informasi Geografis Pemetaan Fasilitas Pendidikan Kota Blitar
                        </p>
                    </div>
                </div>

                @php
                    $totalSd = \App\Models\EducationFacility::where('klas', 'SD')->count();
                    $totalSmp = \App\Models\EducationFacility::where('klas', 'SMP')->count();
                    $totalSma = \App\Models\EducationFacility::where('klas', 'SMA')->count();
                    $totalUniv = \App\Models\EducationFacility::where('klas', 'Universitas')->count();
                    $totalAll = \App\Models\EducationFacility::count();
                @endphp

                <!-- Statistics: always below title -->
                <div class="flex flex-row flex-wrap items-center justify-center gap-2 sm:gap-3">
                    <div
                        class="flex flex-col items-center bg-white/5 px-3 sm:px-4 py-2 rounded-xl border border-white/10">
                        <span class="text-lg sm:text-xl md:text-2xl font-black text-white">{{ $totalAll }}</span>
                        <span
                            class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Total</span>
                    </div>
                    <div class="flex items-center bg-white/5 rounded-xl border border-white/10">
                        <div class="flex flex-col items-center px-3 sm:px-4 py-2 border-r border-white/10">
                            <span
                                class="text-base sm:text-lg md:text-xl font-bold text-rose-400">{{ $totalSd }}</span>
                            <span
                                class="text-[8px] sm:text-[9px] font-semibold text-slate-400 uppercase tracking-widest mt-0.5">SD</span>
                        </div>
                        <div class="flex flex-col items-center px-3 sm:px-4 py-2 border-r border-white/10">
                            <span
                                class="text-base sm:text-lg md:text-xl font-bold text-sky-400">{{ $totalSmp }}</span>
                            <span
                                class="text-[8px] sm:text-[9px] font-semibold text-slate-400 uppercase tracking-widest mt-0.5">SMP</span>
                        </div>
                        <div class="flex flex-col items-center px-3 sm:px-4 py-2 border-r border-white/10">
                            <span
                                class="text-base sm:text-lg md:text-xl font-bold text-amber-400">{{ $totalSma }}</span>
                            <span
                                class="text-[8px] sm:text-[9px] font-semibold text-slate-400 uppercase tracking-widest mt-0.5">SMA</span>
                        </div>
                        <div class="flex flex-col items-center px-3 sm:px-4 py-2">
                            <span
                                class="text-base sm:text-lg md:text-xl font-bold text-emerald-400">{{ $totalUniv }}</span>
                            <span
                                class="text-[8px] sm:text-[9px] font-semibold text-slate-400 uppercase tracking-widest mt-0.5">Univ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Center / Explore Button -->
        <div id="welcome-center"
            class="h-[18vh] sm:h-[22vh] md:h-[30vh] shrink-0 relative flex items-center justify-center pointer-events-none transition-opacity duration-700 w-full bg-slate-800/60 backdrop-blur-[1px] border-y border-white/5">
            <div id="explore-btn-wrapper"
                class="pointer-events-auto z-10 transition-all duration-[1200ms] ease-[cubic-bezier(0.87,0,0.13,1)] scale-100">
                <button id="btn-explore-map"
                    class="group relative px-5 sm:px-6 md:px-8 py-2.5 sm:py-3 bg-sky-500 hover:bg-sky-400 text-white font-bold rounded-full text-sm sm:text-base md:text-lg shadow-[0_0_40px_rgba(14,165,233,0.6)] hover:shadow-[0_0_60px_rgba(14,165,233,0.8)] hover:-translate-y-1 transition-all duration-300 uppercase tracking-widest flex items-center gap-2 sm:gap-3">
                    <span>Eksplorasi Peta</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>

        <!-- Bottom Half -->
        <div id="welcome-bottom"
            class="relative flex-1 bg-slate-900 pointer-events-auto transition-transform duration-[1200ms] ease-[cubic-bezier(0.87,0,0.13,1)] min-h-0 overflow-y-auto overflow-x-hidden flex flex-col justify-start xl:justify-center">

            <div
                class="w-full max-w-7xl mx-auto px-5 md:px-12 grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-12 pt-5 md:pt-8 pb-2 shrink-0">

                <!-- About -->
                <div class="flex flex-col text-center">
                    <h3
                        class="text-white font-bold text-[11px] sm:text-sm uppercase tracking-wider mb-3 border-b border-white/10 pb-2 inline-block self-center">
                        Tentang Aplikasi</h3>
                    <p class="text-[11px] sm:text-xs text-slate-400 leading-relaxed text-justify">
                        Platform GIS ini menyajikan pemetaan interaktif yang komprehensif, ditujukan secara khusus untuk
                        manajemen fasilitas dan institusi pendidikan di wilayah Kota Blitar.
                    </p>
                </div>

                <!-- Features -->
                <div class="flex flex-col text-center">
                    <h3
                        class="text-white font-bold text-[11px] sm:text-sm uppercase tracking-wider mb-3 border-b border-white/10 pb-2 inline-block self-center">
                        Fitur Unggulan</h3>
                    <ul class="text-[11px] sm:text-xs text-slate-400 space-y-2">
                        <li class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-sky-400"></i> Peta Interaktif &amp; Spasial
                        </li>
                        <li class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-circle-info text-emerald-400"></i> Detail Informasi Instansi
                        </li>
                        <li class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-route text-amber-400"></i> Integrasi Pencarian Rute
                        </li>
                    </ul>
                </div>

                <!-- Developer -->
                <div class="flex flex-col text-center shrink-0">
                    <h3
                        class="text-white font-bold text-[11px] sm:text-sm uppercase tracking-wider mb-3 border-b border-white/10 pb-2 inline-block self-center">
                        Pengembang</h3>
                    <p class="text-[11px] sm:text-xs text-slate-400 leading-relaxed mb-1">Dikembangkan oleh:</p>
                    <p class="text-[11px] sm:text-xs text-slate-300 font-medium mb-4">Rizqi Harisma | Choirul Ulum |
                        Lovi Aldi</p>
                    <a href="https://github.com/rizHarism/gis-pendidikan" target="_blank" rel="noopener"
                        class="text-[11px] sm:text-xs font-semibold text-sky-400 hover:text-sky-300 transition-colors inline-flex items-center justify-center gap-2 bg-sky-500/10 hover:bg-sky-500/20 px-3 py-1.5 rounded-lg self-center border border-sky-500/20">
                        <i class="fa-brands fa-github text-sm"></i> Repository GitHub
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="w-full border-t border-white/5 pt-4 pb-5 text-center px-5 mt-auto shrink-0">
                <div class="text-[10px] sm:text-[11px] text-slate-500 tracking-widest uppercase font-semibold">
                    {{ \App\Models\Setting::get('app_name', 'GIS Pendidikan') }} &copy; {{ date('Y') }}
                </div>
            </div>
        </div>
    </div>

    <div id="map-container">
        <div id="dock">
            <div id="search-wrapper" class="relative">
                <input type="text" id="search-input" placeholder="Cari nama sekolah..." autocomplete="off" />
                <div id="search-dropdown"
                    class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl overflow-hidden z-[5000] hidden">
                </div>
            </div>

            <div class="h-6 w-px bg-slate-600/30 mx-1"></div>

            <button id="btn-info" class="dock-btn" title="Informasi Aplikasi">Informasi</button>

            @auth
                <a href="{{ route('admin.dashboard') }}" class="font-bold text-indigo-300">Dashboard</a>
            @else
                <button id="btn-open-login" class="dock-btn">Login</button>
            @endauth
        </div>

        {{-- Info Modal --}}
        <div id="info-modal" class="modal-overlay hidden">
            <div class="modal-backdrop"></div>
            <div class="modal-card !w-[90vw] !max-w-[680px]">
                {{-- Header --}}
                <div class="bg-gradient-to-br from-[#254669] to-[#005c83] p-7 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/15 mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-extrabold text-white m-0 tracking-tight leading-7">
                        {{ \App\Models\Setting::get('app_name', 'GIS Pendidikan') }}</h2>
                    <p class="text-[13px] text-white/60 m-0 mt-1">Kota Blitar</p>
                </div>

                {{-- Body --}}
                <div class="modal-body p-5 sm:p-6 max-h-[60vh] sm:max-h-[50vh] overflow-y-auto">
                    {{-- Description --}}
                    <p class="text-[13px] text-slate-600 leading-relaxed mb-3.5 text-justify">
                        Sistem Informasi Geografis (GIS) Pendidikan Kota Blitar adalah platform berbasis web yang
                        menyajikan peta sebaran fasilitas pendidikan secara interaktif. Mencakup data Sekolah Dasar
                        (SD), Sekolah Menengah Pertama (SMP), Sekolah Menengah Atas (SMA), dan Universitas di wilayah
                        Kota Blitar.
                    </p>
                    <p class="text-[13px] text-slate-600 leading-relaxed mb-4">
                        Berikut adalah beberapa fitur dan interface Aplikasi:
                    </p>

                    {{-- Features --}}
                    <div class=" text-[13px] flex flex-col gap-2 mb-4">
                        <div class="flex items-center gap-2 text-xs text-slate-500 ml-3">
                            <span class="">📍</span> <span>Peta interaktif fasilitas pendidikan</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500 ml-3">
                            <span class="">🔍</span> <span>Pencarian & detail fasilitas</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500 ml-3">
                            <span class="">📊</span> <span>Dashboard statistik interaktif</span>
                        </div>
                    </div>

                    {{-- Version & GitHub --}}
                    <div class="border-t border-slate-100 pt-3.5 mb-3.5 grid grid-cols-2 gap-4 text-center">
                        <div>
                            <span class="text-[13px] uppercase tracking-widest text-slate-400 block mb-1">Versi</span>
                            <span class="text-xs font-semibold text-slate-600">v1.0.0</span>
                        </div>
                        <div>
                            <span class="text-[13px] uppercase tracking-widest text-slate-400 block mb-1">Source
                                Code</span>
                            <a href="https://github.com/rizHarism/gis-pendidikan" target="_blank" rel="noopener"
                                class="text-xs font-semibold text-[#005c83] hover:text-[#254669] transition flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z" />
                                </svg>
                                GitHub
                            </a>
                        </div>
                    </div>

                    {{-- Developer Team --}}
                    <div class="border-t border-slate-100 pt-3.5">
                        <div class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2.5">Tim
                            Pengembang</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ([['1', 'Anggota 1', '000000'], ['2', 'Anggota 2', '000000'], ['3', 'Anggota 3', '000000']] as [$id, $name, $nim])
                                <div
                                    class="flex items-center gap-2.5 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center text-[13px] font-bold text-slate-600 flex-shrink-0">
                                        {{ $id }}</div>
                                    <div class="min-w-0">
                                        <div class="text-[13px] font-semibold text-slate-700 truncate capitalize">
                                            {{ $name }}</div>
                                        <div class="text-[11px] text-slate-400">NIM: {{ $nim }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div
                        class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400 text-center tracking-tight">
                        Kelompok 2 &mdash; Praktek Kerja Lapangan &mdash; Semester 5
                    </div>
                </div>
            </div>
        </div>

        {{-- Custom Layer Control Panel --}}
        <div id="layer-control-wrapper">
            <button id="layer-toggle-btn" title="Layer Control">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
                <span class="layer-control-label">Layer Control</span>
            </button>

            <div id="layer-panel">
                {{-- Basemap Section --}}
                <div class="layer-section-title">Basemap</div>
                <div class="layer-options">
                    <label class="layer-option">
                        <input type="radio" name="basemap" value="osm" checked>
                        <span class="layer-option-icon">🗺️</span>
                        <span>OpenStreetMap</span>
                    </label>
                    <label class="layer-option">
                        <input type="radio" name="basemap" value="imagery">
                        <span class="layer-option-icon">🛰️</span>
                        <span>Satellite</span>
                    </label>
                    <label class="layer-option">
                        <input type="radio" name="basemap" value="OpenTopoMap">
                        <span class="layer-option-icon">⛰️</span>
                        <span>Topografi</span>
                    </label>
                </div>

                <div class="layer-divider"></div>

                {{-- Data Sekolah Section --}}
                <div class="layer-section-title">Data Sekolah</div>
                <div class="layer-options">
                    <label class="layer-option">
                        <input type="checkbox" name="jenjang" value="sd">
                        <span class="layer-option-icon">🏫</span>
                        <span>SD</span>
                        <span id="badge-sd" class="layer-badge hidden"></span>
                    </label>
                    <label class="layer-option">
                        <input type="checkbox" name="jenjang" value="smp">
                        <span class="layer-option-icon">🏫</span>
                        <span>SMP</span>
                        <span id="badge-smp" class="layer-badge hidden"></span>
                    </label>
                    <label class="layer-option">
                        <input type="checkbox" name="jenjang" value="sma">
                        <span class="layer-option-icon">🏫</span>
                        <span>SMA</span>
                        <span id="badge-sma" class="layer-badge hidden"></span>
                    </label>
                    <label class="layer-option">
                        <input type="checkbox" name="jenjang" value="universitas">
                        <span class="layer-option-icon">🎓</span>
                        <span>Universitas</span>
                        <span id="badge-universitas" class="layer-badge hidden"></span>
                    </label>
                </div>
            </div>
        </div>

        <div id="map"></div>
    </div>
    {{-- Detail Modal Overlay (Alpine.js) --}}
    <div id="detail-modal" class="modal-overlay" x-data="mapDetailModal()" :class="{ 'show': showDetail }"
        x-show="showDetail" @open-map-detail.window="openDetail($event.detail)" style="display: none;" x-cloak>

        <div class="modal-backdrop" @click="closeDetail()"></div>
        <div class="modal-card !w-[96vw] !max-w-[680px]">
            {{-- Loading spinner --}}
            <div class="modal-loading flex h-64" x-show="detailLoading">
                <div class="modal-spinner"></div>
                <span>Memuat data...</span>
            </div>

            {{-- Content --}}
            <template x-if="!detailLoading && detail">
                <div class="flex flex-col h-full max-h-[88vh] sm:max-h-[85vh]">
                    {{-- Header: text-only gradient bar --}}
                    <div
                        class="shrink-0 px-5 py-5 sm:py-8 md:py-10 flex items-start justify-between gap-3 rounded-t-2xl bg-gradient-to-br from-sky-700 to-slate-800">
                        <div class="flex-1 min-w-0">
                            <h2 class="text-lg font-black text-white leading-tight truncate" x-text="detail.name">
                            </h2>
                            <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                <span class="modal-jenjang-badge text-[10px] !px-2 !py-0.5" :class="badgeClass"
                                    x-text="jenjangLabel"></span>
                                <template x-if="detail.school_code">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-white/20 text-white/90"
                                        x-text="'NPSN: ' + detail.school_code"></span>
                                </template>
                                <template x-if="detail.accreditation">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black bg-white/20 text-white/90"
                                        x-text="'Akreditasi ' + detail.accreditation"></span>
                                </template>
                            </div>
                        </div>
                        <button @click="closeDetail()"
                            class="shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-white/20 hover:bg-white/30 text-white transition-colors"
                            title="Tutup">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>

                    {{-- Body scrollable --}}
                    <div class="modal-body overflow-y-auto overflow-x-hidden flex-1 !pb-6">

                        {{-- Info Grid (2 columns) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-3">
                            <!-- Alamat (full width) -->
                            <div class="modal-info-row sm:col-span-2">
                                <span class="modal-info-icon">📍</span>
                                <div>
                                    <div class="modal-info-label">Alamat</div>
                                    <div class="modal-info-value" x-text="detail.address || '-'"></div>
                                </div>
                            </div>

                            <!-- Kepala Sekolah -->
                            <template x-if="detail.principal_name">
                                <div class="modal-info-row">
                                    <span class="modal-info-icon">👨‍🏫</span>
                                    <div>
                                        <div class="modal-info-label"
                                            x-text="detail.klas === 'universitas' ? 'Rektor' : 'Kepala Sekolah'">
                                        </div>
                                        <div class="modal-info-value font-medium" x-text="detail.principal_name">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Kapasitas -->
                            <template x-if="detail.student_capacity || detail.teacher_count">
                                <div class="modal-info-row">
                                    <span class="modal-info-icon">👥</span>
                                    <div>
                                        <div class="modal-info-label">Kapasitas</div>
                                        <div class="modal-info-value">
                                            <template x-if="detail.student_capacity"><span
                                                    x-text="detail.student_capacity + (detail.klas === 'universitas' ? ' Mahasiswa' : ' Siswa')"></span></template>
                                            <template x-if="detail.student_capacity && detail.teacher_count"><span
                                                    class="text-slate-300 mx-1">·</span></template>
                                            <template x-if="detail.teacher_count"><span
                                                    x-text="detail.teacher_count + (detail.klas === 'universitas' ? ' Dosen' : ' Guru')"></span></template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Telepon -->
                            <template x-if="detail.phone">
                                <div class="modal-info-row">
                                    <span class="modal-info-icon">📞</span>
                                    <div>
                                        <div class="modal-info-label">Telepon</div>
                                        <a :href="'tel:' + detail.phone"
                                            class="modal-info-value text-blue-600 hover:text-blue-800 hover:underline font-medium"
                                            x-text="detail.phone"></a>
                                    </div>
                                </div>
                            </template>

                            <!-- Email -->
                            <template x-if="detail.email">
                                <div class="modal-info-row">
                                    <span class="modal-info-icon">📧</span>
                                    <div>
                                        <div class="modal-info-label">Email</div>
                                        <a :href="'mailto:' + detail.email"
                                            class="modal-info-value text-blue-600 hover:text-blue-800 hover:underline font-medium"
                                            x-text="detail.email"></a>
                                    </div>
                                </div>
                            </template>

                            <!-- Website -->
                            <template x-if="detail.website">
                                <div class="modal-info-row sm:col-span-2">
                                    <span class="modal-info-icon">🌐</span>
                                    <div>
                                        <div class="modal-info-label">Website</div>
                                        <a :href="detail.website" target="_blank" rel="noopener"
                                            class="modal-info-value text-blue-600 hover:text-blue-800 hover:underline break-all font-medium"
                                            x-text="detail.website"></a>
                                    </div>
                                </div>
                            </template>

                            <!-- Jam Operasional -->
                            <template x-if="detail.opening_hours && detail.opening_hours.length > 0">
                                <div class="modal-info-row sm:col-span-2">
                                    <span class="modal-info-icon">⏰</span>
                                    <div class="w-full">
                                        <div class="modal-info-label">Jam Operasional</div>
                                        <div
                                            class="modal-info-value space-y-1 mt-1 bg-slate-50 border border-slate-100 p-3 rounded-xl">
                                            <template x-for="(h, i) in detail.opening_hours" :key="i">
                                                <div class="flex justify-between items-center text-[12px]">
                                                    <span class="font-semibold text-slate-700" x-text="h.day"></span>
                                                    <span class="font-mono text-slate-500"
                                                        x-text="h.open + ' – ' + h.close"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Deskripsi -->
                            <template x-if="detail.description">
                                <div class="modal-info-row sm:col-span-2">
                                    <span class="modal-info-icon">📝</span>
                                    <div>
                                        <div class="modal-info-label">Deskripsi</div>
                                        <div class="modal-info-value text-slate-600 leading-relaxed"
                                            x-text="detail.description"></div>
                                    </div>
                                </div>
                            </template>

                            <!-- Koordinat -->
                            <div class="modal-info-row sm:col-span-2">
                                <span class="modal-info-icon">📌</span>
                                <div>
                                    <div class="modal-info-label">Koordinat</div>
                                    <div class="modal-info-value modal-coords-text text-[11px] bg-slate-100 px-2 py-0.5 rounded-md inline-block mt-0.5"
                                        x-text="detail.latitude + ', ' + detail.longitude"></div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Media Section (side-by-side grid) ── --}}
                        <template
                            x-if="(detail.gallery && detail.gallery.length > 0) || (detail.video_url && ytEmbedUrl)">
                            <div class="mt-5 pt-5 border-t border-slate-100">
                                <div class="modal-info-row sm:col-span-2">
                                    <span class="modal-info-icon">▶️</span>
                                    <div class="modal-info-label">Media</div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start justify-center">

                                    {{-- LEFT: Image Carousel --}}
                                    <template x-if="detail.gallery && detail.gallery.length > 0">
                                        <div class="space-y-1.5"
                                            :class="!(detail.video_url && ytEmbedUrl) ?
                                            'sm:col-span-2 sm:max-w-[50%] sm:mx-auto w-full' : ''">
                                            {{-- <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                                Galeri Foto</p> --}}
                                            <div
                                                class="relative rounded-xl overflow-hidden bg-slate-100 aspect-video group">
                                                {{-- Slides --}}
                                                <template x-for="(img, idx) in detail.gallery" :key="idx">
                                                    <img :src="'/storage/' + img"
                                                        @click="zoomImg = '/storage/' + img; isImageZoomed = true"
                                                        class="w-full h-full object-cover absolute inset-0 transition-transform duration-500 ease-in-out cursor-zoom-in"
                                                        :class="{
                                                            'translate-x-0': carouselIdx === idx,
                                                            'translate-x-full': carouselIdx < idx,
                                                            '-translate-x-full': carouselIdx > idx
                                                        }"
                                                        :alt="detail.name">
                                                </template>
                                                {{-- Arrows --}}
                                                <template x-if="detail.gallery.length > 1">
                                                    <div
                                                        class="absolute inset-0 z-10 pointer-events-none flex items-center justify-between px-1.5">
                                                        <button type="button"
                                                            @click.stop="carouselIdx = carouselIdx === 0 ? detail.gallery.length - 1 : carouselIdx - 1"
                                                            class="w-7 h-7 pointer-events-auto bg-black/40 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition-colors opacity-70 hover:opacity-100">
                                                            <svg class="w-3.5 h-3.5" transform="rotate(180)"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        </button>
                                                        <button type="button"
                                                            @click.stop="carouselIdx = (carouselIdx + 1) % detail.gallery.length"
                                                            class="w-7 h-7 pointer-events-auto bg-black/40 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition-colors opacity-70 hover:opacity-100">
                                                            <svg class="w-3.5 h-3.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <span
                                                    class="absolute bottom-1.5 right-2 z-10 bg-black/60 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                                                    x-text="(carouselIdx + 1) + ' / ' + detail.gallery.length"></span>
                                                <!-- Modal Zoom / Lightbox -->
                                                <template x-if="isImageZoomed">
                                                    <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 p-4"
                                                        @click="isImageZoomed = false"
                                                        x-transition:enter="transition ease-out duration-300"
                                                        x-transition:enter-start="opacity-0"
                                                        x-transition:enter-end="opacity-100">

                                                        <!-- Tombol Close -->
                                                        <button
                                                            class="absolute top-5 right-5 text-white/70 hover:text-white z-[10000]">
                                                            <svg class="w-10 h-10" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>

                                                        <!-- Gambar Terzoom -->
                                                        <img :src="zoomImg"
                                                            class="max-w-full max-h-full rounded-lg shadow-2xl transform transition-transform duration-300"
                                                            x-transition:enter="transition ease-out duration-300"
                                                            x-transition:enter-start="scale-90"
                                                            x-transition:enter-end="scale-100" @click.stop>

                                                        <p class="absolute bottom-5 text-white/50 text-xs tracking-widest uppercase"
                                                            x-text="detail.name"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                    </template>

                                    {{-- RIGHT: YouTube Video --}}
                                    <template x-if="detail.video_url && ytEmbedUrl">
                                        <div class="space-y-1.5"
                                            :class="!(detail.gallery && detail.gallery.length > 0) ?
                                            'sm:col-span-2 sm:max-w-[50%] sm:mx-auto w-full' : ''">
                                            {{-- <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                                Video</p> --}}
                                            <template x-if="!showVideo">
                                                <button type="button" @click="isVideoZoomed = true; showVideo = true"
                                                    {{-- Tambahkan isVideoZoomed --}}
                                                    class="w-full relative rounded-xl overflow-hidden group aspect-video block border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                                    <img :src="ytThumbUrl" class="w-full h-full object-cover">
                                                    <!-- Overlay Gelap & Tombol Play Tengah -->
                                                    <div
                                                        class="absolute inset-0 bg-black/30 group-hover:bg-black/50 transition-colors flex items-center justify-center">
                                                        <!-- Lingkaran Tombol Play -->
                                                        <div
                                                            class="w-16 h-16 bg-red-600 text-white rounded-full flex items-center justify-center shadow-2xl transform group-hover:scale-110 transition-transform duration-300">
                                                            <svg class="w-8 h-8 ml-1" fill="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path d="M8 5v14l11-7z" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Label di Pojok Kiri Bawah -->
                                                    <div class="absolute bottom-3 left-3 flex items-center gap-2">
                                                        <span
                                                            class="bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded-lg tracking-wider uppercase">
                                                            Putar Video
                                                        </span>
                                                    </div>
                                                </button>
                                            </template>
                                            <template x-if="isVideoZoomed">
                                                <div class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/95 p-4 md:p-10"
                                                    @click="isVideoZoomed = false; showVideo = false">
                                                    {{-- Tutup video saat klik luar --}}

                                                    <!-- Tombol Close -->
                                                    <button
                                                        class="absolute top-5 right-5 text-white/70 hover:text-white z-[1010]">
                                                        <svg class="w-10 h-10" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>

                                                    <!-- Container Video -->
                                                    <div class="w-full max-w-5xl aspect-video bg-black rounded-xl overflow-hidden flex item-center justify-center shadow-2xl"
                                                        @click.stop> {{-- Stop propagation agar tidak tertutup saat klik video --}}
                                                        <iframe :src="ytEmbedUrl" class="w-full h-full"
                                                            frameborder="0"
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen>
                                                        </iframe>
                                                        <p class="absolute bottom-5 text-white/50 text-xs tracking-widest uppercase"
                                                            x-text="detail.name"></p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    @vite('resources/js/gis/initial-map.js')
    @vite('resources/js/map/map.js')
    @vite('resources/js/app.js')

    {{-- Awesome Markers Plugin (Must be after map.js to find window.L) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.min.js" defer>
    </script>

    {{-- Auth modal --}}
    @include('partials.auth-modal')

    <script>
        // Login modal
        document.getElementById('btn-open-login')?.addEventListener('click', () => {
            window.dispatchEvent(new CustomEvent('open-auth-modal'));
        });

        // Info modal
        (function() {
            const infoModal = document.getElementById('info-modal');
            const infoBtn = document.getElementById('btn-info');
            const backdrop = infoModal?.querySelector('.modal-backdrop');

            function openInfo() {
                if (!infoModal) return;
                infoModal.classList.remove('hidden');
                infoModal.classList.add('flex');
                requestAnimationFrame(() => infoModal.classList.add('show'));
            }

            function closeInfo() {
                if (!infoModal) return;
                infoModal.classList.remove('show');
                setTimeout(() => {
                    infoModal.classList.add('hidden');
                    infoModal.classList.remove('flex');
                }, 250);
            }

            infoBtn?.addEventListener('click', openInfo);
            backdrop?.addEventListener('click', closeInfo);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !infoModal?.classList.contains('hidden')) closeInfo();
            });
        })();
    </script>

    {{-- Map Detail Modal Alpine Component --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mapDetailModal', () => ({
                showDetail: false,
                detailLoading: false,
                detail: null,
                carouselIdx: 0,
                showVideo: false,
                badgeClass: '',
                jenjangLabel: '',
                isImageZoomed: false,
                zoomImg: '',
                isVideoZoomed: false,
                zoomVideo: '',

                get ytEmbedUrl() {
                    if (!this.detail?.video_url) return '';
                    const m = this.detail.video_url.match(
                        /(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/
                    );
                    return m ? 'https://www.youtube.com/embed/' + m[1] + '?autoplay=1' : '';
                },
                get ytThumbUrl() {
                    if (!this.detail?.video_url) return '';
                    const m = this.detail.video_url.match(
                        /(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/
                    );
                    return m ? 'https://img.youtube.com/vi/' + m[1] + '/mqdefault.jpg' : '';
                },

                async openDetail(facilityId) {
                    this.showDetail = true;
                    this.detailLoading = true;
                    this.detail = null;
                    this.carouselIdx = 0;
                    this.showVideo = false;

                    try {
                        const response = await fetch(`/api/map/detail/${facilityId}`);
                        const result = await response.json();

                        if (result.success) {
                            this.detail = result.data;

                            let config = {
                                label: this.detail.klas?.toUpperCase() ?? "Sekolah",
                                badgeClass: "bg-slate-100 text-slate-800",
                            };

                            const m = this.detail.klas?.toLowerCase();
                            if (m === 'sd') {
                                config = {
                                    label: "SD",
                                    badgeClass: "bg-rose-100 text-rose-800"
                                };
                            } else if (m === 'smp') {
                                config = {
                                    label: "SMP",
                                    badgeClass: "bg-sky-100 text-sky-800"
                                };
                            } else if (m === 'sma') {
                                config = {
                                    label: "SMA",
                                    badgeClass: "bg-amber-100 text-amber-800"
                                };
                            } else if (m === 'universitas') {
                                config = {
                                    label: "Universitas",
                                    badgeClass: "bg-emerald-100 text-emerald-800"
                                };
                            }

                            this.badgeClass = config.badgeClass;
                            this.jenjangLabel = config.label;
                        } else {
                            this.closeDetail();
                        }
                    } catch (e) {
                        console.error("Failed to load map detail:", e);
                        this.closeDetail();
                    } finally {
                        this.detailLoading = false;
                    }
                },

                closeDetail() {
                    this.showDetail = false;
                    this.isImageZoomed = false;
                    this.isVideoZoomed = false;
                    setTimeout(() => {
                        this.showVideo = false;
                        this.detail = null;
                    }, 300);
                }
            }));
        });
    </script>

    {{-- Welcome Overlay Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const welcomeOverlay = document.getElementById('welcome-overlay');
            const welcomeTop = document.getElementById('welcome-top');
            const welcomeBottom = document.getElementById('welcome-bottom');
            const exploreBtnWrapper = document.getElementById('explore-btn-wrapper');
            const welcomeCenter = document.getElementById('welcome-center');
            const exploreBtn = document.getElementById('btn-explore-map');

            if (!welcomeOverlay) return;

            const isCurtainClosed = localStorage.getItem('gis_welcome_curtain');

            if (!isCurtainClosed) {
                // Show overlay & lock body scroll
                welcomeOverlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';

                exploreBtn.addEventListener('click', () => {
                    // Exit animations
                    welcomeTop.style.transform = 'translateY(-100%)';
                    welcomeBottom.style.transform = 'translateY(100%)';
                    exploreBtnWrapper.style.transform = 'scale(0)';
                    exploreBtnWrapper.style.opacity = '0';
                    welcomeCenter.style.opacity = '0';

                    // Save to localStorage
                    localStorage.setItem('gis_welcome_curtain', 'true');

                    // Restore scroll & remove overlay after animation
                    setTimeout(() => {
                        document.body.style.overflow = '';
                        welcomeOverlay.remove();
                    }, 1200);
                });
            } else {
                welcomeOverlay.remove();
            }
        });
    </script>
</body>

</html>
