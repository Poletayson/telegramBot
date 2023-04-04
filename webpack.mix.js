const mix = require('laravel-mix');
const cssnanoPlugin = require("cssnano");


function findFiles(dir) {
    const fs = require('fs');
    return fs.readdirSync(dir).filter(file => {
        return fs.statSync(`${dir}/${file}`).isFile();
    });
}

function buildSass(dir, dest) {
    findFiles(dir).forEach(function (file) {
        if ( ! file.startsWith('_')) {
            mix.sass(dir + '/' + file, dest);
        }
    });
}


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.scripts('resources/js/supportChat.js', 'public/js/supportChat.js')
    .scripts('resources/js/adminPanel.js', 'public/js/adminPanel.js')
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/scripts.js', 'public/js')

    // .copy('resources/css', 'public/css')

    .copy(['resources/js/vendor.js'], 'public/js')
    .copy(['resources/img'], 'public/images')
    .copy(['resources/fonts'], 'public/fonts')
    .copy(['resources/js/Bootstrap'], 'public/js/Bootstrap')
    .copy(['resources/css/Bootstrap'], 'public/css/Bootstrap')
    .copy(['resources/css/table.css'], 'public/css')

    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/auth.scss', 'public/css')
    .sass('resources/sass/exception.scss', 'public/css')
    .sass('resources/sass/chat.scss', 'public/css');

// buildSass('resources/sass', 'public/css');
// buildSass('resources/sass/Contacts', 'public/css/Contacts');
