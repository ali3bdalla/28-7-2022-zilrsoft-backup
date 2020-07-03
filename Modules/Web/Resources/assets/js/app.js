import Vue from 'vue';
window.axios = require('axios');
import VueSpinners from 'vue-spinners'

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrf;
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


require('./components');
window.getRequestUrl = function (path) {
    return '/api/web/' + path;
}

window.getIndex = function(needle, haystack)
{
    let length = haystack.length;
    for(let i = 0; i < length; i++) {
        if(haystack[i] === needle) return i;
    }
    return  -1;
}
window.inArray = function(needle, haystack) {
    if(haystack === undefined)
        return  false;
    let index = getIndex(needle, haystack);
    return index !== -1;
}




Vue.use(VueSpinners);
const app = new Vue({
    el: '#app',
});
