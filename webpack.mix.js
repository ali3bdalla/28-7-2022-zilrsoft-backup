const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss')

mix.js('./resources/js/app.js', 'public/js');
mix.js('./resources/js/app_v2.js', 'public/js');
mix.js('./resources/js/external/app.js', 'public/js/external.js');
mix.js('./resources/js/upload_images.js', 'public/js');
mix.sass('./resources/sass/main.scss', 'public/css/main.css').options({
    processCssUrls: false,
    postCss: [tailwindcss('./tailwind.config.js')],
});
mix.sass('resources/sass/tailwind.scss', 'public/css/tailwind.css')
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('./tailwind.config.js')],
    })



