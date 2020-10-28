import {InertiaApp} from '@inertiajs/inertia-vue'
import Vue from 'vue'
import Vuex from 'vuex'


window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrf;
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


String.prototype.replaceAt = function(index, replacement) {
    return this.substr(0, index) + replacement + this.substr(index + replacement.length);
}


Vue.use(Vuex)

let cart = window.localStorage.getItem('cart');
let cartCount = window.localStorage.getItem('cartCount');

const store = new Vuex.Store({
    state: {
        cart: cart ? JSON.parse(cart) : [],
        cartCount: cartCount ? parseInt(cartCount) : 0,
    },

    mutations: {
        addToCart: function (state, item, quantity = 1, type = 'inc') {
            console.log(item,type,quantity)

            let found = state.cart.find(product => product.id === item.id);
            if (found) {

                if (type == 'inc') {
                    found.quantity += parseInt(quantity);
                } else if (type == 'set') {
                    let newQuantity = parseInt(quantity);
                    if (parseInt(newQuantity) >= 0) {
                        found.quantity = parseInt(newQuantity);
                    }
                } else {
                    let newQuantity = found.quantity - parseInt(quantity);
                    if (parseInt(newQuantity) >= 0) {
                        found.quantity = parseInt(newQuantity);
                    }
                }
                found.totalPrice = found.quantity * found.price;
            } else {
                state.cart.push(item);
                Vue.set(item, 'quantity', parseInt(quantity));
                Vue.set(item, 'totalPrice', item.price);
                state.cartCount++;

            }

            this.commit('saveCart');

        },
        removeFromCart(state, item) {
            let index = state.cart.indexOf(item);
            if (index > -1) {
                let product = state.cart[index];
                state.cartCount -= product.quantity;

                state.cart.splice(index, 1);
            }
            this.commit('saveCart');

        },
        saveCart(state) {
            window.localStorage.setItem('cart', JSON.stringify(state.cart));
            window.localStorage.setItem('cartCount', state.cartCount);
        },
        updateItemCartAvailableQty(state, item, available_qty = 0) {
            let found = state.cart.find(product => product.id === item.id);
            if (found) {
                found.available_qty = parseInt(available_qty);
            }
            this.commit('saveCart');
        }
    }
});

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
        }),

}).$mount(app)
