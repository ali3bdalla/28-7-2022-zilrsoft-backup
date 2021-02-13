<template>
  <div>
    <button
      v-if="!$store.state.cart.find((product) => product.id === item.id)"
      class="product__add-to-cart-button"
      @click="addItem"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        class="product__add-to-cart-icon"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
        />
      </svg>
      {{ $page.$t.products.add_to_cart }}
    </button>
    <button v-else class="product__remove-from-cart-button" @click="removeItem">
      <svg
        class="product__add-to-cart-icon"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
        />
      </svg>
      {{ $page.$t.products.remove_to_cart }}
    </button>
  </div>
</template>

<script>
export default {
  props: ['item'],

  methods: {
    addItem () {
      this.$store.commit('addToCart', { item: this.item, quantity: 1 })
    },
    removeItem () {
      this.$store.commit('removeFromCart', this.item)
    }
  }
}
</script>

<style>
</style>>
