const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss')

mix.js('./resources/js/app.js', 'public/js');
mix.js('./resources/js/online-store.js', 'public/js');
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
    //  proxy:"http://127.0.0.1:8009"
    proxy:  "https://zilrsoft.test",
  });

