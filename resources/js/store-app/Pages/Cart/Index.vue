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
            <div v-else>
              <CartProgress :active="0" />
              <CartItems />
              <div class="page__mt-5 col-lg-12">
                <CartAmount :total="true" />
                <div class="flex justify-content-center">
                  <div class="cart__totals-buttons">
                    <button
                      class="cart__submit-btn"
                      @click="$inertia.visit('/web/cart/shipping_method')"
                    >
                      {{ $page.props.$t.common.next }}
                    </button>
                  </div>
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
import CartItems from "./../../Components/Cart/CartItems";
import CartProgress from "./../../Components/Cart/CartProgress";
import CartAmount from "./../../Components/Cart/CartAmount";
import WebLayout from "../../Layouts/WebAppLayout";

import ShowEmptyCartComponent from "./../../Components/Cart/ShowEmptyCartComponent.vue";
export default {
  name: "Checkout",
  components: {
    CartItems,
    CartProgress,
    CartAmount,
    ShowEmptyCartComponent,
    WebLayout,
  },
  computed: {
    isEmpty() {
      return this.$page.props.cart.items_count == 0;
    },
  },
};
</script>
