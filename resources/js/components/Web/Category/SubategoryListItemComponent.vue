<template>
  <div
    class=" p-3 bg-white border-l border-b border-r transition hover:shadow-md  relative"
  >
    <div
      class="text-sm bg-web-primary absolute   shadow-xl rounded font-extrabold truncate inline-block py-1 px-2 text-white  text-center"
    >
      {{ category.products_count }} Products
    </div>
    <div class="overflow-hidden w-full">
      <img :src="category.image" class="h-32 object-contain" />
    </div>
    <h3 class="mt-4 text-sm bg-gray-200  text-gray-900 shadow-sm rounded font-extrabold truncate inline-block py-1 px-2  w-full text-center ">
      {{ category.name }}    
    </h3>
    <!-- <div class="mt-5 h-16 overflow-hidden">
      <h3 class="text-xl font-extrabold">{{ category.name }}</h3>
      <p class="mt-2 text-sm truncate">{{ category.description }}</p>
    </div> -->
  </div>
</template>

<script>
export default {
  props: ["category"],
};
</script>

<style>
</style>>