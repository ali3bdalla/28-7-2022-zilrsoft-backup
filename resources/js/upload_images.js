const Vue = require('vue');
import VueSimpleAlert from "vue-simple-alert";

Vue.component('products-list-for-uploading-images-component',require('./components/UploadImages/ProductsListComponent').default);
Vue.use(VueSimpleAlert)

// alert('hello')
// alert('hello')
// alert('hello')
const app = new Vue({
    el: '#app'
});



