<template>
  <div
    :style="
      $page.props.active_locale === 'ar' ? 'direction:rtl' : 'direction:ltr'
    "
    class="product__list-item"
    style="border-color: #d2e8ff !important; border-width: 3px !important;"
  >
    <div class="product__list-item-image-container">
      <a :href="`/web/items/${item.slug}`"
        ><img
          :alt="item.locale_name"
          :src="$processedImageUrl(item.item_image_url, 200, 180)"
          class="product__list-item-image"
        />
      </a>
    </div>
    <div class="product__list-item-content">
      <h3
        v-if="item.available_qty <= 0 || item.category == null"
        class="product__list-item-category-name product__list-item-category-name__out-of-stock"
      >
        <span> {{ $page.props.$t.products.out_of_stock }} </span>
      </h3>
      <a
        v-else
        :href="`/web/items/search/results?category_id=${item.category_id}`"
      >
        <h3 class="product__list-item-category-name">
          {{ item.category ? item.category.locale_name : "" }}
        </h3>
      </a>

      <a :href="`/web/items/${item.slug}`" class="product__list-item-name">
        {{ productName }}
      </a>
      <h6 class="product__list-item-model-number">
        {{ $page.props.$t.products.model }} : {{ modelNumber }}
      </h6>
      <ProductRatingComponent
        :item="item"
        class="product__list-item-cart-options"
      ></ProductRatingComponent>
      <div>
        <h4 class="product__list-item-old-price">
          {{ parseFloat(item.online_offer_price).toFixed(2) }}
        </h4>
        <span class="product__list-item-currency">{{
          $page.props.$t.products.sar
        }}</span>
      </div>
      <p class="product__list-item-including-tax">
        {{ $page.props.$t.products.inc }}
      </p>
      <ToggleCartItemButtonComponent
        :item="item"
        class="product__list-item-cart-options"
      ></ToggleCartItemButtonComponent>
    </div>
  </div>
</template>

<script>
import ToggleCartItemButtonComponent from "../Cart/ToggleCartItemButtonComponent";
import ProductRatingComponent from "./ProductRatingComponent.vue";

export default {
  components: {
    ToggleCartItemButtonComponent,
    ProductRatingComponent,
  },
  props: ["item", "index"],

  computed: {
    productName() {
      const modelNumber = this.modelNumber;
      if (modelNumber != "") {
        return this.item.locale_name.replace(modelNumber, "");
      }

      return this.item.locale_name;
    },
    modelNumber() {
      if (this.item.filters) {
        const modelNumber = this.item.filters.find((p) => p.filter_id == 38);

        if (modelNumber && modelNumber.value) {
          return modelNumber.value.locale_name;
        }
      }

      return "";
    },
  },
};
</script>

<style></style>>
