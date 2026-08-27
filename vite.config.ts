import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { compression } from 'vite-plugin-compression2';
import { ViteImageOptimizer } from 'vite-plugin-image-optimizer';
import tsconfigPaths from 'vite-tsconfig-paths';
import path from 'path';

/**
 * Vite Build Configuration — PT Jaringan Teknologi Sejahtera
 *
 * Pipeline:
 *  - Laravel plugin: hot-reload + manifest untuk Blade @vite directive
 *  - Brotli + Gzip compression untuk asset production
 *  - Image optimizer: kompres aset gambar saat build
 *  - Path alias: import bersih lintas folder resources/js
 */
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.ts',
                'resources/js/admin.ts',
            ],
            refresh: [
                'resources/views/**',
                'app/Http/Controllers/**',
                'routes/**',
            ],
        }),
        tsconfigPaths(),
        compression({ algorithm: 'brotliCompress', exclude: [/\.(br)$/, /\.(gz)$/] }),
        compression({ algorithm: 'gzip', exclude: [/\.(br)$/, /\.(gz)$/] }),
        ViteImageOptimizer({
            png: { quality: 80 },
            jpeg: { quality: 80 },
            jpg: { quality: 80 },
            webp: { quality: 80 },
            svg: {
                multipass: true,
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@scss': path.resolve(__dirname, 'resources/scss'),
            '@components': path.resolve(__dirname, 'resources/js/components'),
            '@modules': path.resolve(__dirname, 'resources/js/modules'),
            '@utils': path.resolve(__dirname, 'resources/js/utils'),
            '@types': path.resolve(__dirname, 'resources/js/types'),
        },
    },
    build: {
        target: 'es2022',
        chunkSizeWarningLimit: 1000,
        sourcemap: false,
        minify: 'esbuild',
        cssMinify: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    three: ['three'],
                    gsap: ['gsap'],
                    swiper: ['swiper'],
                    vendor: ['alpinejs', 'axios', 'chart.js'],
                },
            },
        },
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: 'localhost',
        },
    },
    css: {
        devSourcemap: true,
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler',
            },
        },
    },
});
