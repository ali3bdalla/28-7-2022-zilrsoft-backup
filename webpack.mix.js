const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss')

mix.js('./resources/js/app.js', 'public/js');
mix.js('./resources/js/app_v2.js', 'public/js');

mix.sass('resources/sass/tailwind.scss', 'public/css/tailwind.css')
    .options({
        processCssUrls: false,
        postCss: [ tailwindcss('./tailwind.config.js') ],
    })



