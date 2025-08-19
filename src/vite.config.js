import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/test_websocket.css',
                'resources/js/app.js',
                'resources/js/two-factor-auth.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        port: 5174,
    },
});
