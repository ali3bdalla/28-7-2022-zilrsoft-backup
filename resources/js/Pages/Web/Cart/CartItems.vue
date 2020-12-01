<template>
  <div v-if="$store.state.cartCount >= 1" class="cart-table">
    <table>
      <thead>
        <tr>
          <th v-if="$page.client_logged && activePage === 'cart'">
            <input type="checkbox" class="cart__item-checkbox" />
          </th>
          <th>Image</th>
          <th class="p-name">Product Name</th>
          <th>Price</th>
          <th v-if="activePage === 'cart'">Quantity</th>
          <th>Total</th>
          <th  v-if="$page.client_logged && activePage === 'cart'">
            <i v-if="$store.state.cartCount >= 1" class="ti-close"></i>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in $store.state.cart"
          :key="index"
          :class="[
            parseInt(item.available_qty) < parseInt(item.quantity)
              ? 'cart__table-raw__red'
              : '',
          ]"
          class="cart__table-raw"
        >
          <td
            v-if="$page.client_logged && activePage === 'cart'"
            class="cart__item-checkbox-container"
          >
            <input
              v-if="parseInt(item.available_qty) >= parseInt(item.quantity)"
              :checked="orderProducts.find((p) => p.id == item.id) !== null"
              class="cart__item-checkbox"
              type="checkbox"
              @change="toggleOrderProduct(item)"
            />
          </td>
          <td class="cart-pic first-row text-center">
            <img
              class="cart__item-image"
              src="https://preview.colorlib.com/theme/fashi/img/cart-page/product-1.jpg"
            />
          </td>
          <td class="cart-title first-row">
            <h5>{{ item.name }}</h5>
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
          <td class="total-price first-row">{{ getProductTotal(item) }}</td>
          <td class="close-td first-row"  v-if="$page.client_logged && activePage === 'cart'">
            <i class="ti-close" @click="removeCartItem(item)" ></i>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import CartMixin from "./CartMixin";

export default {
  name: "CartItems",
  mixins: [CartMixin],
  data() {
    return {
      shippingAddressMethod: 1,
      shippingAddressId: 0,
      cart: [],
      orderProducts: [],
    };
  },
  created() {
    this.validateCart();
  },
  methods: {
    grabOrderItems() {
      let items = [];
      let itemsId = [];
      for (let index = 0; index < this.orderProducts.length; index++) {
        const element = this.orderProducts[index];
        let product = this.findProductById(element);
        if (
          product &&
          parseInt(product.available_qty) >= parseInt(product.quantity) &&
          !itemsId.includes(element)
        ) {
          items.push(product);
          itemsId.push(element);
        }
      }

      return items;
    },

    setActivePage(activePage) {
      this.activePage = activePage;
    },

    findProductById(id) {
      return this.$store.state.cart.find((p) => p.id === id);
    },
    findProduct(product) {
      return this.findProductById(product.id);
    },

    updateProduct(payload) {
      this.$store.commit("addToCart", payload);
      this.emitUpdateEvent();
    },

    updateOrderProductQuantity(item, type) {
      let product = this.findProduct(item);
      let quantity = parseInt(product.quantity);
      if (type === "inc") {
        quantity += 1;
      } else {
        quantity -= 1;
      }
      this.updateProduct({
        item: item,
        quantity: quantity,
      });
    },

    validateCart() {
      if (this.$store.state.cartCount > 0) {
        let items = [];
        for (const item of this.$store.state.cart) {
          if (item.id) {
            items.push(item.id);
          }
        }
        let appVm = this;
        axios
          .post("/api/web/cart/get_items_details", {
            items: items,
          })
          .then((res) => {
            let responseItems = res.data;
            responseItems.forEach((item) => {
              let product = appVm.findProduct(item);

              if (product) {
                this.$store.commit("updateItemCartAvailableQty", {
                  item: product,
                  available_qty: item.available_qty,
                });
                if (
                  parseInt(item.available_qty) >= parseInt(product.quantity)
                ) {
                  this.orderProducts.push(product.id);
                }
              }
            });
            this.emitUpdateEvent();
          })
          .catch((error) => {});
      }
    },

    async itemQtyUpdated(item) {
      let quantity = parseInt(item.quantity);

      if (quantity >= 0) {
        await this.$store.commit("addToCart", {
          item: item,
          quantity: quantity,
        });
      }
      this.emitUpdateEvent();
    },
    toggleOrderProduct(item) {
      let index = this.orderProducts.indexOf(item.id);
      if (this.orderProducts.includes(item.id)) {
        this.orderProducts.splice(index, 1);
      } else {
        this.orderProducts.push(item.id);
      }
      this.emitUpdateEvent();
    },

    removeCartItem(item) {
      this.$confirm("are you sure ?", "", "error").then(() => {
        this.$store.commit("removeFromCart", item);
        this.emitUpdateEvent();
      });
    },

    emitUpdateEvent() {
      this.$emit("orderItems", {
        items: this.grabOrderItems(),
      });
    },
  },
};
</script>

<style scoped>
</style>
