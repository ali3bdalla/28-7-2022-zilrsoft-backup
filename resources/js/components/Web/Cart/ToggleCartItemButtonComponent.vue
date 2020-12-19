<template>
  <div>
    <button
      v-if="!$store.state.cart.find((product) => product.id === item.id)"
      class="product__add-to-cart-button"
      @click="addItem"
    >
      {{ $page.$t.products.add_to_cart}}
    </button>
    <button
      v-else
      class="bg-gray-300 py-2 w-full text-center md:px-5 font-extrabold text-black text-sm uppercase hover:bg-gray-500"
      @click="removeItem"
    >
      {{ $page.$t.products.remove_to_cart}}
    </button>
  </div>
</template>

<script>
export default {
  props: ["item"],

  methods: {
    addItem() {
      this.$store.commit("addToCart",{item: this.item,quantity:1});
    },
    removeItem() {
      console.log('delete');
      this.$store.commit("removeFromCart", this.item);
    },
  },
};
</script>

<style>
</style>>