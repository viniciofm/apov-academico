try {
    window.$ = window.jQuery = require('jquery');
    window.Popper = require('popper.js').default; // default is very important.
    require('bootstrap');
} catch (e) {
    console.error(e);
}
