import L from "leaflet";
import "leaflet/dist/leaflet.css";
import { initMap } from "../gis/initial-map";
import "leaflet.markercluster";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";

let map;
let allMarkers = {};
const clusterGroup = L.markerClusterGroup({
    showCoverageOnHover: false,
    zoomToBoundsOnClick: true,
    spiderfyOnMaxZoom: true,
});

const jenjangLayers = {
    sd: L.layerGroup(),
    smp: L.layerGroup(),
    sma: L.layerGroup(),
    universitas: L.layerGroup(),
};

const basemaps = {
    imagery: L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
        {
            maxZoom: 19,
            attribution: "Esri",
        },
    ),
    osm: L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "OpenStreetMap",
    }),
    terrain: L.tileLayer(
        "https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}.png",
        {
            maxZoom: 18,
            attribution: "Stamen",
        },
    ),
};

document.addEventListener("DOMContentLoaded", async () => {
    map = initMap();
    if (!map) return;

    basemaps.osm.addTo(map);

    L.control.layers(basemaps, null, { position: "topright" }).addTo(map);

    await loadFacilities();
});

async function loadFacilities() {
    try {
        const response = await fetch("/api/map/facilities");
        const result = await response.json();

        if (result.success) {
            const facilities = result.data;
            addMarkersToMap(facilities);
            console.log(`Loaded ${result.count} facilities`);
        }
    } catch (error) {
        console.error("Error fetching facilities:", error);
        alert("Gagal memuat data peta. Silakan coba lagi.");
    }
}

function addMarkersToMap(facilities) {
    facilities.forEach((facility) => {
        const marker = createMarker(facility);

        clusterGroup.addLayer(marker);
        jenjangLayers[facility.klas].addLayer(marker);
    });

    clusterGroup.addTo(map);

    Object.values(jenjangLayers).forEach((layer) => layer.addTo(map));

    L.control
        .layers(
            {
                SD: jenjangLayers.sd,
                SMP: jenjangLayers.smp,
                SMA: jenjangLayers.sma,
                Universitas: jenjangLayers.universitas,
            },
            null,
            { position: "topright" },
        )
        .addTo(map);
}

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

function createPopupContent(facility) {
    const imageUrl = facility.image
        ? `/storage/${facility.image}`
        : "/assets/images/default.png";

    return `
        <div class="map-popup">
            <div class="popup-header">
                <img src="${imageUrl}" class="popup-image" alt="${facility.name}">
                <h3 class="popup-title text-center">${facility.name}</h3>
            </div>
            <div class="popup-body">
                <p class="popup-address text-center">📍 ${facility.address}</p>
            </div>
            <div class="item-center">
                <button onclick="showDetailModal(${facility.id})" class="popup-btn">
                Lihat Detail →
                </button>
            </div>
        </div>
    `;
}

function truncateText(text, maxLength) {
    if (!text) return "";
    return text.length > maxLength
        ? text.substring(0, maxLength) + "..."
        : text;
}

window.showDetailModal = function (facilityId) {
    alert("Detail modal for facility ID: " + facilityId);
};
