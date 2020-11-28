<template>
  <div class="cart__totals-buttons">
    <div>
      <div class="cart__total-amount">
        <span>Total(Inc Vat): </span>
        <display-money :money="getOrderTotalAmount(orderItems)"></display-money>
      </div>
      <div class="cart__total-amount">
        <span>Shipping: </span>
        <display-money :money="getOrderTotalAmount(orderItems)"></display-money>
      </div>
      <div class="cart__total-amount">
        <span>Grant Total: </span>
        <display-money :money="getOrderTotalAmount(orderItems)"></display-money>
      </div>
    </div>
    <div v-if="activePage === 'cart'">

      <button class="proceed-btn" @click="changeActivePage('checkout')">
        Checkout ({{ orderItems.length }})
      </button>
    </div>

    <div v-else>
      <button v-if="$page.client_logged" class="proceed-btn" @click="sendOrder"
      >Confirm Order
      </button>
      <a v-else class="proceed-btn" href="/web/sign_in"
      >LOGIN TO CHECK OUT</a
      >
    </div>

  </div>
</template>

<script>
import CartMixin from "./CartMixin";
import DisplayMoney from "../../../components/BackEnd/Money/DisplayMoney";

export default {
  components: {DisplayMoney},
  mixins: [CartMixin],
  name: "CartButton",
  props: {
    orderItems: {
      type: Array,
      required: true,
    },
  },
  methods: {
    changeActivePage(page) {
      console.log(page);
      this.$emit("changeActivePage", {page: page});
    },

    sendOrder() {
      console.log('works')
      this.$emit("sendOrder");
    }
  },
};
</script>

<style scoped>
</style>