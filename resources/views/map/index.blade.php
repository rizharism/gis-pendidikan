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
            <div id="search-wrapper">
                <input type="text" id="search-input" placeholder="Cari nama sekolah..."
                    autocomplete="off" />
                <div id="search-dropdown" style="display:none;"></div>
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
        <div id="info-modal" class="modal-overlay" style="display:none;">
            <div class="modal-backdrop"></div>
            <div class="modal-card !w-[90vw] !max-w-[680px]" style="width: auto;">
                <div style="background:linear-gradient(135deg,#254669,#005c83); padding:28px 24px; text-align:center;">
                    <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:16px;background:rgba(255,255,255,.15);margin-bottom:12px;">
                        <svg style="width:24px;height:24px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 style="font-size:18px;font-weight:800;color:white;margin:0;">{{ \App\Models\Setting::get('app_name', 'GIS Pendidikan') }}</h2>
                    <p style="font-size:13px;color:rgba(255,255,255,.6);margin:4px 0 0;">Kota Blitar</p>
                </div>
                <div class="modal-body" style="padding:20px 24px; max-height:50vh; overflow-y:auto;">
                    {{-- Description --}}
                    <p style="font-size:13px;color:#475569;line-height:1.7;margin:0 0 14px;">
                        Sistem Informasi Geografis (GIS) Pendidikan Kota Blitar adalah platform berbasis web yang menyajikan peta sebaran fasilitas pendidikan secara interaktif. Mencakup data Sekolah Dasar (SD), Sekolah Menengah Pertama (SMP), Sekolah Menengah Atas (SMA), dan Universitas di wilayah Kota Blitar.
                    </p>
                    <p style="font-size:13px;color:#475569;line-height:1.7;margin:0 0 16px;">
                        Berikut adalah beberapa fitur dan interface Aplikasi:
                    </p>

                    {{-- Features --}}
                    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;">
                        <div class="ms-3" style="display:flex;align-items:center;gap:8px;font-size:12px;color:#64748b;">
                            <span>📍</span> <span>Peta interaktif fasilitas pendidikan</span>
                        </div>
                        <div class="ms-3" style="display:flex;align-items:center;gap:8px;font-size:12px;color:#64748b;">
                            <span>🔍</span> <span>Pencarian & detail fasilitas</span>
                        </div>
                        <div class="ms-3" style="display:flex;align-items:center;gap:8px;font-size:12px;color:#64748b;">
                            <span>📊</span> <span>Dashboard statistik interaktif</span>
                        </div>
                    </div>

                    {{-- Version & GitHub --}}
                    <div style="border-top:1px solid #f1f5f9;padding-top:14px;margin-bottom:14px;" class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;display:block;margin-bottom:4px;">Versi</span>
                            <span style="font-size:12px;font-weight:600;color:#475569;">v1.0.0</span>
                        </div>
                        <div>
                            <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;display:block;margin-bottom:4px;">Source Code</span>
                            <a href="https://github.com/rizHarism/gis-pendidikan" target="_blank" rel="noopener"
                               style="font-size:12px;font-weight:600;color:#005c83;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                <svg style="width:14px;height:14px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                                GitHub
                            </a>
                        </div>
                    </div>

                    {{-- Developer Team --}}
                    <div style="border-top:1px solid #f1f5f9;padding-top:14px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:10px;">Tim Pengembang</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            {{-- TODO: Ganti nama & NIM sesuai anggota kelompok --}}
                            <div style="display:flex;align-items:center;gap:10px;background:#f8fafc;padding:10px;border-radius:12px;border:1px solid #f1f5f9;">
                                <div style="width:32px;height:32px;border-radius:10px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#475569;flex-shrink:0;">1</div>
                                <div style="min-width:0;">
                                    <div style="font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Nama Anggota 1</div>
                                    <div style="font-size:11px;color:#94a3b8;">NIM: 000000</div>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;background:#f8fafc;padding:10px;border-radius:12px;border:1px solid #f1f5f9;">
                                <div style="width:32px;height:32px;border-radius:10px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#475569;flex-shrink:0;">2</div>
                                <div style="min-width:0;">
                                    <div style="font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Nama Anggota 2</div>
                                    <div style="font-size:11px;color:#94a3b8;">NIM: 000000</div>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;background:#f8fafc;padding:10px;border-radius:12px;border:1px solid #f1f5f9;">
                                <div style="width:32px;height:32px;border-radius:10px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#475569;flex-shrink:0;">3</div>
                                <div style="min-width:0;">
                                    <div style="font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Nama Anggota 3</div>
                                    <div style="font-size:11px;color:#94a3b8;">NIM: 000000</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div style="margin-top:16px;padding-top:12px;border-top:1px solid #f1f5f9;font-size:11px;color:#94a3b8;text-align:center;">
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
                        <span id="badge-sd" class="layer-badge" style="display:none;"></span>
                    </label>
                    <label class="layer-option">
                        <input type="checkbox" name="jenjang" value="smp">
                        <span class="layer-option-icon">🏫</span>
                        <span>SMP</span>
                        <span id="badge-smp" class="layer-badge" style="display:none;"></span>
                    </label>
                    <label class="layer-option">
                        <input type="checkbox" name="jenjang" value="sma">
                        <span class="layer-option-icon">🏫</span>
                        <span>SMA</span>
                        <span id="badge-sma" class="layer-badge" style="display:none;"></span>
                    </label>
                    <label class="layer-option">
                        <input type="checkbox" name="jenjang" value="universitas">
                        <span class="layer-option-icon">🎓</span>
                        <span>Universitas</span>
                        <span id="badge-universitas" class="layer-badge" style="display:none;"></span>
                    </label>
                </div>
            </div>
        </div>

        <div id="map"></div>
    </div>
    {{-- Detail Modal Overlay --}}
    <div id="detail-modal" class="modal-overlay" style="display:none;">
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
            <div id="modal-loading" class="modal-loading" style="display:none;">
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
                infoModal.style.display = 'flex';
                requestAnimationFrame(() => infoModal.classList.add('show'));
            }
            function closeInfo() {
                if (!infoModal) return;
                infoModal.classList.remove('show');
                setTimeout(() => { infoModal.style.display = 'none'; }, 250);
            }

            infoBtn?.addEventListener('click', openInfo);
            backdrop?.addEventListener('click', closeInfo);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && infoModal?.style.display !== 'none') closeInfo();
            });
        })();
    </script>
</body>

</html>
