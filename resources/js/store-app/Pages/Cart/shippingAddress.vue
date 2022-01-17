<template>
  <web-layout>
    <section
      class="shopping-cart spad cart"
      style="padding-top: 0px !important"
    >
      <div class="container">
        <div class="page__mt-5 col-lg-12">
          <h2
            v-if="$page.props.shippingAddresses.length > 0"
            class="cart__shipping-method-title"
          >
            {{ $page.props.$t.cart.select_shipping_address }}
            {{ $page.props.$t.common.or }}
            <inertia-link
              class="text-blue-400"
              href="/web/profile/create-shipping-address"
              >{{ $page.props.$t.common.add_new }}
            </inertia-link>
          </h2>
          <div class="cart__totals-buttons">
            <CartAmount :total="true" />
            <inertia-link href="/web/cart/checkout" class="cart__submit-btn">
              {{ $page.props.$t.cart.checkout }}
            </inertia-link>
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
