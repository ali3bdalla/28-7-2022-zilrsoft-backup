const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')

if (mix.inProduction()) {
  mix.js('./resources/js/online-store.js', 'public/js')
    .sass('resources/sass/store.scss', 'public/css/')
    .sass('resources/sass/rtl_store.scss', 'public/css/')
} else {
  mix.js('./resources/js/app.js', 'public/js')
    .js('./resources/js/online-store.js', 'public/js')
    .js('./resources/js/external/app.js', 'public/js/external.js')
    .js('./resources/js/upload_images.js', 'public/js')
    .sass('./resources/sass/main.scss', 'public/css/main.css')
    .sass('resources/sass/store.scss', 'public/css/')
    .sass('resources/sass/rtl_store.scss', 'public/css/')
    .sass('resources/sass/images.scss', 'public/css/')
    .browserSync({
      proxy: 'http://zilrsoft.test'
    })
}

mix.version()
  .options({
    processCssUrls: false,
    postCss: [tailwindcss('./tailwind.config.js')]
  })
