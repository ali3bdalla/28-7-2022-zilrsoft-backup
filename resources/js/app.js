import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import ToggleButton from 'vue-js-toggle-button'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import CxltToastr from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'
window.TextValidator =  require('validator');
// import 'bulma/css/bulma.css'
// require('bootstrap');
require('./bootstrap');
import VModal from 'vue-js-modal'

window.Vue = Vue;

require('./accounting/load');
Vue.use(Vuetify);
Vue.use(VModal);
const opts = {};
export default new Vuetify({
    icons: {
        iconfont: "md"
    },
    theme: { dark: true }
})

Vue.use(CxltToastr, {
    position: 'top right',
    showDuration: 2000
});
Vue.use(Loading);
Vue.use(ToggleButton);


const app = new Vue({
    el: '#app',
});
