import Vue from 'vue'

import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import CxltToastr from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'
import VuejsDialog from 'vuejs-dialog'
import 'vuejs-dialog/dist/vuejs-dialog.min.css'
import VModal from 'vue-js-modal'
import VueSimpleAlert from 'vue-simple-alert'

require('./bootstrap')
window.TextValidator = require('validator')
window.getRequestUrl = function (path) {
  return '/api/web/' + path + '?lang=ar'
}
window.getIndex = function (needle, haystack) {
  const length = haystack.length
  for (let i = 0; i < length; i++) {
    if (haystack[i] === needle) return i
  }
  return -1 // update latter
}
window.Vue = Vue

require('./Plugins/plugins')
require('./accounting/load')
// Vue.use(ElementUI);
Vue.use(VModal)
Vue.use(VuejsDialog)
Vue.use(VueSimpleAlert)

Vue.use(CxltToastr, {
  position: 'top right',
  showDuration: 2000
})

Vue.prototype.$page = {
  errors: []
}
Vue.use(Loading)

Vue.use(VueSimpleAlert)

Vue.component('layouts-header-component', require('./components/layoutsHeaderComponent').default)
Vue.component('pending-purchases-counter-component', require('./components/pendingPurchasesCounterComponent').default)
require('./components/include')
window.routes = require('./routes')

const app = new Vue({
  el: '#app'
})
