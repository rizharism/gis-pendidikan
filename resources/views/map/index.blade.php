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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css">

    @vite(['resources/css/app.css'])
</head>


<body class="bg-slate-50">
    <div id="map-container">
        <div id="dock">
            <div id="search-wrapper" class="relative">
                <input type="text" id="search-input" placeholder="Cari nama sekolah..."
                    autocomplete="off" />
                <div id="search-dropdown" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl overflow-hidden z-[5000] hidden"></div>
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
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-extrabold text-white m-0 tracking-tight leading-7">{{ \App\Models\Setting::get('app_name', 'GIS Pendidikan') }}</h2>
                    <p class="text-[13px] text-white/60 m-0 mt-1">Kota Blitar</p>
                </div>

                {{-- Body --}}
                <div class="modal-body p-6 max-h-[50vh] overflow-y-auto">
                    {{-- Description --}}
                    <p class="text-[13px] text-slate-600 leading-relaxed mb-3.5 italic">
                        Sistem Informasi Geografis (GIS) Pendidikan Kota Blitar adalah platform berbasis web yang menyajikan peta sebaran fasilitas pendidikan secara interaktif. Mencakup data Sekolah Dasar (SD), Sekolah Menengah Pertama (SMP), Sekolah Menengah Atas (SMA), dan Universitas di wilayah Kota Blitar.
                    </p>
                    <p class="text-[13px] text-slate-600 leading-relaxed mb-4">
                        Berikut adalah beberapa fitur dan interface Aplikasi:
                    </p>

                    {{-- Features --}}
                    <div class="flex flex-col gap-2 mb-4">
                        <div class="flex items-center gap-2 text-xs text-slate-500 ml-3">
                            <span class="grayscale brightness-150">📍</span> <span>Peta interaktif fasilitas pendidikan</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500 ml-3">
                            <span class="grayscale brightness-150">🔍</span> <span>Pencarian & detail fasilitas</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500 ml-3">
                            <span class="grayscale brightness-150">📊</span> <span>Dashboard statistik interaktif</span>
                        </div>
                    </div>

                    {{-- Version & GitHub --}}
                    <div class="border-t border-slate-100 pt-3.5 mb-3.5 grid grid-cols-2 gap-4 text-center">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400 block mb-1">Versi</span>
                            <span class="text-xs font-semibold text-slate-600">v1.0.0</span>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400 block mb-1">Source Code</span>
                            <a href="https://github.com/rizHarism/gis-pendidikan" target="_blank" rel="noopener"
                               class="text-xs font-semibold text-[#005c83] hover:text-[#254669] transition flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                                GitHub
                            </a>
                        </div>
                    </div>

                    {{-- Developer Team --}}
                    <div class="border-t border-slate-100 pt-3.5">
                        <div class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-2.5">Tim Pengembang</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach([['1', 'Anggota 1', '000000'], ['2', 'Anggota 2', '000000'], ['3', 'Anggota 3', '000000']] as [$id, $name, $nim])
                                <div class="flex items-center gap-2.5 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <div class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center text-[13px] font-bold text-slate-600 flex-shrink-0">{{ $id }}</div>
                                    <div class="min-w-0">
                                        <div class="text-[13px] font-semibold text-slate-700 truncate capitalize">{{ $name }}</div>
                                        <div class="text-[11px] text-slate-400">NIM: {{ $nim }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400 text-center tracking-tight">
                        Kelompok 2 &mdash; Praktek Kerja Lapangan &mdash; Semester 5
                    </div>
                </div>
            </div>
        </div>

        {{-- Custom Layer Control Panel --}}
        <div id="layer-control-wrapper">
            <button id="layer-toggle-btn" title="Layer Control">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
                <span>Layer</span>
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
                        <input type="radio" name="basemap" value="terrain">
                        <span class="layer-option-icon">⛰️</span>
                        <span>Terrain</span>
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
    {{-- Detail Modal Overlay --}}
    <div id="detail-modal" class="modal-overlay hidden">
        <div class="modal-backdrop"></div>
        <div class="modal-card">
            {{-- Header with image --}}
            <div id="modal-header" class="modal-header">
                <img id="modal-image" class="modal-image" src="" alt="">
                <div class="modal-header-overlay">
                    <span id="modal-jenjang-badge" class="modal-jenjang-badge"></span>
                </div>
                <button id="modal-close-btn" class="modal-close-btn" title="Tutup">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <h2 id="modal-name" class="modal-name"></h2>

                <div class="modal-info-rows">
                    <div class="modal-info-row">
                        <span class="modal-info-icon">📍</span>
                        <div>
                            <div class="modal-info-label">Alamat</div>
                            <div id="modal-address" class="modal-info-value"></div>
                        </div>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-icon">📝</span>
                        <div>
                            <div class="modal-info-label">Deskripsi</div>
                            <div id="modal-description" class="modal-info-value"></div>
                        </div>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-icon">📌</span>
                        <div>
                            <div class="modal-info-label">Koordinat</div>
                            <div id="modal-coords" class="modal-info-value modal-coords-text"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Loading spinner --}}
            <div id="modal-loading" class="modal-loading hidden">
                <div class="modal-spinner"></div>
                <span>Memuat data...</span>
            </div>
        </div>
    </div>

    @vite('resources/js/gis/initial-map.js')
    @vite('resources/js/map/map.js')
    @vite('resources/js/app.js')

    {{-- Awesome Markers Plugin (Must be after map.js to find window.L) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.min.js" defer></script>

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
            const infoBtn   = document.getElementById('btn-info');
            const backdrop  = infoModal?.querySelector('.modal-backdrop');

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
</body>

</html>
