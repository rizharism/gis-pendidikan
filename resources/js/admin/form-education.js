import L from "leaflet";
import { initMap } from "../gis/initial-map";

document.addEventListener("DOMContentLoaded", () => {
    const map = initMap();
    if (!map) return;

    const marker = L.marker(map.getCenter())
        .addTo(map)
        .bindPopup("Drag peta untuk mendapatkan lat long.")
        .openPopup();

    map.on("move", () => {
        const center = map.getCenter();
        document.getElementById("latlong").value =
            `${center.lat.toFixed(6)}, ${center.lng.toFixed(6)}`;
        marker.setLatLng(center).update();
    });
});
