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
let vendorPackages = ['vue', 'axios', 'moment'];

mix.js('resources/themes/reservation-doctors/assets/PublicReservation/Schedule.js', 'assets/themes/reservation-doctors/dist/js');

mix.js('resources/themes/reservation-touring/assets/Booking/Booking.js', 'assets/themes/reservation-touring/dist/js');


//keep the following last thing to generate the files in public
mix.extract(vendorPackages);

mix.js('resources/assets/js/laravel-echo-setup.js', 'assets/core/compiled/js');
