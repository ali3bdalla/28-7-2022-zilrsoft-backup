import Vue from 'vue';
import VueSimpleAlert from "vue-simple-alert";

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


const ConfirmOrderDeliveryStatus = require('./delivery/ConfirmOrderDeliveryStatus');

Vue.use(VueSimpleAlert);


Vue.component('confirm-order-delivery-status', ConfirmOrderDeliveryStatus)

const app = new Vue({
    el: '#app'
});
