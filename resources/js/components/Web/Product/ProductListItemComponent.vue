<template>
  <div class="mt-3 border border-gray-500 product__list-item md:mt-0 animate__animated  animate__bounceIn" style="border-color:#d2e8ff !important;border-width:3px !important">
    <div class="product__list-item-image-container">
      <img :src="item.item_image_url" class="product__list-item-image" />
    </div>
    <div class="p-2 mt-5 overflow-hidden">
      <h3 class="product__list-item-category-name product__list-item-category-name__out-of-stock" v-if="item.available_qty <= 0 || item.category == null">
        <span > {{$page.$t.products.out_of_stock}} </span>
      </h3>
      <h3 v-else class="product__list-item-category-name">
          {{item.category.locale_name}}
      </h3>
      
      <a :href="`/web/items/${item.id}`" class="product__list-item-name">
        {{ item.locale_name }}
      </a>
      <h6 class="truncate">
         {{$page.$t.products.model}} : {{modelNumber}}
      </h6>
      <ProductRatingComponent
        :item="item"
        class="mt-2"
      ></ProductRatingComponent>

      <h4 class="product__list-item-price">
        {{ parseFloat(item.online_price).toFixed(2) }}
      </h4>
      <span class="product__list-item-currency">{{$page.$t.products.sar}}</span>
      <div>
        <h4 class="product__list-item-old-price">
          {{ parseFloat(item.online_offer_price).toFixed(2) }}
        </h4>
        <span class="product__list-item-currency">{{$page.$t.products.sar}}</span>
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
  
  computed: {

    modelNumber(){
      if(this.item.filters)
      {
let modelNumber = this.item.filters.find(p => p.filter_id == 38);

      if(modelNumber)
          return modelNumber.value.locale_name;
      }
      
      return ""
    },

  },
};
</script>

<style>
</style>>