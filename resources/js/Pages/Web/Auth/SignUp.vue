<template>
  <web-layout>
    <div class="register-login-section ">
      <div class="container">
        <div class="">
          <div class="col-lg-6 offset-lg-3">
            <div class="login-form">
              <h2>SignUp</h2>
              <form action="#">
                <div class="flex">
                  <div class="flex-1 group-input">
                    <label for="phone_number">Phone Number</label>
                    <input
                      type="text"
                      id="phone_number"
                      placeholder="Phone Number"
                    />
                  </div>
                  <div class="flex-1 group-input">
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Username" />
                  </div>
                </div>


                <div class="flex">
                  <div class="flex-1 group-input">
                    <label for="phone_number">Phone Number</label>
                    <input
                      type="text"
                      id="phone_number"
                      placeholder="Phone Number"
                    />
                  </div>
                  <div class="flex-1 group-input">
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Username" />
                  </div>
                </div>



                <div class="flex">
                  <div class="flex-1 group-input">
                    <label for="phone_number">Phone Number</label>
                    <input
                      type="text"
                      id="phone_number"
                      placeholder="Phone Number"
                    />
                  </div>
                  <div class="flex-1 group-input">
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Username" />
                  </div>
                </div>

                
                <div class="group-input gi-check">
                  <div class="gi-more">
                    <label for="save-pass">
                      Save Password
                      <input type="checkbox" id="save-pass" />
                      <span class="checkmark"></span>
                    </label>
                    <a href="#" class="forget-pass">Forget your Password</a>
                  </div>
                </div>
                <button type="submit" class="site-btn login-btn">
                  Sign In
                </button>
              </form>
              <div class="switch-login">
                <a href="./register.html" class="or-login"
                  >Or Create An Account</a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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