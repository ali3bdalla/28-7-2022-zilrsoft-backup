<template>
  <div class="flex justify-content-center">
    <div class="cart__totals-buttons">
      <div class="cart__total-amount" v-if="shouldRenderTotal">
        <span>{{ $page.props.$t.cart.total }}</span>
        <div>
          <RenderMoneyComponent :money="cartTotal"></RenderMoneyComponent>
          {{ $page.props.$t.products.sar }}
        </div>
      </div>
      <div class="cart__total-amount" v-if="shouldRenderShipping">
        <span>{{ $page.props.$t.cart.shipping_weight }}</span>
        <div>
          <RenderMoneyComponent :money="shippingWeight"></RenderMoneyComponent>
          {{ $page.props.$t.products.kg }}
        </div>
      </div>
      <div class="cart__total-amount" v-if="shouldRenderShipping">
        <span>{{ $page.props.$t.cart.shipping_total }}</span>
        <div>
          <div v-if="shippingSubtotal > 0">
            <RenderMoneyComponent
              :money="shippingSubtotal"
            ></RenderMoneyComponent>
          </div>
          <span v-else>{{ $page.props.$t.cart.free }}</span>
        </div>
      </div>
      <div class="cart__total-amount" v-if="all">
        <span>{{ $page.props.$t.cart.net }}</span>
        <div>
          <RenderMoneyComponent :money="orderNetAmount"></RenderMoneyComponent>
          {{ $page.props.$t.products.sar }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import RenderMoneyComponent from "../Utility/RenderMoneyComponent.vue";
import { getOrderTotal, getOrderNet } from "../../../repository/orders";
import {
  getProductsWeight,
  getShippingSubtotal,
} from "../../../repository/shipping";
export default {
  components: { RenderMoneyComponent },
  computed: {
    orderNetAmount() {
      if (this.shippingMethod)
        return getOrderNet(this.shippingMethod, this.itemsList);
      return 0;
    },
    itemsList() {
      return this.$page.props.cart.items.map((cartItem) => {
        cartItem.item.quantity = cartItem.quantity;
        return cartItem.item;
      });
    },
    shippingSubtotal() {
      if (!this.shippingMethod) return 0;
      return getShippingSubtotal(this.shippingMethod, this.itemsList);
    },
    shippingWeight() {
      return getProductsWeight(this.itemsList);
    },
    shouldRenderShipping() {
      return this.shippingMethod && (this.shipping || this.all);
    },
    shouldRenderTotal() {
      return this.total || this.all;
    },
    cartTotal() {
      return parseFloat(
        this.$page.props.cart.items.reduce(
          (c, n) => c + parseFloat(n.quantity * n.price),
          0
        )
      ).toFixed(2);
    },
  },
  props: {
    shippingMethod: {
      type: Object,
      default: () => {},
    },
    shipping: {
      type: Boolean,
      default: false,
    },
    all: {
      type: Boolean,
      default: false,
    },
    total: {
      type: Boolean,
      default: false,
    },
  },
  name: "CartAmount",
};
</script>
