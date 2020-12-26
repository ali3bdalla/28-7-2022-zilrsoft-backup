<template>
  <div class="product__ratings-list">
    <svg
        v-for="x in 5"
        :key="x"
        :class="[x > rating ? 'product__ratings-svg__inactive' : 'product__ratings-svg__active']"
        class="product__ratings-svg"
        viewBox="0 0 20 20"
        xmlns="http://www.w3.org/2000/svg"
    >
      <path
          d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"
      />
    </svg>
  </div>
</template>

<script>
export default {
  props: ["item"],
  computed: {
    rating() {
      return Math.floor(Math.random() * (5- 4+ 1) + 4);
      return (Math.random() * 100) % 6;
    },
  },
};
</script>

<style>
</style>>