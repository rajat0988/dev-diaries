import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/login.css',
                'resources/css/profile.css',
                'resources/css/showQuestions.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '192.168.1.18', // Use your server's IP address
        hmr: {
            host: '192.168.1.18', // Use the same IP address for HMR
        },
    },
});
