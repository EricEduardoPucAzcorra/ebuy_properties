import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/site/site.js',
                'resources/site/site.css',
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true, // Ignorar advertencias de dependencias
                logger: {
                    warn: () => {} // Silenciar warnings
                }
            }
        }
    }
});
