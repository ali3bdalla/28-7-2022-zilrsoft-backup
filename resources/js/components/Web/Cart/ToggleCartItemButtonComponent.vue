<template>
  <div>
    <button v-if="!$store.state.cart.find(product => product.id === item.id)"
            class="bg-web-primary py-2 w-full text-center md:px-5  font-extrabold text-white text-sm uppercase"
            @click="addItem">Add To
      Cart
    </button>
  </div>
</template>

<script>
export default {
  props: ['item'],

  methods: {
    addItem() {
      this.$store.commit('addToCart', this.item)
    }
  }
}
</script>

<style>

</style>>