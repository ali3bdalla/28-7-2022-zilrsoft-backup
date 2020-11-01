import {InertiaApp} from '@inertiajs/inertia-vue'
import Vue from 'vue'
import Vuex from 'vuex'
import VueSimpleAlert from "vue-simple-alert";


window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
let token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrf
    console.error(
        'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
    )
}

String.prototype.replaceAt = function (index, replacement) {
    return (
        this.substr(0, index) +
        replacement +
        this.substr(index + replacement.length)
    )
}

require('./upload_images');


Vue.use(Vuex)
Vue.use(VueSimpleAlert);

const store = new Vuex.Store(require('./store/web'))
Vue.use(InertiaApp)

const app = document.getElementById('app')

new Vue({
    store: store,
    render: h =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name => require(`./Pages/${name}`).default
            }
        })
}).$mount(app)
