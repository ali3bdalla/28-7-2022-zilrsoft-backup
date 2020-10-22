<template>
  <div class=" bg-white p-2 pt-4 border-l border-b border-r transition hover:shadow-md shadow-sm text-center">
    <div class="overflow-hidden w-full">
      <img :src="item.image" class="mx-auto object-cover" />
    </div>
    <div class="mt-5 overflow-hidden p-2">
    <h3 class="text-sm bg-gray-900 shadow rounded font-extrabold truncate inline-block py-1 px-2 text-white w-full text-center ">
        {{ item.category.name }}
      </h3>
      <h3 class="mt-2 text-lg md:text-xl h-12 overflow-hidden font-extrabold">
        {{ item.name }}
      </h3>
      <ProductRatingComponent
        class="mt-2"
        :item="item"
      ></ProductRatingComponent>

      <h4
        class="mt-2 text-2xl text-gray-900 font-bold inline-block line-through"
      >
        {{ parseFloat(item.price_with_tax).toFixed(2) }}
      </h4>
      <span class="text-sm text-web-primary font-bold">.SAR</span>
      <div>
        <h4 class="mt-2 text-2xl text-gray-900 font-bold inline-block">
          {{ parseFloat(item.price).toFixed(2) }}
        </h4>
        <span class="text-sm text-web-primary font-bold">.SAR</span>
      </div>
      <ToggleCartItemButtonComponent
        class="mt-2"
        :item="item"
      ></ToggleCartItemButtonComponent>
    </div>
  </div>
</template>

<script>
import ToggleCartItemButtonComponent from "../Cart/ToggleCartItemButtonComponent";
import ProductRatingComponent from "./ProductRatingComponent";

export default {
  components: { ToggleCartItemButtonComponent, ProductRatingComponent },
  props: ["item"],
};
</script>

<style>
</style>>