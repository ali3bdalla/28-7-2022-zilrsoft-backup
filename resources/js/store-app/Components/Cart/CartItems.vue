<template>
  <div>
    <div class="md:hidden mb-4">
      <div>
        <div class="container flex justify-center items-center md:hidden">
          <div class="mx-auto w-full">
            <div
              v-for="(cartItem, index) in cartItems"
              :key="index"
              class="bg-white"
            >
              <div
                class="border mt-2 flex flex-col justify-center p-2 pb-0  rounded-lg shadow-sm"
                style="border-color: #d2e8ff !important"
              >
                <div class="prod-title mx-auto">
                  <p class="uppercase text-center mt-2 text-gray-900 font-bold">
                    {{ cartItem.item.locale_name }}
                  </p>
                  <p class="uppercase text-xl text-center">
                    {{ cartItem.item.online_offer_price }}
                    {{ $page.props.$t.products.sar }}
                  </p>
                </div>
                <div class="prod-img border p-2 w-32 mx-auto">
                  <img
                    :src="
                      $processedImageUrl(
                        cartItem.item.item_image_url,
                        90 * 5,
                        90 * 5
                      )
                    "
                    class="w-full object-cover object-center h-20"
                  />
                </div>
                <div class="prod-info grid mb-0 mt-1">
                  <div
                    class="mt-1 flex flex-col gap-2 justify-between items-center text-gray-900"
                  >
                    <el-input-number
                      :min="0"
                      :max="parseFloat(cartItem.item.available_qty)"
                      style="text-align: center !important"
                      class="text-center"
                      size="small"
                      v-model="cartItem.quantity"
                      @change="updateQuantity(cartItem)"
                      :step="1"
                    ></el-input-number>
                    <div class="font-bold mt-2 text-xl">
                      {{ $page.props.$t.products.total }}: &nbsp;
                      {{ getCartProductTotal(cartItem) }}
                      {{ $page.props.$t.products.sar }}
                    </div>
                  </div>
                </div>

                <div
                  class="mt-3 w-full mx-auto flex justify-between items-center text-gray-900"
                >
                  <el-button
                    @click="removeCartItem(cartItem)"
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
            <th class="p-name">{{ $page.props.$t.products.name }}</th>
            <th>{{ $page.props.$t.products.price }}</th>
            <th>{{ $page.props.$t.products.quantity }}</th>
            <th>{{ $page.props.$t.products.total }}</th>
            <th v-if="$page.props.client_logged">
              <i v-if="cartItems.length >= 1" class="ti-close"></i>
            </th>
          </tr>
        </thead>
        <tbody v-for="(cartItem, index) in cartItems" :key="index">
          <tr class="cart__table-raw">
            <td class="text-center cart-pic first-row">
              <img
                class="cart__item-image"
                :src="
                  $processedImageUrl(
                    cartItem.item.item_image_url,
                    90 * 5,
                    90 * 5
                  )
                "
              />
            </td>
            <td class="cart-title first-row" style="font-size: 15px !important">
              {{ cartItem.item.locale_name }}
            </td>
            <td class="p-price first-row">
              {{ parseFloat(cartItem.price).toFixed(2) }}
            </td>
            <td class="qua-col first-row">
              <div class="quantity">
                <div class="pro-qty">
                  <button
                    class="dec qtybtn"
                    @click="updateOrderProductQuantity(cartItem, 'dec')"
                  >
                    -
                  </button>
                  <input
                    v-model="cartItem.quantity"
                    type="text"
                    @change="updateQuantity(cartItem)"
                  />
                  <button
                    class="inc qtybtn"
                    @click="updateOrderProductQuantity(cartItem, 'inc')"
                  >
                    +
                  </button>
                </div>
              </div>
            </td>
            <td class="total-price first-row">
              {{ cartItem.item.total }}
            </td>
            <td class="close-td first-row">
              <i class="fa fa-remove" @click="removeCartItem(cartItem)"></i>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { getProductTotal } from "./../../../repository/products";
export default {
  name: "CartItems",
  data() {
    return {
      select_all: false,
      shippingAddressMethod: 1,
      shippingAddressId: 0,
      cart: [],
    };
  },
  computed: {
    cartItems() {
      return this.$page.props.cart_items;
    },
  },
  methods: {
    getCartProductTotal(product) {
      return 0;
    },

    async updateOrderProductQuantity(cartItem, type) {
      let quantity = parseFloat(cartItem.quantity);
      if (type === "inc") {
        quantity += 1;
      } else {
        quantity -= 1;
      }
      this.updateQuantity(cartItem, quantity);
    },
    async updateQuantity(cartItem, quantity = null) {
      if (quantity == null) parseFloat(cartItem.quantity);
      if (quantity > 0 && quantity <= parseFloat(cartItem.item.available_qty)) {
        axios
          .put("/api/web/cart/update_quantity", {
            quantity: quantity,
            cart_item_id: cartItem.id,
          })
          .then((res) => {
            location.reload();
          });
      }
    },

    removeCartItem(item) {
      this.$confirm(this.$page.props.$t.messages.are_you_sure, "", "error", {
        confirmButtonText: this.$page.props.$t.messages.yes,
        cancelButtonText: this.$page.props.$t.messages.no,
      }).then(() => {
        axios
          .delete("/api/web/cart/remove_item", {
            params: { cart_item_id: item.id },
          })
          .then((res) => {
            location.reload();
          });
      });
    },
  },
};
</script>

<style>
.el-input__inner {
  text-align: center !important;
}
</style>
