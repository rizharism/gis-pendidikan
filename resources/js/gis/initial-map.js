import L from "leaflet";
window.L = L;
import "leaflet/dist/leaflet.css";

let mapInstance = null;

export function initMap() {
    if (mapInstance) return mapInstance; // cegah double init

    mapInstance = L.map("map").setView(
        [-8.098194632146122, 112.16521834801217],
        13,
    );

    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "&copy; OpenStreetMap",
    }).addTo(mapInstance);

    // Fix Leaflet not filling the container on mobile when the browser
    // chrome (address bar) shows/hides or the device is rotated.
    let resizeTimer;
    window.addEventListener("resize", () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            mapInstance.invalidateSize();
        }, 150);
    });

    return mapInstance;
}

export function getMap() {
    return mapInstance;
}
