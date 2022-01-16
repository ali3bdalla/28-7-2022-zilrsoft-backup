<template>
  <web-layout>
    <section
      class="shopping-cart spad cart"
      style="padding-top: 0px !important"
    >
      <div class="container">
        <div class="page__mt-5 col-lg-12">
          <div class="cart__totals-buttons">
            <CartAmount :total="true" />
            <button class="cart__submit-btn" @click="checkout">
              {{ $page.props.$t.cart.checkout }}
            </button>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import CartAmount from "./../../Components/Cart/CartAmount";
import WebLayout from "../../Layouts/WebAppLayout";

export default {
  name: "ShippingMethod",
  components: { CartAmount, WebLayout },
  methods: {
    checkout() {},
  },
};
</script>
