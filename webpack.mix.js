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

mix.js([
    'resources/js/app.js',
    'resources/inspinia/jquery.metisMenu.js',
    'resources/inspinia/jquery.slimscroll.min.js',
    'resources/inspinia/inspinia.js',
    'resources/inspinia/pace.min.js',
], 'public/js/app.js')
    .extract(['jquery', 'bootstrap', 'toastr'])
    .sourceMaps()
    .version();

mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'node_modules/toastr/build/toastr.css',
    'node_modules/vue-awesome-notifications/dist/styles/style.css',
    'resources/inspinia/font-awesome/css/font-awesome.css',
    'resources/inspinia/style.css',
    'resources/inspinia/custom.css',
], 'public/css/app.css')
    .sourceMaps()
    .version();

mix.copy('resources/inspinia/font-awesome/fonts', 'public/fonts');


mix.scripts([
    'node_modules/jquery-blockui/jquery.blockUI.js',
    'node_modules/jquery-mask-plugin/src/jquery.mask.js',
    'resources/vendor/jquery.maskMoney.min.js',
    'resources/js/boot-jquery.mask.js',
    'resources/js/boot-functions.js',
], 'public/js/functions.js')
    .sourceMaps()
    .version();
