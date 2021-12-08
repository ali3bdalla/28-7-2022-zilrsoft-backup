const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')

if (mix.inProduction()) {
  mix.js('resources/js/store-app/app.js', 'public/js/store-app/app.js')
    .sass('resources/sass/store.scss', 'public/css/')
    .sass('resources/sass/rtl_store.scss', 'public/css/')
    .version()
} else {
  mix
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/store-app/app.js', 'public/js/store-app/app.js')
    .js('resources/js/external/app.js', 'public/js/external.js')
    .sass('resources/sass/main.scss', 'public/css/main.css')
    .sass('resources/sass/store.scss', 'public/css/')
    .sass('resources/sass/rtl_store.scss', 'public/css/')
    .browserSync({
      proxy: 'http://localhost:8080'
    })
}
mix.options({
  processCssUrls: false,
  postCss: [tailwindcss('./tailwind.config.js')]
}).version()
