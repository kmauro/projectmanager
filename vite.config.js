import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: '192.168.0.168',
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/sidebar.css',
                'resources/js/app.js',
                'resources/imgs/logo_argentina-azul.svg',
            ],
            refresh: true,
        }),
    ],
});
