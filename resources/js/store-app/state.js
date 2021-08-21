const Vue = require('vue')

const cart = window.localStorage.getItem('cart')
const cartCount = window.localStorage.getItem('cartCount')
module.exports = {
  state: {
    cart: cart ? JSON.parse(cart) : [],
    cartCount: cartCount ? parseInt(cartCount) : 0
  },

  mutations: {
    addToCart (state, payload) {
      const quantity = parseInt(payload.quantity)
      const item = payload.item
      const found = state.cart.find(product => product.id === item.id)
      if (found) {
        const newQuantity = parseInt(quantity)
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
      const product = state.cart.find(product => product.id == item.id)

      const index = state.cart.indexOf(product)

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
    updateItemCartAvailableQty (state, payload) {
      const found = state.cart.find(product => product.id === payload.item.id)
      if (found) {
        found.available_qty = parseInt(payload.available_qty)
      }
      this.commit('saveCart')
    }
  }
}
