let mix = require('laravel-mix')

// mix.js('resources/assets/js/modules/main.js', `public/js/app.min.js`);

// mix.sass('resources/assets/sass/app.scss', 'public/css').version()

mix.copy('resources/assets/js', 'public/js', false)
    .copy('resources/assets/css', 'public/css', false)

// mix.webpackConfig(require('./webpack.config'))
//     .disableNotifications()
