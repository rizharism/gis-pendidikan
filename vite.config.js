import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { map } from "leaflet";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/gis/initial-map.js",
                "resources/js/map/map.js",
                "resources/js/admin/form-education.",
            ],
            refresh: true,
        }),
    ],
});
