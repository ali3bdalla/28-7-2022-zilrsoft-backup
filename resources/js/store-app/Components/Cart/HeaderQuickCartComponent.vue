<template>
  <div class="page__header-quick-cart">
    <ul class="nav-right">
      <li class="cart-icon page__header-cart-icon-container">
        <a
          :class="applyClass"
          :href="[cartItemsCount === 0 ? '#' : '/web/cart']"
        >
          <svg
            class="page__header-cart-icon"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
            />
          </svg>
          <span
            class="page__header-cart-icon-counter"
            style="
              width: 34px;
              height: 34px;
              text-align: center;
              font-size: 20px;
              vertical-align: middle;
              justify-content: center;
              justify-items: center;
              display: flex;
              align-items: center;
            "
            >{{ cartItemsCount }}</span
          >
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "HeaderQuickCartComponent",
  data() {
    return {
      applyClass: "",
    };
  },
  computed: {
    cartItemsCount() {
      return this.$page.props.cart.items_count;
    },
  },
  watch: {
    cartCount: function(oldValue, NewValue) {
      this.applyClass = "animate__animated animate__rubberBand ";
      const appVm = this;
      setTimeout(function() {
        appVm.applyClass = " ";
      }, 350);
    },
  },
};
</script>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css";
</style>
