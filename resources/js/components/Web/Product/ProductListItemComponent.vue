<template>
  <div
    class="mb-0 bg-white p-2 pt-4 border-b border-r border-l border-t transition hover:shadow-md text-center flex flex-col"
  >
    <div class="overflow-hidden w-full">
      <img :src="itemImage" class="mx-auto object-cover" />
    </div>
    <div class="mt-5 overflow-hidden p-2">
      <h3
        class="text-sm bg-gray-900 shadow rounded font-extrabold truncate inline-block py-1 px-2 text-white w-full text-center"
      >
        {{ item.category.name }}
      </h3>
      <a
        :href="`/web/items/${item.id}`"
        class="block  mt-2 text-lg md:text-xl h-16 overflow-hidden font-extrabold text-web-primary hover:text-yellow-600"
      >
        {{ item.name }}
      </a>
      <ProductRatingComponent
        class="mt-2"
        :item="item"
      ></ProductRatingComponent>

      <h4
        class="mt-2 text-2xl text-gray-900 font-bold inline-block line-through"
      >
        {{ parseFloat(item.price_with_tax).toFixed(2) }}
      </h4>
      <span class="text-sm text-web-primary font-bold">SR</span>
      <div>
        <h4 class="mt-2 text-2xl text-gray-900 font-bold inline-block">
          {{ parseFloat(item.price).toFixed(2) }}
        </h4>
        <span class="text-sm text-web-primary font-bold">SR</span>
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
  props: ["item", "index"],
  data() {
    return {
      images: [
        "https://images10.newegg.com/ProductImageCompressAll180/11-133-244-41.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/A8X5_131058525764839488GVB8iSbB5E.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/96-110-022-11.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/20-721-108-02.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/35-608-044_R01.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/75-100-554-02.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/11-133-244-41.jpg",
      ],
    };
  },
  computed: {
    itemImage() {
      return this.item.image
        ? this.item.image
        : this.images[parseInt(Math.random() * 10) % 7];
    },
  },
};
</script>

<style>
</style>>