import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: '192.168.0.169',
            protocol: 'ws',
            port: 5173
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/sidebar.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
