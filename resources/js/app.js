import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import CxltToastr from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'
import VuejsDialog from 'vuejs-dialog'
import 'vuejs-dialog/dist/vuejs-dialog.min.css'
import VModal from 'vue-js-modal'
import VueSimpleAlert from 'vue-simple-alert'
import 'element-ui/lib/theme-chalk/index.css'
import ToggleButton from 'vue-js-toggle-button'
import Vuex from 'vuex'
import VueState from './state'
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
  return -1
}
require('./accounting/load')
require('./store-app/func')
Vue.use(Vuetify)
Vue.use(ToggleButton)
Vue.use(VModal)
Vue.use(VuejsDialog)
Vue.use(VueSimpleAlert)

export default new Vuetify({
  icons: {
    iconfont: 'md'
  },
  theme: { dark: true }
})
Vue.use(Vuex)
Vue.use(CxltToastr, {
  position: 'top right',
  showDuration: 2000
})
Vue.use(Loading)
Vue.use(VueSimpleAlert)
require('./components/include')
new Vue({
  store: new Vuex.Store(VueState),
  el: '#app'
})
