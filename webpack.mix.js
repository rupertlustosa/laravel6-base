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

mix.js('resources/js/app.js', 'public/js/app.js')
    .extract(['jquery', 'bootstrap', 'toastr'])
    .sourceMaps();

mix.js('resources/js/laravel-echo-setup.js', 'public/js');

mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'node_modules/toastr/build/toastr.css',
    'node_modules/vue-awesome-notifications/dist/styles/style.css',
], 'public/css/app.css')
    .sourceMaps();

mix.styles([
    'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
], 'public/css/datepicker.css');

mix.styles([
    'resources/inspinia/font-awesome/css/font-awesome.css',
    'resources/inspinia/style.css',
    'resources/inspinia/custom.css',
], 'public/css/theme.css')
    .sourceMaps();

mix.copy('resources/inspinia/font-awesome/fonts', 'public/fonts');

// jquery.mask
mix.scripts([
    'node_modules/jquery-mask-plugin/src/jquery.mask.js',
    'resources/js/boot-jquery.mask.js',
], 'public/js/custom-masks.js');

mix.scripts([
    'node_modules/jquery-blockui/jquery.blockUI.js',
    'resources/js/boot-functions.js',
], 'public/js/functions.js')
    .sourceMaps();
