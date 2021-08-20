import { InertiaApp } from '@inertiajs/inertia-vue'
import Vue from 'vue'
import Vuex from 'vuex'
import VueLogger from 'vuejs-logger'

import VueSimpleAlert from 'vue-simple-alert'
import vClickOutside from 'v-click-outside'
import Dialog from 'vue-dialog-loading'
const isProduction = process.env.NODE_ENV === 'production'
window.axios = require('axios')
window._ = require('lodash')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrf
  console.error(
    'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
  )
}

Vue.use(VueLogger, {
  isEnabled: true,
  logLevel: isProduction ? 'error' : 'info',
  stringifyArguments: false,
  showLogLevel: true,
  showMethodName: true,
  separator: '|',
  showConsoleColors: true
})
require('./Plugins/plugins')
require('./upload_images')
Vue.use(Vuex)
Vue.use(VueSimpleAlert)
Vue.use(Dialog, {
  dialogBtnColor: '#0f0',
  background: 'rgba(0, 0, 0, 0.5)'
})
Vue.use(vClickOutside)

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
