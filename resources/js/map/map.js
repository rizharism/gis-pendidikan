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
    terrain: L.tileLayer(
        "https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}.png",
        { maxZoom: 18, attribution: "© Stamen Design" },
    ),
};

// Track currently active basemap key
let activeBasemap = "osm";

// ─── Jenjang layer groups (one cluster group per jenjang) ────────────────────
const jenjangLayers = {
    sd: L.markerClusterGroup({ showCoverageOnHover: false, zoomToBoundsOnClick: true, spiderfyOnMaxZoom: true }),
    smp: L.markerClusterGroup({ showCoverageOnHover: false, zoomToBoundsOnClick: true, spiderfyOnMaxZoom: true }),
    sma: L.markerClusterGroup({ showCoverageOnHover: false, zoomToBoundsOnClick: true, spiderfyOnMaxZoom: true }),
    universitas: L.markerClusterGroup({ showCoverageOnHover: false, zoomToBoundsOnClick: true, spiderfyOnMaxZoom: true }),
};

// Track which jenjang layers are currently on the map
const layerOnMap = { sd: false, smp: false, sma: false, universitas: false };

// ─── Init ────────────────────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
    map = initMap();
    if (!map) return;

    // Add default basemap
    basemaps.osm.addTo(map);

    setupBasemapRadios();
    setupJenjangCheckboxes();
    setupLayerPanelToggle();
});

// ─── Basemap radio switcher ───────────────────────────────────────────────────
function setupBasemapRadios() {
    document.querySelectorAll('input[name="basemap"]').forEach((radio) => {
        radio.addEventListener("change", (e) => {
            const selected = e.target.value;
            if (selected === activeBasemap) return;

            map.removeLayer(basemaps[activeBasemap]);
            basemaps[selected].addTo(map);
            activeBasemap = selected;
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
                badge.style.display = "inline-flex";
            }

            console.log(`Loaded ${result.count} ${jenjang} facilities`);
        }
    } catch (error) {
        console.error(`Error loading ${jenjang}:`, error);
    }
}

function unloadJenjang(jenjang) {
    jenjangLayers[jenjang].clearLayers();

    if (layerOnMap[jenjang]) {
        map.removeLayer(jenjangLayers[jenjang]);
        layerOnMap[jenjang] = false;
    }

    // Hide count badge
    const badge = document.getElementById(`badge-${jenjang}`);
    if (badge) {
        badge.style.display = "none";
        badge.textContent = "";
    }
}

// ─── Hamburger toggle ─────────────────────────────────────────────────────────
function setupLayerPanelToggle() {
    const toggleBtn = document.getElementById("layer-toggle-btn");
    const panel = document.getElementById("layer-panel");

    if (!toggleBtn || !panel) return;

    toggleBtn.addEventListener("click", () => {
        panel.classList.toggle("collapsed");
        toggleBtn.classList.toggle("active");
    });
}

// ─── Marker & Popup creation ──────────────────────────────────────────────────
function createMarker(facility) {
    const marker = L.marker([facility.latitude, facility.longitude]);

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
        gradient: "linear-gradient(135deg, #3b82f6, #1d4ed8)",
        badge: "#dbeafe",
        badgeText: "#1e40af",
    },
    smp: {
        label: "SMP",
        gradient: "linear-gradient(135deg, #10b981, #059669)",
        badge: "#d1fae5",
        badgeText: "#065f46",
    },
    sma: {
        label: "SMA",
        gradient: "linear-gradient(135deg, #f59e0b, #d97706)",
        badge: "#fef3c7",
        badgeText: "#92400e",
    },
    universitas: {
        label: "Universitas",
        gradient: "linear-gradient(135deg, #8b5cf6, #7c3aed)",
        badge: "#ede9fe",
        badgeText: "#4c1d95",
    },
};

function createPopupContent(facility) {
    const imageUrl = facility.image
        ? `/storage/${facility.image}`
        : "/assets/images/default.png";

    const config = jenjangConfig[facility.klas] ?? {
        label: facility.klas?.toUpperCase() ?? "Sekolah",
        gradient: "linear-gradient(135deg, #64748b, #475569)",
        badge: "#f1f5f9",
        badgeText: "#334155",
    };

    return `
        <div class="map-popup">
            <div class="popup-header" style="background: ${config.gradient};">
                <img src="${imageUrl}" class="popup-image" alt="${facility.name}">
                <div class="popup-header-overlay">
                    <span class="popup-jenjang-badge" style="background:${config.badge}; color:${config.badgeText};">
                        ${config.label}
                    </span>
                </div>
            </div>
            <div class="popup-body">
                <h3 class="popup-title">${facility.name}</h3>
                <p class="popup-address">📍 ${facility.address ?? '-'}</p>
            </div>
            <div class="popup-footer">
                <button onclick="showDetailModal(${facility.id})" class="popup-btn">
                    Lihat Detail →
                </button>
            </div>
        </div>
    `;
}

window.showDetailModal = function (facilityId) {
    alert("Detail modal for facility ID: " + facilityId);
};
