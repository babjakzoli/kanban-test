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

mix
	.copy('resources/images/', 'public/images/')
	.js('resources/js/app.js', 'public/js')
   	.sass('resources/sass/app.scss', 'public/css')
	.version();

mix.autoload({
	jquery: ['$', 'window.jQuery',"jQuery","window.$","jquery","window.jquery"]
});
