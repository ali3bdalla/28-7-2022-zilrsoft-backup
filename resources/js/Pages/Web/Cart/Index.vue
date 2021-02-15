<template>
  <web-layout>
    <section
      class="shopping-cart spad cart"
      style="padding-top: 0px !important"
    >
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <cart-empty v-if="!this.$store.state.cart.length"></cart-empty>
            <CartItems
              :active-page="activePage"
              @orderItems="updateOrderItems"
            />
          </div>
          <div v-if="$page.client" class="col-12">
            <div class="w-full">
              <cart-shipping-address
                v-if="activePage === 'checkout'"
                :shippingAddressId="shippingAddressId"
                @updateShippingId="updateShippingId"
              />
            </div>
          </div>
          <div v-if="activePage == 'checkout' && shippingAddress">
            <div class="page__mt-5 col-lg-12  ">
                <h1 class="cart__shipping-method-title">
                  {{ $page.$t.cart.shipping_method }}
                </h1>
                <div class="cart__shipping-method-list">
                  <div
                    class="cart__shipping-method-list-item bg-white"
                    v-for="shippingMethod in $page.shippingMethods"
                    :key="shippingMethod.id"
                    v-if="!disableShippingMethod(shippingMethod)"
                  >
                    <el-image
                      :class="{
                        'cart__shipping-method-list-item-image__hidden': disableShippingMethod(
                          shippingMethod
                        ),
                      }"
                      :src="shippingMethod.logo"
                      class="cart__shipping-method-list-item-image object-contain"
                    ></el-image>
                    <el-radio
                      v-model="shippingMethodId"
                      :label="shippingMethod.id"
                      :disabled="disableShippingMethod(shippingMethod)"
                      >{{ shippingMethod.locale_name }}
                    </el-radio>
                  </div>
                </div>
              </div>

            <!-- <div class="page__mt-5 col-lg-12">
                <h1 class="page__mt-2 home__products-count">
                  {{ $page.$t.cart.payment_method }}
                </h1>
                <div class="cart__shipping-method-list">
                  <div
                    class="cart__shipping-method-list-item"
                    v-for="paymentMethod in paymentMethods"
                    :key="paymentMethod.name"
                  >
                    <el-image
                      :class="{
                        'cart__shipping-method-list-item-image__hidden': !paymentMethod.active,
                      }"
                      :src="paymentMethod.logo"
                      class="cart__shipping-method-list-item-image"
                    ></el-image>
                    <el-radio
                      :selected="paymentMethod.active"
                      v-model="paymentMethodId"
                      :label="paymentMethod.name"
                      :disabled="!paymentMethod.active"
                    >
                      {{ $page.$t.cart[paymentMethod.name] }}
                    </el-radio>
                  </div>
                </div>
              </div> -->
          </div>
          <div class="cart__checkout">
            <div class="proceed-checkout">
              <CartButton
                v-if="orderItems.length"
                :shipping-method-id="shippingMethodId"
                :active-page="activePage"
                :order-items="orderItems"
                @changeActivePage="changeActivePage"
                @sendOrder="sendOrder"
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
import CartButton from './CartButton'
import CartShippingAddress from './CartShippingAddress.vue'
import CartEmpty from './CartEmpty'

export default {
  name: 'Index',
  data () {
    return {
      activePage: 'cart',
      orderItems: [],
      shippingMethodId: 0,
      paymentMethodId: 'bank_transfer',
      shippingAddress: null,
      shippingAddressMethods: [],
      shippingAddressId: 0
    }
  },
  components: {
    CartEmpty,
    CartButton,
    CartItems,
    WebLayout,
    CartShippingAddress
  },
  computed: {
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
  methods: {
    disableShippingMethod (shippingMethod) {
      if (this.shippingAddress) {
        return !shippingMethod.cities_ids.includes(
          parseInt(this.shippingAddress.city_id)
        )
      }
      return true
    },
    updateShippingId (e) {
      this.shippingAddress = e
      this.shippingAddressId = e.id
    },
    updateOrderItems (e) {
      this.orderItems = e.items
    },
    changeActivePage (e) {
      this.activePage = e.page
    },
    sendOrder () {
      const items = this.orderItems

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
            items: items
          },
          {
            replace: true,
            preserveState: (page) => Object.keys(page.props.errors).length,
            preserveScroll: (page) => Object.keys(page.props.errors).length,
            onSuccess: () => {
              return Promise.all([
                this.removeCartItems(items),
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
    removeCartItems (items) {
      return new Promise((resolve) => {
        this.$store.state.cart.forEach((item) => {
          const isOrdered = items.find((p) => p.id === item.id)
          if (isOrdered) {
            this.$store.commit('removeFromCart', item)
          }
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
