<template>
  <div class="h-32 overflow-hidden border md:h-48" style="border-color:#d2e8ff !important">
<!-- 
    <div
        class="absolute inline-block px-2 py-1 text-sm font-extrabold text-center text-white truncate shadow-xl bg-web-primary"
    >
      {{ category.products_count }} {{$page.$t.products.product}}
    </div> -->

    
    <div class="flex items-center justify-center">
      <div class="flex-1">
        <div
          class="flex items-center justify-center w-full h-32 min-h-full p-3 text-xl font-bold text-center text-white md:text-2xl md:h-48"
          style="background-color: #575757"
        >
          {{ category.locale_name }}
        </div>
      </div>
      <div class="flex-1 bg-white">
        <img
          class="object-contain w-full h-32 p-1 md:h-48"
          :src="category.image_url"
        />

        <!-- <img src="https://media.extra.com/i/aurora/Mobiles_1x4_en.webp" class="object-cover w-full h-48 md:h-64" />
              <div>
                {{category.locale_name}}
              </div> -->
      </div>
    </div>
  </div>

  <!--         
  <div
      class="relative p-3 transition bg-white border-b border-l border-r hover:shadow-md"
  >
    <div
        class="absolute inline-block px-2 py-1 text-sm font-extrabold text-center text-white truncate rounded shadow-xl bg-web-primary"
    >
      {{ category.products_count }} {{$page.$t.products.product}}
    </div>
    <div class="w-full overflow-hidden">
      <img
          src="https://media.extra.com/i/aurora/Mobiles_1x4_en.webp"
          class="object-cover w-full h-84"/>
    </div>
 <h3 class="inline-block w-full px-2 py-1 mt-4 text-sm font-extrabold text-center text-gray-900 truncate bg-gray-200 rounded shadow-sm ">
      {{ category.locale_name }}
    </h3> -->
  <!-- <div class="h-16 mt-5 overflow-hidden">
      <h3 class="text-xl font-extrabold">{{ category.name }}</h3>
      <p class="mt-2 text-sm truncate">{{ category.description }}</p>
    </div> 
  </div> -->
</template>

<script>
export default {
  props: ["category"],
};
</script>

<style>
</style>>