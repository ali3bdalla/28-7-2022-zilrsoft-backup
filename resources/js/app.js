import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import ToggleButton from 'vue-js-toggle-button'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import CxltToastr from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'
require('./bootstrap');
window.TextValidator = require('validator');
import VuejsDialog from 'vuejs-dialog';
import 'vuejs-dialog/dist/vuejs-dialog.min.css';
import VModal from 'vue-js-modal'

window.getRequestUrl = function (path) {
    return '/api/web/' + path + "?lang=ar";
}

window.getIndex = function(needle, haystack)
{
    let length = haystack.length;
    for(let i = 0; i < length; i++) {
        if(haystack[i] === needle) return i;
    }
    return  -1;
}
window.Vue = Vue;
require('./accounting/load');
Vue.use(Vuetify);
Vue.use(VModal);
Vue.use(VuejsDialog);

export default new Vuetify({
    icons: {
        iconfont: "md"
    },
    theme: {dark: true}
})

Vue.use(CxltToastr, {
    position: 'top right',
    showDuration: 2000
});
Vue.use(Loading);
Vue.use(ToggleButton);

Vue.component('layouts-header-component', require('./components/layoutsHeaderComponent').default)
Vue.component('pending-purchases-counter-component', require('./components/pendingPurchasesCounterComponent').default)

window.routes = require('./routes')


const app = new Vue({
    el: '#app'
});
