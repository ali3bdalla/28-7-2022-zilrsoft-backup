<template>
  <web-layout>
    <section
      class="shopping-cart spad cart"
      style="padding-top: 0px !important"
    >
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <ShowEmptyCartComponent v-if="!this.$store.state.cart.length"></ShowEmptyCartComponent>
            <CartItems
              v-if="activePage === 'cart'"
              :cartItemsList="cartItemsList"
            />
          </div>
          <div
            v-if="$page.client && activePage === 'checkout'"
            class="col-12"
          >
            <div class="w-full">
              <cart-shipping-address
                :shippingAddressId="shippingAddressId"
                @updateShippingId="updateShippingId"
              />
            </div>
          </div>
          <div>
            <div class="page__mt-5 col-lg-12" v-if="activePage === 'cart'">
              <div class="my-4">
                <el-select @change="loadShippingMethods" v-model="cityId" :filterable="true" :placeholder=" $page.$t.messages.select_city"
                          class=""
                          no-data-text="No" no-match-text="No Data">
                <el-option
                    v-for="city in $page.cities"
                    :key="city.id"
                    :label="city.locale_name"
                    :value="city.id">
                  {{ city.locale_name }}
                </el-option>
              </el-select>
              </div>
              <h1 class="cart__shipping-method-title">
                {{ $page.$t.cart.shipping_method }}
              </h1>
              <div class="cart__shipping-method-list">
                <div
                  class="cart__shipping-method-list-item bg-white"
                  v-for="shippingMethod in shippingMethods"
                  :key="shippingMethod.id"
                >
                  <el-image
                    :src="shippingMethod.logo"
                    class="cart__shipping-method-list-item-image object-contain"
                  ></el-image>
                  <el-radio
                    v-model="shippingMethodId"
                    :label="shippingMethod.id"
                    >{{ shippingMethod.locale_name }}
                  </el-radio>
                </div>
              </div>
            </div>
          </div>
          <div class="cart__checkout">
            <div class="proceed-checkout">
              <CartActions
               :getShippingMethod="getShippingMethod"
                v-if="cartItemsList.length"
                :shipping-method-id="shippingMethodId"
                :shipping-address-id="shippingAddressId"
                :active-page="activePage"
                :cart-items-list="cartItemsList"
                @changeActivePage="changeActivePage"
                @issueOrder="issueOrder"
              />
            </div>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import WebLayout from '../../../Layouts/WebAppLayout'
import CartItems from './CartItems'
import CartActions from './CartActions'
import CartShippingAddress from './CartShippingAddress.vue'
import ShowEmptyCartComponent from './../../../components/Web/Cart/ShowEmptyCartComponent.vue'

export default {
  name: 'Index',
  data () {
    return {
      activePage: 'cart',
      shippingMethodId: 0,
      paymentMethodId: 'bank_transfer',
      shippingAddress: null,
      shippingAddressMethods: [],
      shippingAddressId: 0,
      cityId: '',
      shippingMethods: []
    }
  },
  components: {
    ShowEmptyCartComponent,
    CartActions,
    CartItems,
    WebLayout,
    CartShippingAddress
  },
  computed: {
    cartItemsList () {
      return this.$store.state.cart
    },
    paymentMethods () {
      return [
        {
          name: 'bank_transfer',
          logo: '/images/shipping_methods/bank_transfer.jpeg',
          active: true,
          selected: true
        },

        {
          name: 'mada',
          logo: '/images/shipping_methods/mada.jpg',
          active: false
        },
        {
          name: 'visa',
          logo: '/images/shipping_methods/visa.jpg',
          active: false
        },
        {
          name: 'sdad',
          logo: '/images/shipping_methods/sdad.jpg',
          active: false
        }
      ]
    }
  },
  created () {
    this.cityId = localStorage.getItem('cart_shipping_city_id', null)
    if (this.cityId) {
      this.loadShippingMethods(this.cityId)
    }
  },
  methods: {
    getShippingMethod () {
      const shippingMethod = this.shippingMethods.find(
        (p) => p.id === this.shippingMethodId
      )
      return shippingMethod || {}
    },
    loadShippingMethods (e) {
      localStorage.setItem('cart_shipping_city_id', e)
      axios.get(`/api/web/shipping_methods?city_id=${e}`).then(res => {
        this.shippingMethods = res.data
      })
    },
    updateShippingId (e) {
      this.shippingAddress = e
      this.shippingAddressId = e.id
    },
    updatecartItemsList (e) {
      this.cartItemsList = e.items
    },
    changeActivePage (e) {
      this.activePage = e.page
    },
    issueOrder () {
      this.$confirm('', this.$page.$t.messages.are_you_sure, 'success', {
        confirmButtonText: this.$page.$t.messages.yes,
        cancelButtonText: this.$page.$t.messages.no
      }).then(() => {
        this.$loading.show({ delay: 0 })
        this.$inertia.post(
          '/api/web/orders',
          {
            shipping_address_id: this.shippingAddressId,
            shipping_method_id: this.shippingMethodId,
            payment_method_id: this.paymentMethodId,
            items: this.cartItemsList
          },
          {
            replace: true,
            preserveState: (page) => Object.keys(page.props.errors).length,
            preserveScroll: (page) => Object.keys(page.props.errors).length,
            onSuccess: () => {
              return Promise.all([
                this.clearCart(),
                this.alertUser()
              ])
            },
            onFinish: () => {
              this.$loading.hide()
            }
          }
        )
      })
    },
    clearCart () {
      return new Promise((resolve) => {
        this.$store.state.cart.forEach((item) => {
          this.$store.commit('removeFromCart', item)
        })
        resolve()
      })
    },

    alertUser () {
      const number = `${this.$page.client.international_phone_number}`.replace(
        '+',
        ''
      )

      return new Promise((resolve, reject) => {
        this.$alert(
          `${this.$page.$t.order.instructions_for_payment} ${number}`,
          this.$page.$t.order.thanks_for_order,
          'success',
          {
            confirmButtonText: this.$page.$t.messages.yes,
            cancelButtonText: this.$page.$t.messages.no
          }
        ).then(() => {})
        resolve()
      })
    }
  }
}
</script>

<style>
.el-radio__inner {
  border: 1px solid #888 !important;
}

.el-radio__label {
  margin: 0px 5px;
}
.el-image__inner {
  object-fit: contain;
}
</style>
