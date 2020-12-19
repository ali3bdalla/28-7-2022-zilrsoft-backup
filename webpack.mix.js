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
    .sass('resources/sass/web/ar/store.scss', 'public/css/ar')
    .sass('resources/sass/web/store.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('./tailwind.config.js')],
    })



 mix.browserSync({
    //  proxy:"http://192.168.0.149:8000"
    proxy:  "https://zilrsoftproject.test",
  });

