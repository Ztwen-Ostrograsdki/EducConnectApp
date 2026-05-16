// vite.config.js
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import fullReload from "vite-plugin-full-reload";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),

        // Rechargement complet quand les fichiers Livewire changent
        fullReload(["app/Livewire/**/*.php", "routes/**/*.php"]),
    ],

    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },

    // Pré-bundler Motion et Alpine pour accélérer le dev
    optimizeDeps: {
        include: ["motion", "alpinejs"],
    },
});
