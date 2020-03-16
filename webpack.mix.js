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
    'node_modules/jquery-blockui/jquery.blockUI.js',
    'node_modules/jquery-mask-plugin/src/jquery.mask.js',
    'resources/js/boot-jquery.mask.js',
    'node_modules/select2/dist/js/select2.full.js',
    'resources/js/boot-select2.js',
    'resources/js/boot-functions.js',
], 'public/js/app.js')
    .extract(['jquery', 'bootstrap', 'toastr', 'jquery-blockui', 'select2', 'jquery-mask-plugin'])
    .sourceMaps()
    .version();


mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.css',
    'node_modules/toastr/build/toastr.css',
    'resources/inspinia/font-awesome/css/font-awesome.css',
    'resources/inspinia/style.css',
    'resources/inspinia/custom.css',
    'node_modules/select2/dist/css/select2.css',
    'node_modules/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css',
], 'public/css/app.css')
    .sourceMaps()
    .version();

mix.copy('resources/inspinia/font-awesome/fonts', 'public/fonts');


/*'node_modules/eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js',
    'node_modules/moment/min/moment.min.js',
    'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
    'node_modules/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js',
    'resources/js/boot-datepicker.js',*/
/*

mix.styles([
    'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
], 'public/css/datepicker.css');

// datetimepicker
mix.styles([
        'node_modules/eonasdan-bootstrap-datetimepicker/src/sass/bootstrap-datetimepicker-build.scss',
    ], 'public/css/custom-datetimepicker.css');
*/
