const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

mix.js('resources/js/adminlte.js', 'public/js');
mix.js('resources/js/pages/dashboard.js', 'public/js/pages');
mix.js('resources/js/demo.js', 'public/js');

mix.css('resources/css/adminlte.min.css', 'public/css');



