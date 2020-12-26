<template>
  <web-layout>
    <div class="container">
      <div class="">
        <div class=" product__show" style="margin-top: 2.5rem">
          <div class=" product__show__details">
            <div class="pt-3 bg-white border" style="border-color: #d2e8ff !important">
              <h1 class="product__show__details-name">
                {{ $page.item.locale_name }}
              </h1>
              <h1 class="product__show__details-model-number">
                {{ $page.$t.products.model }} : {{ modelNumber }}
              </h1>

              <!-- <ProductRatingComponent
              :item="$page.item"
              align=""
              class="mt-2"
          ></ProductRatingComponent> -->
              <div class="pb-2 mb-3">
                <h4 class="product__show__details-slash-price">
                  {{ parseFloat($page.item.price_with_tax).toFixed(2) }}
                </h4>
                <span class="product__show__details-currency">{{
                  $page.$t.products.sar
                }}</span>
                <div>
                  <h4 class="product__show__details-price">
                    {{ parseFloat($page.item.price).toFixed(2) }}
                  </h4>
                  <span class="product__show__details-currency">{{
                    $page.$t.products.sar
                  }}</span>
                </div>
                <!--            text-xl text-web-primary font-bold-->
                <div class="product__show__details-actions">
                  <ToggleCartItemButtonComponent
                    :item="$page.item"
                    class="mt-2"
                  ></ToggleCartItemButtonComponent>
                </div>
              </div>
            </div>
            <div
              class="hidden pt-0 mt-2 bg-white specification-table product__show__details-specification-table-container md:block"
            >
              <table class="product__show__details-specification-table">
                <tbody>
                  <tr class="product__show__details-specification-table-raw">
                    <td
                      colspan="2"
                      class="p-2 text-center text-black product__show__details-specification-table-title-cell product__show__details-specification-title"
                    >
                      {{ $page.$t.products.product_specifications }}
                    </td>
                  </tr>
                  <tr
                    v-for="filter in $page.item.filters"
                    :key="filter.id"
                    class="product__show__details-specification-table-raw"
                  >
                    <td
                      class="product__show__details-specification-table-title-cell"
                    >
                      {{ filter.filter.locale_name }}
                    </td>
                    <td
                      class="product__show__details-specification-table-value-cell"
                    >
                      {{ filter.value.locale_name }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="product product__show__image-container">
            <div class="p-3 border" style="border-color: #d2e8ff !important">
              <div>
                <img class="product__show__image" :src="activeImage" />
              </div>
              <div class="border product__show__images-grid " style="border-color: #d2e8ff !important">
                <div
                  v-for="image in $page.item.attachments"
                  :key="image.id"
                  class=""
                  @click="changeActiveImage(image.url)"
                >
                  <img
                    :class="{
                      'product__show__images-active-image':
                        activeImage === image.url,
                    }"
                    :src="image.url"
                    class="product__show__images-grid-image"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-3 ">
          <div
            class="visible specification-table product__show__details-specification-table-container md:hidden"
          >
            <table class=" product__show__details-specification-table">
              <tbody>
                <tr class="product__show__details-specification-table-raw">
                  <td
                    colspan="2"
                    class="p-2 text-center text-black product__show__details-specification-table-title-cell product__show__details-specification-title"
                  >
                    {{ $page.$t.products.product_specifications }}
                  </td>
                </tr>
                <tr
                  v-for="filter in $page.item.filters"
                  :key="filter.id"
                  class="product__show__details-specification-table-raw"
                >
                  <td
                    class="product__show__details-specification-table-title-cell"
                  >
                    {{ filter.filter.locale_name }}
                  </td>
                  <td
                    class="product__show__details-specification-table-value-cell"
                  >
                    {{ filter.value.locale_name }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div
            class="mt-3 specification-table product__show__details-specification-table-container md:mt-1"
          >
            <table class="bg-white product__show__details-specification-table">
              <tbody>
                <tr class="product__show__details-specification-table-raw">
                  <td
                    class="p-2 text-center text-black product__show__details-specification-table-title-cell product__show__details-specification-title"
                  >
                    {{ $page.$t.products.description }}
                  </td>
                </tr>

                <tr class="product__show__details-specification-table-raw">
                  <td
                    class="p-2 text-sm product__show__details-specification-table-value-cell"
                  >
                    {{ $page.item.locale_description }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="pt-3 mt-32 border-t-2 lg:mt-20" style="border-color: #d2e8ff !important">
          <h1 class="p-2 -mb-2 text-2xl">منتجات ذات صلة</h1>
          <div class=" products-grid">
            <ProductListItemComponent
              v-for="(item, index) in $page.relatedItems"
              :key="item.id"
              :index="index"
              :item="item"
            ></ProductListItemComponent>
          </div>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import ProductListItemComponent from "./../../../components/Web/Product/ProductListItemComponent";
import ProductRatingComponent from "./../../../components/Web/Product/ProductRatingComponent";
import ToggleCartItemButtonComponent from "./../../../components/Web/Cart/ToggleCartItemButtonComponent";
import ToggleFavoriteItemButtonComponent from "./../../../components/Web/Cart/ToggleFavoriteItemButtonComponent";
import WebLayout from "../../../Layouts/WebAppLayout";
import ImageZoomComponent from "./../../../components/Web/Product/ImageZoomComponent";
export default {
  data() {
    return {
      activeImage: this.$page.item.item_image_url,
    };
  },
  components: {
    WebLayout,
    ProductListItemComponent,
    ProductRatingComponent,
    ToggleCartItemButtonComponent,
    ToggleFavoriteItemButtonComponent,
    ImageZoomComponent,
  },
  computed: {
    modelNumber() {
      let modelNumber = this.$page.item.filters.find((p) => p.filter_id == 38);

      if (modelNumber) return modelNumber.value.locale_name;
      return "";
    },
  },
  methods: {
    changeActiveImage(url) {
      this.activeImage = url;
    },
  },
};
</script>

<style scoped>
.specification-table td {
  padding: 5px !important;
}
</style>