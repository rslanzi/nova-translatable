let mix = require('laravel-mix')

mix
    .setPublicPath('dist')
    .sass('resources/sass/field.scss', 'css')
    .js('resources/js/field.js', 'js');
