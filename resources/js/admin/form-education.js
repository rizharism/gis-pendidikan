import L from "leaflet";
import { initMap } from "../gis/initial-map";

// 1. Import asset icon agar diproses oleh Vite
import markerIcon from "leaflet/dist/images/marker-icon.png";
import markerIconRetina from "leaflet/dist/images/marker-icon-2x.png";
import markerShadow from "leaflet/dist/images/marker-shadow.png";

// 2. Timpa konfigurasi default icon Leaflet
let DefaultIcon = L.icon({
    iconUrl: markerIcon,
    iconRetinaUrl: markerIconRetina,
    shadowUrl: markerShadow,
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41],
});

L.Marker.prototype.options.icon = DefaultIcon;

document.addEventListener("DOMContentLoaded", () => {
    const map = initMap();
    if (!map) return;

    const latlongInput = document.getElementById("latlong");

    // Jika ada koordinat awal (edit mode), arahkan peta ke sana
    if (latlongInput.value) {
        const coords = latlongInput.value
            .split(",")
            .map((c) => parseFloat(c.trim()));
        if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
            map.setView(coords, 18);
        }
    }

    const marker = L.marker(map.getCenter())
        .addTo(map)
        .bindPopup("Geser peta untuk menentukan titik lokasi sekolah.", {
            autoPan: false,
        })
        .openPopup();

    map.on("move", () => {
        const center = map.getCenter();
        latlongInput.value = `${center.lat.toFixed(6)}, ${center.lng.toFixed(6)}`;
        marker.setLatLng(center);
    });
});
