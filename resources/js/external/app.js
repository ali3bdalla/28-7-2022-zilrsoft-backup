import Vue from 'vue'
import VueSimpleAlert from 'vue-simple-alert'

window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const ConfirmTransactionDelivered = require('./delivery/ConfirmTransctionDelivered')

Vue.use(VueSimpleAlert)

Vue.component('confirm-transaction-delivered', ConfirmTransactionDelivered.default)

const app = new Vue({
  el: '#app'
})
