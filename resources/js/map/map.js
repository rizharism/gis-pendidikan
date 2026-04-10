import L from "leaflet";
import "leaflet/dist/leaflet.css";
import { initMap } from "../gis/initial-map";
import "leaflet.markercluster";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";
import "../../css/map.css";

let map;
let allMarkers = {};

// ─── Basemap tile layers ─────────────────────────────────────────────────────
const basemaps = {
    osm: L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "© OpenStreetMap contributors",
    }),
    imagery: L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
        { maxZoom: 19, attribution: "© Esri" },
    ),
    // terrain: L.tileLayer(
    //     "https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}.png",
    //     { maxZoom: 18, attribution: "© Stamen Design" },
    // ),
    OpenTopoMap: L.tileLayer(
        "https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png",
        {
            maxZoom: 19,
            attribution:
                'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
        },
    ),
};

// Track currently active basemap key – read from meta tag or fall back to 'osm'
let activeBasemap =
    document.querySelector('meta[name="default-basemap"]')?.content || "osm";

// ─── Jenjang layer groups (one cluster group per jenjang) ────────────────────
const jenjangLayers = {
    sd: L.markerClusterGroup({
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        spiderfyOnMaxZoom: true,
    }),
    smp: L.markerClusterGroup({
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        spiderfyOnMaxZoom: true,
    }),
    sma: L.markerClusterGroup({
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        spiderfyOnMaxZoom: true,
    }),
    universitas: L.markerClusterGroup({
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        spiderfyOnMaxZoom: true,
    }),
};

// Track which jenjang layers are currently on the map
const layerOnMap = { sd: false, smp: false, sma: false, universitas: false };

// ─── Init ────────────────────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", async () => {
    map = initMap();
    if (!map) return;

    // Read saved settings from meta tags
    const defaultBasemap =
        document.querySelector('meta[name="default-basemap"]')?.content ||
        "osm";
    const layerCollapsed =
        document.querySelector('meta[name="layer-control-collapsed"]')
            ?.content === "1";

    // Add default basemap from settings
    const bm = basemaps[defaultBasemap] || basemaps.osm;
    bm.addTo(map);
    activeBasemap = defaultBasemap;

    // Sync basemap radio buttons to the saved default
    const savedRadio = document.querySelector(
        `input[name="basemap"][value="${defaultBasemap}"]`,
    );
    if (savedRadio) savedRadio.checked = true;
    loadGeoJSON();
    setupBasemapRadios();
    setupJenjangCheckboxes();
    setupLayerPanelToggle(layerCollapsed);
    setupSearch();
});

// ─── Setup GeoJSON Zone of Blitar ───────────────────────────────────────────────────
async function loadGeoJSON() {
    try {
        const response = await fetch("/geojson/kecamatan.geojson");
        console.log(response);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const style = {
            color: "#1aa6d9",
            weight: 4,
            opacity: 0.9,
        };

        const data = await response.json();
        const geojsonLayer = L.geoJSON(data, { style: style });
        geojsonLayer.addTo(map);
        // 3. Zoom to fit
        // map.fitBounds(geojsonLayer.getBounds());
    } catch (error) {
        console.error("Error loading the GeoJSON file:", error);
    }
}
// ─── Basemap radio switcher ───────────────────────────────────────────────────
function setupBasemapRadios() {
    document.querySelectorAll('input[name="basemap"]').forEach((radio) => {
        radio.addEventListener("change", (e) => {
            const selected = e.target.value;
            if (selected === activeBasemap) return;

            // Guard: only remove if tile layer exists & is on the map
            const currentLayer = basemaps[activeBasemap];
            if (currentLayer && map.hasLayer(currentLayer)) {
                map.removeLayer(currentLayer);
            }

            if (basemaps[selected]) {
                basemaps[selected].addTo(map);
                activeBasemap = selected;
            }
        });
    });
}

// ─── Jenjang checkbox AJAX loader ────────────────────────────────────────────
function setupJenjangCheckboxes() {
    document.querySelectorAll('input[name="jenjang"]').forEach((checkbox) => {
        checkbox.addEventListener("change", async (e) => {
            const jenjang = e.target.value;

            if (e.target.checked) {
                await loadJenjang(jenjang);
            } else {
                unloadJenjang(jenjang);
            }
        });
    });

    // const jenjangs = ["sd", "smp", "sma", "universitas"];
    // jenjangs.forEach(async (jenjang) => {
    //     const checkbox = document.querySelector(
    //         `input[name="jenjang"][value="${jenjang}"]`,
    //     );
    //     if (checkbox && !checkbox.checked) {
    //         checkbox.checked = true;
    //         await loadJenjang(jenjang);
    //     }
    // });
}

async function loadJenjang(jenjang) {
    try {
        // Clear first to avoid duplicates on re-check
        jenjangLayers[jenjang].clearLayers();

        const response = await fetch(`/api/map/jenjang/${jenjang}`);
        const result = await response.json();

        if (result.success) {
            result.data.forEach((facility) => {
                const marker = createMarker(facility);
                jenjangLayers[jenjang].addLayer(marker);
            });

            if (!layerOnMap[jenjang]) {
                jenjangLayers[jenjang].addTo(map);
                layerOnMap[jenjang] = true;
            }

            // Show count badge
            const badge = document.getElementById(`badge-${jenjang}`);
            if (badge) {
                badge.textContent = result.count;
                badge.classList.remove("hidden");
                badge.classList.add("inline-flex");
            }

            console.log(`Loaded ${result.count} ${jenjang} facilities`);
        }
    } catch (error) {
        console.error(`Error loading ${jenjang}:`, error);
    }
}

function unloadJenjang(jenjang) {
    // Remove markers from allMarkers tracking before clearing
    jenjangLayers[jenjang].eachLayer((layer) => {
        if (layer.facilityId) {
            delete allMarkers[layer.facilityId];
        }
    });

    jenjangLayers[jenjang].clearLayers();

    if (layerOnMap[jenjang]) {
        map.removeLayer(jenjangLayers[jenjang]);
        layerOnMap[jenjang] = false;
    }

    // Hide count badge
    const badge = document.getElementById(`badge-${jenjang}`);
    if (badge) {
        badge.classList.add("hidden");
        badge.classList.remove("inline-flex");
        badge.textContent = "";
    }
}

// ─── Hamburger toggle ─────────────────────────────────────────────────────────
function setupLayerPanelToggle(initialCollapsed = false) {
    const toggleBtn = document.getElementById("layer-toggle-btn");
    const panel = document.getElementById("layer-panel");

    if (!toggleBtn || !panel) return;

    // Apply saved default state
    if (initialCollapsed) {
        panel.classList.add("collapsed");
        toggleBtn.classList.add("active");
    }

    toggleBtn.addEventListener("click", () => {
        panel.classList.toggle("collapsed");
        toggleBtn.classList.toggle("active");
    });
}

// ─── Marker & Popup creation ──────────────────────────────────────────────────
function createMarker(facility) {
    const config = jenjangConfig[facility.klas] || {
        markerColor: "blue",
        markerIcon: "fa-school",
    };

    // Safety check for AwesomeMarkers (CDN plugin might be on window.L)
    const AwesomeMarkers =
        L.AwesomeMarkers || (window.L ? window.L.AwesomeMarkers : null);

    let marker;
    if (AwesomeMarkers) {
        const markerIcon = AwesomeMarkers.icon({
            icon: config.markerIcon,
            prefix: "fa",
            markerColor: config.markerColor,
        });
        marker = L.marker([facility.latitude, facility.longitude], {
            icon: markerIcon,
        });
    } else {
        console.warn(
            "AwesomeMarkers not found, falling back to default marker",
        );
        marker = L.marker([facility.latitude, facility.longitude]);
    }

    marker.facilityId = facility.id;
    marker.facilityData = facility;

    const popupContent = createPopupContent(facility);
    marker.bindPopup(popupContent, {
        maxWidth: 300,
        className: "custom-popup",
    });

    allMarkers[facility.id] = marker;
    return marker;
}

const jenjangConfig = {
    sd: {
        label: "SD",
        gradient: "bg-gradient-to-br from-rose-400 to-rose-600",
        badgeClass: "bg-rose-100 text-rose-800",
        markerColor: "red",
        markerIcon: "fa-school",
    },
    smp: {
        label: "SMP",
        gradient: "bg-gradient-to-br from-sky-700 to-slate-800",
        badgeClass: "bg-sky-100 text-sky-800",
        markerColor: "orange",
        markerIcon: "fa-book",
    },
    sma: {
        label: "SMA",
        gradient: "bg-gradient-to-br from-amber-400 to-amber-600",
        badgeClass: "bg-amber-100 text-amber-800",
        markerColor: "blue",
        markerIcon: "fa-book-open-reader",
    },
    universitas: {
        label: "Universitas",
        gradient: "bg-gradient-to-br from-emerald-500 to-emerald-700",
        badgeClass: "bg-emerald-100 text-emerald-800",
        markerColor: "darkgreen",
        markerIcon: "fa-graduation-cap",
    },
};

function createPopupContent(facility) {
    const imageUrl =
        facility.gallery &&
        Array.isArray(facility.gallery) &&
        facility.gallery.length > 0
            ? `/storage/${facility.gallery[0]}`
            : "/assets/images/default.png";

    const config = jenjangConfig[facility.klas] ?? {
        label: facility.klas?.toUpperCase() ?? "Sekolah",
        gradient: "bg-gradient-to-br from-slate-400 to-slate-600",
        badgeClass: "bg-slate-100 text-slate-800",
    };

    return `
        <div class="map-popup">
            <div class="popup-header ${config.gradient}">
                <img src="${imageUrl}" class="popup-image" alt="${facility.name}">
                <div class="popup-header-overlay">
                    <span class="popup-jenjang-badge ${config.badgeClass}">
                        ${config.label}
                    </span>
                </div>
            </div>
            <div class="popup-body">
                <h3 class="popup-title">${facility.name}</h3>
                <p class="popup-address">📍 ${facility.address ?? "-"}</p>
            </div>
            <div class="popup-footer">
                <button onclick="showDetailModal(${facility.id})" class="popup-btn">
                    Lihat Detail →
                </button>
            </div>
        </div>
    `;
}

// ─── Detail Modal ─────────────────────────────────────────────────────────────

window.showDetailModal = function (facilityId) {
    window.dispatchEvent(
        new CustomEvent("open-map-detail", { detail: facilityId }),
    );
};

// ─── Live Search + Fly‑to‑Marker ──────────────────────────────────────────────
let searchTimeout = null;
let activeIndex = -1;
let searchResults = [];

function setupSearch() {
    const input = document.getElementById("search-input");
    const dropdown = document.getElementById("search-dropdown");
    if (!input || !dropdown) return;

    // Debounced input handler
    input.addEventListener("input", () => {
        clearTimeout(searchTimeout);
        const q = input.value.trim();

        if (q.length < 2) {
            hideDropdown();
            return;
        }

        searchTimeout = setTimeout(() => fetchSearch(q), 300);
    });

    // Keyboard navigation
    input.addEventListener("keydown", (e) => {
        if (!searchResults.length) return;

        if (e.key === "ArrowDown") {
            e.preventDefault();
            activeIndex = Math.min(activeIndex + 1, searchResults.length - 1);
            highlightItem();
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            activeIndex = Math.max(activeIndex - 1, 0);
            highlightItem();
        } else if (e.key === "Enter") {
            e.preventDefault();
            if (activeIndex >= 0 && searchResults[activeIndex]) {
                selectSchool(searchResults[activeIndex]);
            }
        } else if (e.key === "Escape") {
            hideDropdown();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            hideDropdown();
        }
    });
}

async function fetchSearch(query) {
    const dropdown = document.getElementById("search-dropdown");
    if (!dropdown) return;

    try {
        const res = await fetch(
            `/api/map/search?search=${encodeURIComponent(query)}`,
        );
        const result = await res.json();

        if (result.success && result.data.length > 0) {
            searchResults = result.data;
            activeIndex = -1;
            renderDropdown();
        } else {
            searchResults = [];
            dropdown.innerHTML = `<div class="search-empty px-4 py-3 text-sm text-slate-500 italic">Tidak ada hasil untuk "${query}"</div>`;
            dropdown.classList.remove("hidden");
        }
    } catch (err) {
        console.error("Search error:", err);
        hideDropdown();
    }
}

function renderDropdown() {
    const dropdown = document.getElementById("search-dropdown");
    if (!dropdown) return;

    const html = searchResults
        .map((item, i) => {
            const cfg = jenjangConfig[item.klas] ?? {
                label: item.klas.toUpperCase(),
                badgeClass: "bg-slate-100 text-slate-600",
            };
            return `
            <div class="search-item${i === activeIndex ? " active" : ""}" data-index="${i}">
                <div class="search-item-name">${item.name}</div>
                <div class="search-item-meta">
                    <span class="search-item-badge ${cfg.badgeClass}">${cfg.label}</span>
                    <span class="search-item-address">${item.address ? item.address.substring(0, 40) : "-"}</span>
                </div>
            </div>
        `;
        })
        .join("");

    dropdown.innerHTML = html;
    dropdown.classList.remove("hidden");

    // Click handlers for each item
    dropdown.querySelectorAll(".search-item").forEach((el) => {
        el.addEventListener("click", () => {
            const idx = parseInt(el.dataset.index);
            selectSchool(searchResults[idx]);
        });
    });
}

function highlightItem() {
    const dropdown = document.getElementById("search-dropdown");
    if (!dropdown) return;
    dropdown.querySelectorAll(".search-item").forEach((el, i) => {
        el.classList.toggle("active", i === activeIndex);
    });
    // Scroll active item into view
    const active = dropdown.querySelector(".search-item.active");
    if (active) active.scrollIntoView({ block: "nearest" });
}

function hideDropdown() {
    const dropdown = document.getElementById("search-dropdown");
    if (dropdown) {
        dropdown.classList.add("hidden");
        dropdown.innerHTML = "";
    }
    searchResults = [];
    activeIndex = -1;
}

// ─── Select school from search ────────────────────────────────────────────────
async function selectSchool(facility) {
    const input = document.getElementById("search-input");
    if (input) input.value = facility.name;
    hideDropdown();

    // 1. Marker already exists → fly to it
    if (allMarkers[facility.id]) {
        flyToMarker(allMarkers[facility.id]);
        return;
    }

    // 2. Not loaded → load the entire jenjang layer, then fly
    const jenjang = facility.klas;
    if (jenjang && jenjangLayers[jenjang]) {
        await loadJenjang(jenjang);

        // Auto-check the checkbox in the layer panel
        const checkbox = document.querySelector(
            `input[name="jenjang"][value="${jenjang}"]`,
        );
        if (checkbox && !checkbox.checked) checkbox.checked = true;

        // Ensure the cluster group is on the map
        if (!layerOnMap[jenjang]) {
            jenjangLayers[jenjang].addTo(map);
            layerOnMap[jenjang] = true;
        }

        // Now the marker should exist
        if (allMarkers[facility.id]) {
            flyToMarker(allMarkers[facility.id]);
        } else {
            // Fallback: fly to coordinates from search result
            if (facility.latitude && facility.longitude) {
                map.flyTo(
                    [facility.latitude, facility.longitude],
                    map.getMaxZoom(),
                    { duration: 1.2 },
                );
            }
        }
    }
}

function flyToMarker(marker) {
    map.flyTo(marker.getLatLng(), map.getMaxZoom(), { duration: 1.2 });
    // Open popup after fly animation finishes
    setTimeout(() => marker.openPopup(), 1400);
}
