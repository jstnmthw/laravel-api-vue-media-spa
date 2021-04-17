const mix = require('laravel-mix')
require('laravel-mix-criticalcss')
require('laravel-mix-purgecss')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .webpackConfig(require('./webpack.config'))
  .js('resources/js/app.js', 'public/js')
  .vue()
  .sass('resources/sass/app.scss', 'public/css')
  .criticalCss({
    enabled: mix.inProduction(),
    paths: {
      base: 'http://localhost',
      templates: './public/css/',
      suffix: '.min'
    },
    urls: [{ url: '/', template: 'critical' }],
    options: {
      minify: true,
      penthouse: {
        blockJSRequests: false
      }
    }
  })
  .purgeCss()

if (mix.inProduction()) {
  mix.version()
}
