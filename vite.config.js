import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import {copy} from "laravel-mix";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/sass/main.scss',
                'resources/sass/room.scss',
                'resources/sass/login.scss',
                'resources/js/main.js'
            ],
            refresh: true,
        }),
    ],
});
