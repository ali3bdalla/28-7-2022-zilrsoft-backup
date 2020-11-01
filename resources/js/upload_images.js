const Vue = require('vue');

Vue.component('products-list-for-uploading-images-component',require('./components/UploadImages/ProductsListComponent').default);


const app = new Vue({
    el: '#app'
});



