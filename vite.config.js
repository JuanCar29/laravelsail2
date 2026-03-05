import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],

    server: {
        host: true,            // Escucha en 0.0.0.0
        port: 5173,
        strictPort: true,

        hmr: {
            host: '192.168.0.3',   // IP real del NAS
            port: 5173,
            protocol: 'ws',        // Requerido si el NAS bloquea wss
        },
    },
});
