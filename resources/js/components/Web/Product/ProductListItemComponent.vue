<template>
  <div class=" product__list-item animate__animated  animate__bounceIn" style="border-color:#d2e8ff !important;border-width:3px !important">
    <div class="product__list-item-image-container">
      <a :href="`/web/items/${item.id}`"><img :src="item.item_image_url" class="product__list-item-image" /></a>
    </div>
    <div class="product__list-item-content">
      <h3 class="product__list-item-category-name product__list-item-category-name__out-of-stock" v-if="item.available_qty <= 0 || item.category == null">
        <span > {{$page.$t.products.out_of_stock}} </span>
      </h3>
      <h3 v-else class="product__list-item-category-name">
          {{item.category ? item.category.locale_name : ""}}
      </h3>
      
      <a :href="`/web/items/${item.id}`" class="product__list-item-name">
        {{ productName }}
      </a>
      <h6 class="product__list-item-model-number">
         {{$page.$t.products.model}} : {{modelNumber}}
      </h6>
      <ProductRatingComponent
        :item="item"
        class="product__list-item-cart-options"
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
        class="product__list-item-cart-options"
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
    productName(){
      let modelNumber = this.modelNumber;
      if(modelNumber != "")
        return this.item.locale_name.replace(modelNumber, "");

      return this.item.locale_name;

    },
    modelNumber(){
      if(this.item.filters)
      {
      let modelNumber = this.item.filters.find(p => p.filter_id == 38);

      if(modelNumber && modelNumber.value)
          return modelNumber.value.locale_name;
      }
      
      return ""
    },

  },
};
</script>

<style>
</style>>