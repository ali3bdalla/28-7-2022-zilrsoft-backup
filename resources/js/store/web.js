const Vue = require('vue')

let cart = window.localStorage.getItem('cart')
let cartCount = window.localStorage.getItem('cartCount')

module.exports = {
  state: {
    cart: cart ? JSON.parse(cart) : [],
    cartCount: cartCount ? parseInt(cartCount) : 0
  },

  mutations: {
    addToCart (state, payload) {
      let quantity = parseInt(payload.quantity)
      let item = payload.item
      let found = state.cart.find(product => product.id === item.id)
      if (found) {
        let newQuantity = parseInt(quantity)
        if (parseInt(newQuantity) >= 0) {
          found.quantity = parseInt(newQuantity)
        }
        found.totalPrice = found.quantity * found.price
      } else {
        state.cart.push(item)
        Vue.set(item, 'quantity', parseInt(quantity))
        Vue.set(item, 'totalPrice', item.price)
        state.cartCount++
      }

      this.commit('saveCart')
    },
    removeFromCart (state, item) {
      let product = state.cart.find(product => product.id == item.id)

      let index = state.cart.indexOf(product)

      if (index > -1) {
        state.cartCount -= 1
        state.cart.splice(index, 1)
      }
      this.commit('saveCart')
    },
    saveCart (state) {
      window.localStorage.setItem('cart', JSON.stringify(state.cart))
      window.localStorage.setItem('cartCount', state.cartCount)
    },
    updateItemCartAvailableQty (state,payload) {

      let found = state.cart.find(product => product.id === payload.item.id)
      if (found) {
        found.available_qty = parseInt(payload.available_qty)
      }
      this.commit('saveCart')
    }
  }
}
