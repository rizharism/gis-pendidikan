import L from "leaflet";
import { initMap } from "../gis/initial-map";

document.addEventListener("DOMContentLoaded", () => {
    const map = initMap();
    if (!map) return;

    const latlongInput = document.getElementById("latlong");
    
    // Jika ada koordinat awal (edit mode), arahkan peta ke sana
    if (latlongInput.value) {
        const coords = latlongInput.value.split(",").map(c => parseFloat(c.trim()));
        if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
            map.setView(coords, 18);
        }
    }

    const marker = L.marker(map.getCenter())
        .addTo(map)
        .bindPopup("Geser peta untuk menentukan titik lokasi sekolah.", {
            autoPan: false
        })
        .openPopup();

    map.on("move", () => {
        const center = map.getCenter();
        latlongInput.value = `${center.lat.toFixed(6)}, ${center.lng.toFixed(6)}`;
        marker.setLatLng(center);
    });
});
