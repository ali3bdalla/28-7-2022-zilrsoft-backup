<template>
  <div
    class="product__list-item"
    style="border-color: #d2e8ff !important; border-width: 3px !important"
  >
    <div class="product__list-item-image-container">
      <a :href="`${item.view_url}`"><img
          :src="$processedImageUrl(getUrl,334,250,false,false)"
          class="product__list-item-image"
        /></a>
    </div>
    <div class="product__list-item-content">
      <h3
        class="product__list-item-category-name product__list-item-category-name__out-of-stock"
        v-if="item.available_qty <= 0 || item.category_name == null"
      >
        <span> {{ $page.props.$t.products.out_of_stock }} </span>
      </h3>
      <a
        v-else
        :href="`/web/items/search/results?category_id=${item.category_id}`"
      >
        <h3 class="product__list-item-category-name">
          {{ getCategoryName }}
        </h3>
      </a>
      <a
        :href="`/web/items/${item.slug}`"
        class="product__list-item-name"
      >
        {{ productName }}

      </a>
      <h6 class="product__list-item-model-number">
        {{ $page.props.$t.products.model }} : {{ item.model_number }}
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
import ToggleCartItemButtonComponent from '../Cart/ToggleCartItemButtonComponent.vue'
import ProductRatingComponent from './ProductRatingComponent.vue'
export default {
  components: { ToggleCartItemButtonComponent, ProductRatingComponent },
  props: {
    item: {
      required: true,
      type: Object
    },
    index: {
      required: true,
      type: Number
    }
  },
  computed: {
    productName () {
      const modelNumber = this.item.model_number
      const name = this.$page.props.active_locale === 'ar' ? this.item.ar_name : this.item.name
      if (modelNumber !== '') {
        return name.replace(modelNumber, '')
      }
      return name
    },
    getCategoryName () {
      if (this.$page.props.active_locale === 'en') return this.item.category_name
      return this.item.category_ar_name
    },
    getUrl () {
      return `local:///com.zilrsoft/storage/app/public/${this.item.photo}`
    }
  }
}
</script>

<style>
</style>>
