import L from "leaflet";
import "leaflet/dist/leaflet.css";
import { initMap } from "../gis/initial-map";
import "leaflet.markercluster/dist/MarkerCluster.css";
import "leaflet.markercluster/dist/MarkerCluster.Default.css";
import "leaflet.markercluster";

// const map = initMap();

document.addEventListener("DOMContentLoaded", async () => {
    const map = initMap();
    if (!map) return;

    try {
        const response = await fetch("/assets/dummy-data/dummy.json");
        const data = await response.json();
        const schools = data.data;
        const markers = L.markerClusterGroup();

        schools.forEach((school) => {
            const marker = L.marker([school.lat, school.long]).bindPopup(
                `<b>${school.nama}</b><br>${school.alamat}`,
            );
            markers.addLayer(marker);
        });
        markers.addTo(map);
    } catch (error) {
        console.error("Error fetching dummy data:", error);
    }
});
