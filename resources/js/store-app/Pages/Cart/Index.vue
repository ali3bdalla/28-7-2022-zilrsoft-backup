<template>
  <web-layout>
    <section
        class="shopping-cart spad cart"
        style="padding-top: 0px !important"
    >
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <ShowEmptyCartComponent v-if="!showCart"></ShowEmptyCartComponent>
            <CartItems
                v-if="activePage === 'cart' && showCart"
                :cartItemsList="cartItemsList"
            />
          </div>
          <div
              v-if="$page.props.client && activePage === 'checkout' && showCart"
              class="col-12">
            <div class="w-full">
              <cart-shipping-address
                  :shippingAddressId="shippingAddressId"
                  @updateShippingId="updateShippingId"
              />
            </div>
          </div>
          <div v-if="showCart">
            <div v-if="activePage === 'cart'" class="page__mt-5 col-lg-12">
              <div class="my-4">
                <el-select v-model="cityId" :default-first-option="true" :filterable="true"
                           no-data-text="No"
                           no-match-text="No Data"
                           :placeholder="selectedCityName"
                           value-key="id"
                           @change="loadShippingMethods">
                  <el-option
                      v-for="city in $page.props.cities"
                      :key="city.id"
                      :label="city.locale_name"
                      :value="city">
                    {{ city.locale_name }}
                  </el-option>
                </el-select>
              </div>
              <h1 class="cart__shipping-method-title">
                {{ $page.props.$t.cart.shipping_method }}
              </h1>
              <div class="cart__shipping-method-list">
                <div
                    v-for="shippingMethod in shippingMethods"
                    :key="shippingMethod.id"
                    class="cart__shipping-method-list-item bg-white"
                >
                  <el-image
                      :src="shippingMethod.logo"
                      class="cart__shipping-method-list-item-image object-contain"
                  ></el-image>
                  <el-radio
                      v-model="shippingMethodId"
                      :label="shippingMethod.id"
                      @change="shippingMethodSelected"
                  >{{ shippingMethod.locale_name }}
                  </el-radio>
                </div>
              </div>
            </div>
          </div>
          <div class="cart__checkout">
            <div class="proceed-checkout">
              <CartActions
                  v-if="cartItemsList.length"
                  :active-page="activePage"
                  :cart-items-list="cartItemsList"
                  :getShippingMethod="getShippingMethod"
                  :shipping-address-id="shippingAddressId"
                  :shipping-method-id="shippingMethodId"
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
import WebLayout from '../../Layouts/WebAppLayout'
import CartItems from './CartItems'
import CartActions from './CartActions'
import CartShippingAddress from './CartShippingAddress.vue'
import ShowEmptyCartComponent from '../../Components/Cart/ShowEmptyCartComponent.vue'

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
    selectedCityName () {
      if(this.cityId)
        return this.cityId.locale_name
      return  this.$page.props.$t.messages.select_city;
    },
    showCart () {
      return this.$store.state.cart.length
    },
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
    const storedCityObject = localStorage.getItem('cart_shipping_city_id', null)
    this.shippingMethodId = parseInt(localStorage.getItem('cart_shipping_method_id', 0))
    if (storedCityObject) {
      this.cityId = JSON.parse(storedCityObject)
      this.loadShippingMethods(this.cityId)
    }
  },
  methods: {
    shippingMethodSelected (e) {
      localStorage.setItem('cart_shipping_method_id', e)
    },
    getShippingMethod () {
      const shippingMethod = this.shippingMethods.find(
          (p) => p.id === this.shippingMethodId
      )
      return shippingMethod || {}
    },
    loadShippingMethods (e) {
      localStorage.setItem('cart_shipping_city_id', JSON.stringify(e))
      let id = e.id ?? e
      axios.get(`/api/web/shipping_methods?city_id=${id}`).then(res => {
        this.shippingMethods = res.data
      })
    },
    updateShippingId (e) {
      this.shippingAddress = e
      this.shippingAddressId = e.id
    },
    updateCartItemsList (e) {
      this.cartItemsList = e.items
    },
    changeActivePage (e) {
      this.activePage = e.page
    },
    issueOrder () {
      this.$confirm('', this.$page.props.$t.messages.are_you_sure, 'success', {
        confirmButtonText: this.$page.props.$t.messages.yes,
        cancelButtonText: this.$page.props.$t.messages.no
      }).then(() => {
        this.$loading.show({ delay: 0 })
        axios.post('/api/web/orders',
            {
              shipping_address_id: this.shippingAddressId,
              shipping_method_id: this.shippingMethodId,
              payment_method_id: this.paymentMethodId,
              items: this.cartItemsList,
            }
        ).then(res => {
          Promise.all([
            this.clearCart(),
            this.alertUser(res)
          ])
        }).catch(error => {
        }).finally(() => {
          this.$loading.hide()
        })
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

    alertUser (res) {
      const number = `${res.data.user.phone_number}`.replace(
          '+',
          ''
      )
      return new Promise((resolve, reject) => {
        this.$alert(
            `${this.$page.props.$t.order.instructions_for_payment} ${number}`,
            this.$page.props.$t.order.thanks_for_order,
            'success',
            {
              confirmButtonText: this.$page.props.$t.messages.yes,
              cancelButtonText: this.$page.props.$t.messages.no
            }
        ).then(() => {
          location.href = '/web/profile'
        })
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
