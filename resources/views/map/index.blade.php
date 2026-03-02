<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GIS Pendidikan - Peta Sekolah</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-slate-50">
    <div id="map-container">
        <div id="dock">
            <input type="text" id="search-input" list="school-suggestions" placeholder="Cari nama sekolah..."
                autocomplete="off" />

            <datalist id="school-suggestions">
                <!-- Options will be populated by JavaScript -->
            </datalist>

            <div class="h-6 w-px bg-slate-600/30 mx-1"></div>

            <a href="#" class="font-medium text-slate-200">Peta</a>
            <a href="#" class="font-medium text-slate-200">Informasi</a>

            @auth
                <a href="{{ route('admin.dashboard') }}" class="font-bold text-indigo-300">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="font-medium text-slate-200">Login</a>
            @endauth
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
</body>

</html>
