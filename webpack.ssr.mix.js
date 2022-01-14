const mix = require('laravel-mix')
const webpackNodeExternals = require('webpack-node-externals')

mix
  .options({ manifest: false })
  .js('resources/js/store-app/ssr.js', 'public/js/store-app')
  .vue({
    version: 2,
    options: { optimizeSSR: true }
  })
  .webpackConfig({
    target: 'node',
    externals: [webpackNodeExternals()],
  })
