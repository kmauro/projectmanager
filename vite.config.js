import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: 'localhost',
        port: 5173,
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
