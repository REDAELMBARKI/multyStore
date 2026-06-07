import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.tsx',
            refresh: true,
        }),
        react(),
    ],
    server: {
        host: 'localhost',
        hmr: {
            host: 'localhost',
        },
    },
    optimizeDeps: {
        // Force Vite to always re-bundle dependencies if they change
        // This helps with the 504 Outdated Dep issue
        entries: [
            'resources/js/app.tsx',
        ],
    },
});
