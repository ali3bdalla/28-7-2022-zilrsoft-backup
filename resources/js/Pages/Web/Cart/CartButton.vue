<template>
  <div class="cart__totals-buttons">
    <div>
      <div class="cart__total-amount">
        <span
        >{{ $page.$t.cart.total }} (<span class="inc_tax">{{
            $page.$t.cart.inc_vat
          }} 15%</span
        >) :
        </span>
        <display-money :money="getOrderTotalAmount(orderItems)"></display-money>
      </div>
      <div v-show="activePage === 'checkout' && shippingMethodId">
        <div class="cart__total-amount">
          <span>{{ $page.$t.cart.shipping_weight }} : </span>
          <display-money :money="getTotalShippingWeight()"></display-money>
        </div>
        <div class="cart__total-amount">
          <span>{{ $page.$t.cart.shipping_total }} : </span>

          <display-money
              v-if="getTotalShippingSubtotal() > 0"
              :money="getTotalShippingSubtotal()"
          ></display-money>
          <span v-else>{{ $page.$t.cart.free }}</span>
        </div>

        <div class="cart__total-amount">
          <span>{{ $page.$t.cart.net }}: </span>

          <display-money :money="getOrderNetAmount()"></display-money>
        </div>
      </div>
    </div>
    <div v-if="activePage === 'cart'">
      <button v-if="$page.client_logged" class="proceed-btn" @click="changeActivePage('checkout')">
        {{ $page.$t.cart.checkout }} ({{ orderItems.length }})
      </button>

      <a v-else class="proceed-btn" href="/web/sign_in">{{
        $page.$t.cart.login_to_checkout
        }}</a>
    </div>

    <div v-else>
      <button v-if="$page.client_logged" class="proceed-btn" @click="sendOrder">
        {{ $page.$t.cart.confirm_order }}
      </button>
      <div v-if="$page.client_logged" class="text-center flex items-center justify-center  border-t mt-3">
        <button class="text-center flex items-center justify-center mt-2 text-gray-900 text-sm"
                @click="changeActivePage('cart')">
          {{ $page.$t.cart.back_to_cart }}
        </button>
      </div>
      <a v-else class="proceed-btn" href="/web/sign_in">{{
        $page.$t.cart.login_to_checkout
        }}</a>
    </div>
  </div>
</template>

<script>
import CartMixin from './CartMixin'
import DisplayMoney from '../../../components/BackEnd/Money/DisplayMoney'

export default {
  components: { DisplayMoney },
  mixins: [CartMixin],
  name: 'CartButton',
  props: {
    orderItems: {
      type: Array,
      required: true
    },
    shippingMethodId: {
      required: true,
      type: Number
    }
  },
  data () {
    return {
      shippingMethod: {},
      shippingTotal: 0,
      shippingWeight: 0,
      shippingDiscount: 0,
      shippingSubtotal: 0
    }
  },
  methods: {
    changeActivePage (page) {
      this.$emit('changeActivePage', { page: page })
    },

    sendOrder () {
      this.$emit('sendOrder')
    },

    getTotalShippingWeight () {
      let totalWeight = 0
      const items = this.orderItems
      for (let index = 0; index < items.length; index++) {
        if (items[index].weight) {
          totalWeight += parseFloat(items[index].weight).toFixed(2) * parseInt(items[index].quantity)
        }
      }
      return totalWeight
    },

    getShippingDiscount () {
      let discount = 0
      const items = this.orderItems
      const totalShippingAmount = parseFloat(this.getTotalShippingAmount())
      for (let index = 0; index < items.length; index++) {
        discount +=
            parseFloat(items[index].shipping_discount) *
            parseInt(items[index].quantity)
      }

      if (totalShippingAmount < discount) {
        discount = totalShippingAmount
      }

      return discount
    },

    getOrderNetAmount () {
      return (
          parseFloat(this.getTotalShippingSubtotal()) +
          parseFloat(this.getOrderTotalAmount(this.orderItems))
      )
    },

    getShippingMethod () {
      const shippingMethod = this.$page.shippingMethods.find(
          (p) => p.id == this.shippingMethodId
      )
      return shippingMethod || {}
    },

    getTotalShippingSubtotal () {
      return (
          parseFloat(this.getTotalShippingAmount()) -
          parseFloat(this.getShippingDiscount())
      )
    },
    getTotalShippingAmount () {
      const totalWeight = parseFloat(this.getTotalShippingWeight()).toFixed(2) // 39
      if (totalWeight === 0) return 0
      const maxBaseWeight = parseFloat(this.getShippingMethod().max_base_weight)
      let shippingAmount = parseFloat(
          this.getShippingMethod().max_base_weight_price
      )

      if (maxBaseWeight < totalWeight) {
        const weightVaritionToBase = totalWeight - maxBaseWeight
        shippingAmount +=
            weightVaritionToBase *
            parseFloat(this.getShippingMethod().kg_rate_after_max_price)
      }

      return shippingAmount
    }
  }
}
</script>
