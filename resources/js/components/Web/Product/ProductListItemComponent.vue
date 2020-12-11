<template>
  <div class="product__list-item">
    <div class="product__list-item-image-container">
      <img :src="itemImage" class="product__list-item-image" />
    </div>
    <div class="p-2 mt-5 overflow-hidden">
      <h3 class="product__list-item-category-name product__list-item-category-name__out-of-stock" v-if="item.available_qty <= 0">
        <span > Out Of Stock </span>
      </h3>
      <h3 v-else class="product__list-item-category-name">
          {{item.category.name}}
      </h3>
      <a :href="`/web/items/${item.id}`" class="product__list-item-name">
        {{ item.name }}
      </a>
      <ProductRatingComponent
        :item="item"
        class="mt-2"
      ></ProductRatingComponent>

      <h4 class="product__list-item-price">
        {{ parseFloat(item.price_with_tax).toFixed(2) }}
      </h4>
      <span class="product__list-item-currency">SR</span>
      <div>
        <h4 class="product__list-item-old-price">
          {{ parseFloat(item.price).toFixed(2) }}
        </h4>
        <span class="product__list-item-currency">SR</span>
      </div>
      <ToggleCartItemButtonComponent
        :item="item"
        class="mt-2"
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
        "https://cdn.salla.sa/zdJgBdtmaJ67xEmyMm6EbdUsS1z6lU050NFTHeqc.jpeg",
      ],
    };
  },
  computed: {
    itemImage() {
      // this.item.image
      //     ? this.item.image
      //     :
      return this.images[parseInt(Math.random() * 10) % 7];
    },
  },
};
</script>

<style>
</style>>