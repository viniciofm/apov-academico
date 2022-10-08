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

const CSS_PATH = 'public/dist/css';
const JS_PATH = 'public/dist/js';

mix.copy('resources/assets/js/app.js', `public/js`);
mix.copy('resources/js', `public/js`);
mix.js('resources/assets/js/modules/empresa/main.js', `${JS_PATH}/empresa`).vue();
mix.js('resources/assets/js/modules/instituicao/main.js', `${JS_PATH}/instituicao`).vue();
mix.js('resources/assets/js/modules/professor/main.js', `${JS_PATH}/professor`).vue();
mix.js('resources/assets/js/modules/curso/main.js', `${JS_PATH}/curso`).vue();
mix.js('resources/assets/js/modules/turma/main.js', `${JS_PATH}/turma`).vue();
mix.js('resources/assets/js/modules/matricula/main.js', `${JS_PATH}/matricula`).vue();
mix.js('resources/assets/js/modules/aluno/main.js', `${JS_PATH}/aluno`).vue();
mix.js('resources/assets/js/modules/usuario/main.js', `${JS_PATH}/usuario`).vue();

mix.sass('resources/sass/app.scss', `${CSS_PATH}`);

mix.disableNotifications()
