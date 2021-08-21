<template>
  <div class="cart__totals-buttons">
    <div class="cart__total-amount">
      <span>
        {{ $page.$t.cart.total }} (
        <span class="inc_tax">
          {{
          $page.$t.cart.inc_vat
          }} 15%
        </span>)
      </span>
      <div>
        <RenderMoneyComponent :money="getOrderTotalAmount()"></RenderMoneyComponent>
        {{ $page.$t.products.sar }}
      </div>
    </div>
    <div class="cart__total-amount">
      <span>{{ $page.$t.cart.shipping_weight }}</span>
      <div>
        <RenderMoneyComponent :money="shippingWeight"></RenderMoneyComponent>
        {{ $page.$t.products.kg }}
      </div>
    </div>
    <div v-if="shippingMethodId">
      <div class="cart__total-amount">
        <span>{{ $page.$t.cart.shipping_total }}</span>

        <div v-if="shippingSubtotal > 0">
          <RenderMoneyComponent :money="shippingSubtotal"></RenderMoneyComponent>
        </div>
        <span v-else>{{ $page.$t.cart.free }}</span>
      </div>
      <div class="cart__total-amount">
        <span>{{ $page.$t.cart.net }}</span>
        <div>
          <RenderMoneyComponent :money="orderNetAmount"></RenderMoneyComponent>
          {{ $page.$t.products.sar }}
        </div>
      </div>
      <div v-if="activePage === 'cart'">
        <button
          v-if="$page.client_logged"
          class="proceed-btn"
          @click="changeActivePage('checkout')"
        >{{ $page.$t.cart.checkout }} ({{ cartItemsList.length }})
        </button>
        <button v-else class="proceed-btn" @click="loginToCheckout">
          {{ $page.$t.cart.login_to_checkout }}
        </button>
      </div>
      <div  v-if="activePage === 'checkout'">
        <div class="py-3 my-1">
          <input type="checkbox" v-model="acceptTermsAndPrivacyPolicy"/>
          {{$page.$t.cart.i_agree_to }} <a href="/web/content/terms" class="text-blue-500">{{$page.$t.cart.terms_and_conditions}}</a>
        </div>
        <button
          :disabled="!shippingAddressId || !acceptTermsAndPrivacyPolicy"
          class="confirmButton proceed-btn bg-gray-500"
          @click="issueOrder"
        >
          {{ $page.$t.cart.confirm_order }}
        </button>
        <button
            class="text-center flex items-center justify-center mt-2 text-gray-900 text-sm w-full"
            @click="changeActivePage('cart')"
          >
          {{ $page.$t.common.back }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import RenderMoneyComponent from '../../Components/Utility/RenderMoneyComponent.vue'
import { getOrderTotal, getOrderNet } from '../../../repository/orders'
import {
  getProductsWeight,
  getShippingSubtotal
} from '../../../repository/shipping'

export default {
  components: { RenderMoneyComponent },
  name: 'CartActions',
  props: {
    cartItemsList: {
      type: Array,
      required: true
    },
    getShippingMethod: {
      type: Function,
      required: true
    },
    shippingMethodId: {
      required: true,
      type: Number
    },
    shippingAddressId: {
      required: true,
      type: Number
    },
    activePage: {
      required: true,
      type: String
    }
  },
  data () {
    return {
      acceptTermsAndPrivacyPolicy: true
    }
  },
  computed: {
    shippingWeight () {
      return getProductsWeight(this.cartItemsList)
    },
    orderNetAmount () {
      return getOrderNet(this.getShippingMethod(), this.cartItemsList, {
        price: 'online_offer_price'
      })
    },
    shippingSubtotal () {
      return getShippingSubtotal(this.getShippingMethod(), this.cartItemsList)
    }
  },
  methods: {
    getOrderTotalAmount () {
      const items = this.cartItemsList
      return getOrderTotal(items, { price: 'online_offer_price' })
    },
    loginToCheckout () {
      location.href = '/web/cart/redirect'
    },
    changeActivePage (page) {
      this.$emit('changeActivePage', { page: page })
    },
    issueOrder () {
      this.$emit('issueOrder')
    }
  }
}
</script>

<style scoped>
.confirmButton:disabled {
  background-color: #d2d2d2 !important;
  color: gray;
}
</style>
