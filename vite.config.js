import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Components from 'unplugin-vue-components/vite';
import {PrimeVueResolver} from '@primevue/auto-import-resolver';
import { resolve } from 'path';
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
        Components({
            resolvers: [
                PrimeVueResolver()
            ]
        }),
    ],
    resolve:{
        alias:{
            vue: "vue/dist/vue.esm-bundler.js",
            '@images': resolve(__dirname, './public/src/images'),
            '@components': resolve(__dirname, './resources'),

        }
    },
    optimizeDeps: {
        exclude: ['path', 'fs', 'url', 'source-map-js']
    },
    build: {
        rollupOptions: {
            external: ['path', 'fs', 'url', 'source-map-js']
        }
    },
    define: {
        'process.env': process.env
      }
});
