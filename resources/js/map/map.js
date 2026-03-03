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

// Track currently active basemap key – read from meta tag or fall back to 'osm'
let activeBasemap = document.querySelector('meta[name="default-basemap"]')?.content || "osm";

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

    // Read saved settings from meta tags
    const defaultBasemap = document.querySelector('meta[name="default-basemap"]')?.content || "osm";
    const layerCollapsed  = document.querySelector('meta[name="layer-control-collapsed"]')?.content === "1";

    // Add default basemap from settings
    const bm = basemaps[defaultBasemap] || basemaps.osm;
    bm.addTo(map);
    activeBasemap = defaultBasemap;

    // Sync basemap radio buttons to the saved default
    const savedRadio = document.querySelector(`input[name="basemap"][value="${defaultBasemap}"]`);
    if (savedRadio) savedRadio.checked = true;

    setupBasemapRadios();
    setupJenjangCheckboxes();
    setupLayerPanelToggle(layerCollapsed);
    setupDetailModal();
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
        gradient: "linear-gradient(135deg, #f43f5e, #e11d48)",
        badge: "#ffe4e6",
        badgeText: "#9f1239",
    },
    smp: {
        label: "SMP",
        gradient: "linear-gradient(135deg, #005c83, #254669)",
        badge: "#e0f2fe",
        badgeText: "#0c4a6e",
    },
    sma: {
        label: "SMA",
        gradient: "linear-gradient(135deg, #f59e0b, #d97706)",
        badge: "#fef3c7",
        badgeText: "#92400e",
    },
    universitas: {
        label: "Universitas",
        gradient: "linear-gradient(135deg, #27a154, #1d8a45)",
        badge: "#dcfce7",
        badgeText: "#14532d",
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

// ─── Detail Modal ─────────────────────────────────────────────────────────────
function setupDetailModal() {
    const modal = document.getElementById("detail-modal");
    const backdrop = modal?.querySelector(".modal-backdrop");
    const closeBtn = document.getElementById("modal-close-btn");

    if (!modal) return;

    // Close on ✕ button
    closeBtn?.addEventListener("click", closeDetailModal);

    // Close on backdrop click
    backdrop?.addEventListener("click", closeDetailModal);

    // Close on Escape key
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && modal.style.display !== "none") {
            closeDetailModal();
        }
    });
}

function closeDetailModal() {
    const modal = document.getElementById("detail-modal");
    if (modal) {
        modal.classList.remove("show");
        setTimeout(() => {
            modal.style.display = "none";
        }, 250);
    }
}

window.showDetailModal = async function (facilityId) {
    const modal = document.getElementById("detail-modal");
    const loading = document.getElementById("modal-loading");
    const modalBody = modal?.querySelector(".modal-body");
    const modalHeader = document.getElementById("modal-header");

    if (!modal) return;

    // Show modal with loading state
    modal.style.display = "flex";
    requestAnimationFrame(() => modal.classList.add("show"));

    if (loading) loading.style.display = "flex";
    if (modalBody) modalBody.style.display = "none";

    try {
        const response = await fetch(`/api/map/detail/${facilityId}`);
        const result = await response.json();

        if (result.success) {
            const facility = result.data;
            const config = jenjangConfig[facility.klas] ?? {
                label: facility.klas?.toUpperCase() ?? "Sekolah",
                gradient: "linear-gradient(135deg, #64748b, #475569)",
                badge: "#f1f5f9",
                badgeText: "#334155",
            };

            // Populate header
            const imageUrl = facility.image
                ? `/storage/${facility.image}`
                : "/assets/images/default.png";

            const modalImage = document.getElementById("modal-image");
            if (modalImage) {
                modalImage.src = imageUrl;
                modalImage.alt = facility.name;
            }

            if (modalHeader) {
                modalHeader.style.background = config.gradient;
            }

            const jenjangBadge = document.getElementById("modal-jenjang-badge");
            if (jenjangBadge) {
                jenjangBadge.textContent = config.label;
                jenjangBadge.style.background = config.badge;
                jenjangBadge.style.color = config.badgeText;
            }

            // Populate body
            const nameEl = document.getElementById("modal-name");
            if (nameEl) nameEl.textContent = facility.name;

            const addressEl = document.getElementById("modal-address");
            if (addressEl) addressEl.textContent = facility.address || "-";

            const descEl = document.getElementById("modal-description");
            if (descEl) descEl.textContent = facility.description || "Belum ada deskripsi.";

            const coordsEl = document.getElementById("modal-coords");
            if (coordsEl) coordsEl.textContent = `${facility.latitude}, ${facility.longitude}`;

            // Switch from loading to content
            if (loading) loading.style.display = "none";
            if (modalBody) modalBody.style.display = "block";
        }
    } catch (error) {
        console.error("Error fetching detail:", error);
        if (loading) loading.style.display = "none";
        if (modalBody) {
            modalBody.style.display = "block";
            const nameEl = document.getElementById("modal-name");
            if (nameEl) nameEl.textContent = "Gagal memuat data";
        }
    }
};
