<template>
  <div>
    <div class="md:hidden mb-4">
      <div>
        <div class="container flex justify-center items-center md:hidden">
          <div class="mx-auto w-full">
            <div v-for="(item, index) in $store.state.cart" :key="index" class="bg-white">
              <div
                class="border mt-2 flex flex-col justify-center p-2 pb-0  rounded-lg shadow-sm"
                style="border-color: #d2e8ff !important"
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
                    :src="$processedImageUrl(item.item_image_url,90 * 5,90 * 5)"
                    class="w-full object-cover object-center h-20"
                  />
                </div>
                <div class="prod-info grid mb-0 mt-1">
                  <div
                    class="mt-1 flex flex-col gap-2 justify-between items-center text-gray-900"
                  >
                    <el-input-number
                      :min="0"
                      :max="parseFloat(item.available_qty)"
                      style="text-align: center !important"
                      class="text-center"
                      size="small"
                      v-model="item.quantity"
                      @change="itemQtyUpdated(item)"
                      :step="1"
                    ></el-input-number>
                    <div class="font-bold mt-2 text-xl">
                      {{ $page.$t.products.total }}: &nbsp;
                      {{ getCartProductTotal(item) }} {{ $page.$t.products.sar }}
                    </div>
                  </div>
                </div>

                <div
                  class="mt-3 w-full mx-auto flex justify-between items-center text-gray-900"
                >
                  <el-button
                    @click="removeCartItem(item)"
                    type="danger"
                    icon="el-icon-delete"
                    style="margin-left: 0px;font-size: 26px;padding: 5px 14px;"
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
            <th></th>
            <th class="p-name">{{ $page.$t.products.name }}</th>
            <th>{{ $page.$t.products.price }}</th>
            <th>{{ $page.$t.products.quantity }}</th>
            <th>{{ $page.$t.products.total }}</th>
            <th v-if="$page.client_logged">
            <i v-if="cartItemsList.length >= 1" class="ti-close"></i>
            </th>
          </tr>
        </thead>
        <tbody v-for="(item, index) in cartItemsList" :key="index">
          <tr
            class="cart__table-raw"

          >
            <td class="text-center cart-pic first-row">
              <img class="cart__item-image" :src="$processedImageUrl(item.item_image_url,90 * 5 , 90 * 5)" />
            </td>
            <td class="cart-title first-row" style="font-size: 15px !important">
              {{ getItemNamme(item) }}
            </td>
            <td class="p-price first-row">
              {{ parseFloat(item.online_offer_price).toFixed(2) }}
            </td>
            <td  class="qua-col first-row">
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
            <td class="total-price first-row">{{ getCartProductTotal(item) }}</td>
            <td class="close-td first-row">
              <i class="fa fa-remove" @click="removeCartItem(item)"></i>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { getProductTotal } from './../../../repository/products'
export default {
  name: 'CartItems',
  props: {
    cartItemsList: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      select_all: false,
      shippingAddressMethod: 1,
      shippingAddressId: 0,
      cart: []
    }
  },

  methods: {
    getCartProductTotal (product) {
      return getProductTotal(product, { price: 'online_offer_price' })
    },
    getItemNamme (item) {
      if (this.$page.active_locale === 'en') return item.name
      return item.ar_name
    },
    updateProduct (payload) {
      this.$store.commit('addToCart', payload)
    },
    updateOrderProductQuantity (product, type) {
      let quantity = parseFloat(product.quantity)
      if (type === 'inc') {
        quantity += 1
      } else {
        quantity -= 1
      }
      this.updateProduct({
        item: product,
        quantity: quantity
      })
    },
    async itemQtyUpdated (item) {
      const quantity = parseInt(item.quantity)
      if (quantity >= 0) {
        await this.$store.commit('addToCart', {
          item: item,
          quantity: quantity
        })
      }
    },

    removeCartItem (item) {
      this.$confirm(this.$page.$t.messages.are_you_sure, '', 'error', {
        confirmButtonText: this.$page.$t.messages.yes,
        cancelButtonText: this.$page.$t.messages.no
      }).then(() => {
        this.$store.commit('removeFromCart', item)
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
