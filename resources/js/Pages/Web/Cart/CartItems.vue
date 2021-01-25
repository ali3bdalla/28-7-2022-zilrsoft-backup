<template>
  <div v-if="$store.state.cartCount >= 1">
    <div class="md:hidden">
      <div>
        <div class="container flex justify-center items-center md:hidden">
          <div class="mx-auto w-full">
            <div v-for="(item, index) in $store.state.cart" :key="index" class="bg-white">
              <div
                class="border mt-2 flex flex-col justify-center p-2 pb-0  rounded-lg shadow-sm"
                style="border-color: #d2e8ff !important"
            v-if="
              activePage == 'cart' ||
              (activePage === 'checkout' &&
                parseInt(item.available_qty) >= parseInt(item.quantity))
            "
                :class="[
              parseInt(item.available_qty) < parseInt(item.quantity)
                ? 'bg-red-200'
                : '',
            ]"

              >
                <div class="prod-title mx-auto">
                  <p class="uppercase text-center mt-2 text-gray-900 font-bold">
                    {{ getItemNamme(item) }}
                  </p>
                  <p class="uppercase text-xl text-center">
                    {{ item.online_offer_price }} {{ $page.$t.products.sar }}
                  </p>
                </div>
                <div class="prod-img border p-2 w-32 mx-auto">
                  <img
                    :src="item.item_image_url"
                    class="w-full object-cover object-center h-20"
                  />
                </div>
                <div class="prod-info grid mb-0 mt-1">
                  <div
                    class="mt-1 flex flex-col gap-2 justify-between items-center text-gray-900"
                  >
                    <el-input-number
                      :min="0"
                      style="text-align: center !important"
                      class="text-center"
                      size="small"
                      v-model="item.quantity"
                      @change="itemQtyUpdated(item)"
                      :step="1"
                      :disabled="activePage === 'checkout'"
                    ></el-input-number>
                    <div class="font-bold mt-2 text-xl">
                      {{ $page.$t.products.total }}: &nbsp;
                      {{ getProductTotal(item) }} {{ $page.$t.products.sar }}
                    </div>
                  </div>
                </div>

                <div
                  class="mt-3 w-full mx-auto flex justify-between items-center text-gray-900"
                >
                  <div>
                    <el-checkbox
                    class="bg-gray-200"
                    v-if="
                      parseInt(item.available_qty) >= parseInt(item.quantity)
                    "
                    :value="orderProducts.includes(item.id)"
                    @change="toggleOrderProduct(item)"
                    border
                    round
                  ></el-checkbox>
                  </div>

                  <el-button
                    v-if="activePage === 'cart'"
                    @click="removeCartItem(item)"
                    type="danger"
                    icon="el-icon-delete"
                    style="margin-left: 0px;font-size: 26px;
    padding: 5px 14px;"

                  ></el-button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="cart-table hidden md:block">
      <table>
        <thead>
          <tr>
            <th v-if="$page.client_logged && activePage === 'cart'">
              <input
                type="checkbox"
                class="cart__item-checkbox"
                v-model="select_all"
                @change="updateAll"
              />
            </th>
            <th></th>
            <th class="p-name">{{ $page.$t.products.name }}</th>
            <th>{{ $page.$t.products.price }}</th>
            <th>{{ $page.$t.products.quantity }}</th>
            <th>{{ $page.$t.products.total }}</th>
            <th v-if="$page.client_logged && activePage === 'cart'">
              <i v-if="$store.state.cartCount >= 1" class="ti-close"></i>
            </th>
          </tr>
        </thead>
        <tbody v-for="(item, index) in $store.state.cart" :key="index">
          <tr
            :class="[
              parseInt(item.available_qty) < parseInt(item.quantity)
                ? 'cart__table-raw__red'
                : '',
            ]"
            class="cart__table-raw"
            v-if="
              activePage == 'cart' ||
              (activePage === 'checkout' &&
                parseInt(item.available_qty) >= parseInt(item.quantity))
            "
          >
            <td
              v-if="$page.client_logged && activePage === 'cart'"
              class="cart__item-checkbox-container"
            >
              <input
                v-if="parseInt(item.available_qty) >= parseInt(item.quantity)"
                :checked="orderProducts.includes(item.id)"
                class="cart__item-checkbox"
                type="checkbox"
                @change="toggleOrderProduct(item)"
              />
            </td>
            <td class="text-center cart-pic first-row">
              <img class="cart__item-image" :src="item.item_image_url" />
            </td>
            <td class="cart-title first-row" style="font-size: 15px !important">
              {{ getItemNamme(item) }}
            </td>
            <td class="p-price first-row">
              {{ parseFloat(item.price).toFixed(2) }}
            </td>
            <td v-if="activePage === 'cart'" class="qua-col first-row">
              <div class="quantity">
                <div class="pro-qty">
                  <button
                    class="dec qtybtn"
                    @click="updateOrderProductQuantity(item, 'dec')"
                  >
                    -
                  </button>
                  <input
                    v-model="item.quantity"
                    type="text"
                    @change="itemQtyUpdated(item)"
                  />
                  <button
                    class="inc qtybtn"
                    @click="updateOrderProductQuantity(item, 'inc')"
                  >
                    +
                  </button>
                </div>
              </div>
            </td>
            <td v-else class="total-price first-row">{{ item.quantity }}</td>
            <td class="total-price first-row">{{ getProductTotal(item) }}</td>
            <td class="close-td first-row" v-if="activePage === 'cart'">
              <i class="fa fa-remove" @click="removeCartItem(item)"></i>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import CartMixin from './CartMixin'

export default {
  name: 'CartItems',
  mixins: [CartMixin],
  data () {
    return {
      select_all: false,
      shippingAddressMethod: 1,
      shippingAddressId: 0,
      cart: [],
      orderProducts: []
    }
  },
  created () {
    this.validateCart()
  },

  methods: {
    getItemNamme (item) {
      if (this.$page.active_locale === 'en') return item.name

      return item.ar_name
    },
    updateAll () {
      const products = this.orderProducts
      for (const item of this.$store.state.cart) {
        if (products.includes(item.id) && !this.select_all) {
          products.splice(this.orderProducts.indexOf(item.id), 1)
        }
        if (!products.includes(item.id) && this.select_all) {
          products.push(item.id)
        }
      }

      this.orderProducts = products
      this.emitUpdateEvent()
    },
    grabOrderItems () {
      const items = []
      const itemsId = []
      for (let index = 0; index < this.orderProducts.length; index++) {
        const element = this.orderProducts[index]
        const product = this.findProductById(element)
        if (
          product &&
          parseInt(product.available_qty) > 0 &&
          parseInt(product.available_qty) >= parseInt(product.quantity) &&
          !itemsId.includes(element)
        ) {
          items.push(product)
          itemsId.push(element)
        }
      }

      return items
    },

    setActivePage (activePage) {
      this.activePage = activePage
    },

    findProductById (id) {
      return this.$store.state.cart.find((p) => p.id === id)
    },
    findProduct (product) {
      return this.findProductById(product.id)
    },

    updateProduct (payload) {
      this.$store.commit('addToCart', payload)
      this.emitUpdateEvent()
    },

    updateOrderProductQuantity (item, type) {
      const product = this.findProduct(item)
      let quantity = parseInt(product.quantity)
      if (type === 'inc') {
        quantity += 1
      } else {
        quantity -= 1
      }
      this.updateProduct({
        item: item,
        quantity: quantity
      })
    },

    validateCart () {
      if (this.$store.state.cartCount > 0) {
        const items = []
        for (const item of this.$store.state.cart) {
          if (item.id) {
            items.push(item.id)
          }
        }
        const appVm = this
        axios
          .post('/api/web/cart/get_items_details', {
            items: items
          })
          .then((res) => {
            const responseItems = res.data
            responseItems.forEach((item) => {
              const product = appVm.findProduct(item)

              if (product) {
                this.$store.commit('updateItemCartAvailableQty', {
                  item: product,
                  available_qty: item.available_qty
                })
                if (
                  parseInt(item.available_qty) >= parseInt(product.quantity)
                ) {
                  this.orderProducts.push(product.id)
                }
              }
            })
            this.emitUpdateEvent()
          })
          .catch((error) => {})
      }
    },

    async itemQtyUpdated (item) {
      const quantity = parseInt(item.quantity)

      if (quantity >= 0) {
        await this.$store.commit('addToCart', {
          item: item,
          quantity: quantity
        })
      }
      this.emitUpdateEvent()
    },
    toggleOrderProduct (item) {
      const index = this.orderProducts.indexOf(item.id)
      if (this.orderProducts.includes(item.id)) {
        this.orderProducts.splice(index, 1)
      } else {
        this.orderProducts.push(item.id)
      }
      this.emitUpdateEvent()
    },

    removeCartItem (item) {
      this.$confirm(this.$page.$t.messages.are_you_sure, '', 'error', {
        confirmButtonText: this.$page.$t.messages.yes,
        cancelButtonText: this.$page.$t.messages.no
      }).then(() => {
        this.$store.commit('removeFromCart', item)
        this.emitUpdateEvent()
      })
    },

    emitUpdateEvent () {
      this.$emit('orderItems', {
        items: this.grabOrderItems()
      })
    }
  }
}
</script>

<style>
.el-input__inner {
  text-align: center !important;
}
</style>
