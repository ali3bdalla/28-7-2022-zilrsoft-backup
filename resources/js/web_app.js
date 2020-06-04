import Vue from 'vue';

require('./web_bootstrap');
window.Vue = Vue;



const app = new Vue({
    el: '#app',
});
