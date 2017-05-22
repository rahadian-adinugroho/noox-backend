const { mix } = require('laravel-mix');

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

// mix.js('resources/assets/js/app.js', 'public/js')
//    .sass('resources/assets/sass/app.scss', 'public/css');

mix.js('resources/assets/admin/js/app.js', 'public/admin/js')
   .js('resources/assets/admin/js/tables.js', 'public/admin/js')
   .sass('resources/assets/admin/sass/app.scss', 'public/admin/css')
   .sass('resources/assets/admin/sass/tables.scss', 'public/admin/css');