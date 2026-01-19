// const mix = require('laravel-mix');
//
// mix.js('resources/js/app.js', 'public/js')
//     .js('resources/js/admin.js', 'public/js')
//     .sass('resources/css/sass/admin.scss', 'public/css/admin.css')
//     .vue()
//     .copyDirectory('resources/shuchack/', 'public/shuchack')
//     .copyDirectory('resources/theshuchak/', 'public/theshuchak')
//     .copyDirectory('resources/assets/', 'public/assets')
//     .copyDirectory('resources/assets/images', 'public/images')
//     .copyDirectory('resources/js/manifest_user.json', 'public/manifest.json')
//     .copyDirectory('resources/js/userserviceworker.js', 'public/serviceworker.js')
//     .copyDirectory('resources/js/offline.html', 'public/offline.html')
//     .autoload({
//         '@popperjs/core': ['Popper'],
//         jquery: ['$', 'window.jQuery', 'jQuery'],
//     })
//     .webpackConfig(require('./webpack.config'));
//
// if (mix.inProduction()) {
//     mix.minify('public/js/app.js');
//     mix.minify('public/js/admin.js');
//     mix.version();
// }


const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/admin.js', 'public/js')
    .sass('resources/css/sass/admin.scss', 'public/css/admin.css')
    .vue()

    // Copy static assets (ONLY ONCE)
    .copyDirectory('resources/shuchack', 'public/shuchack')
    .copyDirectory('resources/theshuchak', 'public/theshuchak')
    .copyDirectory('resources/assets/images', 'public/images')

    // PWA / misc
    .copy('resources/js/manifest_user.json', 'public/manifest.json')
    .copy('resources/js/userserviceworker.js', 'public/serviceworker.js')
    .copy('resources/js/offline.html', 'public/offline.html')

    .autoload({
        '@popperjs/core': ['Popper'],
        jquery: ['$', 'window.jQuery', 'jQuery'],
    })
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}
