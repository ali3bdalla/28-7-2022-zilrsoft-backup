<template>
  <web-layout>
    <section class="shopping-cart spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div v-if="$store.state.cartCount >= 1" class="cart-table">
              <table>
                <thead>
                  <tr>
                    <th v-if="$page.client_logged">#</th>
                    <th>Image</th>
                    <th class="p-name">Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>
                      <i
                        v-if="$store.state.cartCount >= 1"
                        class="ti-close"
                      ></i>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(item, index) in $store.state.cart"
                    :key="index"
                    :class="[
                      parseInt(item.available_qty) < parseInt(item.quantity)
                        ? 'bg-red-200'
                        : '',
                    ]"
                    class="border-b border-gray-500"
                  >
                    <td class="w-20" v-if="$page.client_logged">
                      <input type="checkbox" />
                    </td>
                    <td class="cart-pic first-row text-center">
                      <img
                        alt=""
                        class="w-20 h-20 rounded-full object-center self-center mx-auto"
                        src="https://preview.colorlib.com/theme/fashi/img/cart-page/product-1.jpg"
                      />
                    </td>
                    <td class="cart-title first-row">
                      <h5>{{ item.name }}</h5>
                    </td>
                    <td class="p-price first-row">
                      {{ parseFloat(item.price).toFixed(2) }}
                    </td>
                    <td class="qua-col first-row">
                      <div class="quantity">
                        <div class="pro-qty">
                          <button
                            class="dec qtybtn"
                            @click="updateItemQuantity(item, 'dec')"
                          >
                            -
                          </button>
                          <input v-model="item.quantity" type="text" />
                          <button
                            class="inc qtybtn"
                            @click="updateItemQuantity(item, 'inc')"
                          >
                            +
                          </button>
                        </div>
                      </div>
                    </td>
                    <td class="total-price first-row">{{ getTotal(item) }}</td>
                    <td class="close-td first-row">
                      <i class="ti-close" @click="removeCartItem(item)"></i>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="text-center">
              <h1 class="text-6xl text-gray-500 font-bold uppercase">
                Your Cart Is Empty
              </h1>
            </div>
            <div v-if="$store.state.cartCount >= 1" class="row">
              <!--              <div class="col-lg-4">-->
              <!--                <div class="cart-buttons">-->
              <!--                  <a class="primary-btn continue-shop" href="#">Continue shopping</a>-->
              <!--                  <a class="primary-btn up-cart" href="#">Update cart</a>-->
              <!--                </div>-->
              <!--                <div class="discount-coupon">-->
              <!--                  <h6>Discount Codes</h6>-->
              <!--                  <form action="#" class="coupon-form">-->
              <!--                    <input placeholder="Enter your codes" type="text">-->
              <!--                    <button class="site-btn coupon-btn" type="submit">Apply</button>-->
              <!--                  </form>-->
              <!--                </div>-->
              <!--              </div>-->
              <div class="col-lg-4 offset-lg-8">
                <div class="proceed-checkout">
                  <ul>
                    <li class="subtotal">Subtotal <span>$240.00</span></li>
                    <li class="cart-total">Total <span>$240.00</span></li>
                  </ul>
                  <a v-if="$page.client_logged" class="proceed-btn" href="#"
                    >PROCEED TO CHECK OUT</a
                  >
                  <a v-else class="proceed-btn" href="/web/sign_in"
                    >LOGIN TO CHECK OUT</a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import WebLayout from "../../../Layouts/WebAppLayout";

export default {
  name: "Index",

  data() {
    return {
      cartItems: [],
    };
  },
  components: {
    WebLayout,
  },

  created() {
    this.updateCartItems();
  },
  methods: {
    updateItemQuantity(item, type) {
      let quantity = 0;
      if (type == "inc") {
        quantity = parseInt(item.quantity) + 1;
      } else {
        quantity = parseInt(item.quantity) - 1;
      }
      this.$store.commit("addToCart", item, quantity, "set");
    },

    updateCartItems() {
      if (this.$store.state.cartCount > 0) {
        let items = [];

        for (const item of this.$store.state.cart) {
          items.push(item.id);
        }
        console.log(items);
        let appVm = this;
        axios
          .post("/api/web/cart/get_items_details", {
            items: items,
          })
          .then((res) => {
            let responseItems = res.data;
            for (let responseItem of responseItems) {
              let product = appVm.$store.state.cart.find(
                (product) => product.id === responseItem.id
              );
              if (product)
                appVm.$store.updateItemCartAvailableQty(
                  product,
                  item.available_qty
                );
            }
          })
          .catch((error) => {
            // callback(res);
          });
      }
    },

    getTotal(item) {
      return parseFloat(parseInt(item.quantity) * item.price).toFixed(2);
    },
    removeCartItem(item) {
      this.$store.commit("removeFromCart", item);
    },
  },

  computed: {},
};
</script>

<style scoped>
</style>