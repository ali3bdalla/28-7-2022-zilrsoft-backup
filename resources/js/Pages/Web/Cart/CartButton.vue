<template>
  <div class="cart__totals-buttons">
    <div>
      <div class="cart__total-amount">
        <span>Total (Inc Vat): </span>
        <display-money :money="getOrderTotalAmount(orderItems)"></display-money>
      </div>
      <div v-show="activePage === 'checkout' && shippingMethodId">
        <div class="cart__total-amount">
          <span>Shipping Weight: </span>
          <display-money :money="getTotalShippingWeight()"></display-money>
        </div>
        <div class="cart__total-amount">
          <span>Shipping Total: </span>

          <display-money
              :money="getTotalShippingSubtotal()"
              v-if="getTotalShippingSubtotal() > 0"
          ></display-money>
          <span v-else>Free</span>
        </div>


        <div class="cart__total-amount">
          <span>Net Amount: </span>

          <display-money
              :money="getOrderNetAmount()"

          ></display-money>

        </div>
        <!-- <div class="cart__total-amount">
          <span>Shipping Discount: </span>
          <display-money :money="getShippingDiscount()"></display-money>
        </div> -->
      </div>
    </div>
    <div v-if="activePage === 'cart'">
      <button class="proceed-btn" @click="changeActivePage('checkout')">
        Checkout ({{ orderItems.length }})
      </button>
    </div>

    <div v-else>
      <button v-if="$page.client_logged" class="proceed-btn" @click="sendOrder">
        Confirm Order
      </button>
      <a v-else class="proceed-btn" href="/web/sign_in">LOGIN TO CHECK OUT</a>
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
    shippingMethodId: {
      required: true,
      type: Number,
    },
  },
  data() {
    return {
      shippingMethod: {},
      shippingTotal: 0,
      shippingWeight: 0,
      shippingDiscount: 0,
      shippingSubtotal: 0,
    };
  },
  methods: {
    changeActivePage(page) {
      console.log(page);
      this.$emit("changeActivePage", {page: page});
    },

    sendOrder() {
      console.log("works");
      this.$emit("sendOrder");
    },

    updateShippingDetails() {
      this.shippingMethod = this.$page.shipping_methods.find(
          (p) => p.id === value
      );
    },
    getTotalShippingWeight() {
      let totalWeight = 0;
      let items = this.orderItems;
      for (let index = 0; index < items.length; index++) {
        totalWeight +=
            parseFloat(items[index].weight) * parseInt(items[index].quantity);
      }
      return totalWeight;
    },

    getShippingDiscount() {
      let discount = 0;
      let items = this.orderItems;
      let totalShippingAmount = parseFloat(this.getTotalShippingAmount());
      for (let index = 0; index < items.length; index++) {
        discount +=
            parseFloat(items[index].shipping_discount) *
            parseInt(items[index].quantity);
      }

      if (totalShippingAmount < discount) {
        discount = totalShippingAmount;
      }
      return discount;
    },

    getOrderNetAmount() {
      return parseFloat(this.getTotalShippingSubtotal()) + parseFloat(this.getOrderTotalAmount(this.orderItems));
    },

    getShippingMethod() {
      let shippingMethod = this.$page.shippingMethods.find(
          (p) => p.id == this.shippingMethodId
      );
      return shippingMethod ? shippingMethod : {};
    },

    getTotalShippingSubtotal() {
      return parseFloat(this.getTotalShippingAmount()) - parseFloat(this.getShippingDiscount());
    },
    getTotalShippingAmount() {
      let totalWeight = parseFloat(this.getTotalShippingWeight()); // 39

      if(totalWeight == 0)
        return 0;
      let maxBaseWeight = parseFloat(this.getShippingMethod().max_base_weight);
      let shippingAmount = parseFloat(
          this.getShippingMethod().max_base_weight_price
      );



      if (maxBaseWeight < totalWeight) {
        let weightVaritionToBase = totalWeight - maxBaseWeight;
        shippingAmount +=
            weightVaritionToBase *
            parseFloat(this.getShippingMethod().kg_rate_after_max_price);
      }

      return shippingAmount;
    },
  },
};
</script>

<style scoped>
</style>