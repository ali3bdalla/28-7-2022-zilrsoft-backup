window._ = require('lodash');

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {
}

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrf;
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

import kithelper from './helper/kithelper';


// window.exports = kithelper;



var validate = require("validate.js");
var autoload = {
    kithelper
};
var counting = require("./counting").counting;
var helpers = require("./helpers").helpers;
window.validate = validate;
window.autoload = autoload;
window.counting = counting;
window.helpers = helpers;

