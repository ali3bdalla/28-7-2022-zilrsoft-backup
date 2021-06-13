const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')

mix.js('./resources/js/app.js', 'public/js')
  .js('./resources/js/online-store.js', 'public/js')
  .js('./resources/js/external/app.js', 'public/js/external.js')
  .js('./resources/js/upload_images.js', 'public/js')
  .sass('./resources/sass/main.scss', 'public/css/main.css')
  .sass('resources/sass/store.scss', 'public/css/')
  .sass('resources/sass/rtl_store.scss', 'public/css/')
  .sass('resources/sass/images.scss', 'public/css/')
  .options({
    processCssUrls: false,
    postCss: [tailwindcss('./tailwind.config.js')]
  })
  .browserSync({
    proxy: 'http://dev.test'
  })
  .version()
