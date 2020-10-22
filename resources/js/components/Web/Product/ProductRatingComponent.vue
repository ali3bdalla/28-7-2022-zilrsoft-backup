<template>
  <div class="flex gap-1 my-2 items-center justify-center">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 20 20"
      v-for="x in 5"
      :key="x"
      class="w-6 h-6 fill-current"
      :class="[x > rating ? 'text-gray-200' : 'text-web-primary']"
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
      return (Math.random() * 100) % 6;
    },
  },
};
</script>

<style>
</style>>