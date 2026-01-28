import L from "leaflet";
import "leaflet/dist/leaflet.css";

let mapInstance = null;

export function initMap() {
    if (mapInstance) return mapInstance; // cegah double init

    mapInstance = L.map("map").setView(
        [-8.098194632146122, 112.16521834801217],
        18,
    );

    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "&copy; OpenStreetMap",
    }).addTo(mapInstance);

    return mapInstance;
}

export function getMap() {
    return mapInstance;
}
