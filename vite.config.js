import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve:{
        alias:{
            vue: "vue/dist/vue.esm-bundler.js",
            '@images': resolve(__dirname, './public/src/images'),
            '@components': resolve(__dirname, './resources'),
        }
    }
});
