<template>
  <web-layout>
    <section
      class="shopping-cart spad cart"
      style="padding-top: 0px !important"
    >
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <ShowEmptyCartComponent v-if="isEmpty"></ShowEmptyCartComponent>
            <CartItems />
            <div class="cart__totals-buttons">
              <CartAmount :total="true" />
              <inertia-link class="cart__submit-btn" href="/">
                {{ $page.props.$t.cart.checkout }}
              </inertia-link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import CartItems from "./../../Components/Cart/CartItems";
import CartAmount from "./../../Components/Cart/CartAmount";
import WebLayout from "../../Layouts/WebAppLayout";

import ShowEmptyCartComponent from "./../../Components/Cart/ShowEmptyCartComponent.vue";
export default {
  name: "Checkout",
  components: { CartItems, CartAmount, ShowEmptyCartComponent, WebLayout },
  computed: {
    isEmpty() {
      return this.$page.props.cart_items.length === 0;
    },
  },
};
</script>
