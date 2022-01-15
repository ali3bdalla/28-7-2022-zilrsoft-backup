<template>
  <div class="cart__total-amount" v-if="total">
    <span>{{ $page.props.$t.cart.total }}</span>
    <div>
      {{ cartTotal }}
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    cartTotal() {
      return parseFloat(
        this.$page.props.cart_items.reduce(
          (c, n) => c + parseFloat(n.quantity * n.price),
          0
        )
      ).toFixed(2);
    },
  },
  props: {
    total: {
      type: Boolean,
      default: false,
    },
  },
  name: "CartAmount",
};
</script>
