let mix = require('laravel-mix');

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

mix.react('resources/assets/js/app.js', 'public/js')
.extract([
  'axios',
  'prop-types',
  'react',
  'react-bootstrap',
  'react-dom',
  'react-router-dom',
])
.less('resources/assets/less/app.less', 'public/css');

if (mix.inProduction()) {
  mix.version()
}
