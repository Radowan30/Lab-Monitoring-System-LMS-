import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // Listen on all interfaces
        port: 5173,      // Optional: Explicitly set the port
        strictPort: true, // Prevent port fallback
        hmr: {
            host: '192.168.1.112', // Replace with your local IP address
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/lab-room.css'],
            refresh: true,
        }),
    ],
});