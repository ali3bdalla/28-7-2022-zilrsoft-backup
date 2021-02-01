export default {
  props: {
    activePage: {
      type: String,
      required: true
    }
  },

  methods: {
    getOrderTotalAmount (orderItems = null) {
      let amount = 0
      const items = orderItems || this.grabOrderItems()
      for (let index = 0; index < items.length; index++) {
        amount += parseFloat(this.getProductTotal(items[index]))
      }
      return amount
    },

    getProductTotal (item) {
      const total = parseFloat(item.online_offer_price) * parseInt(item.quantity)
      return total.toFixed(2)
    }
  }
}
