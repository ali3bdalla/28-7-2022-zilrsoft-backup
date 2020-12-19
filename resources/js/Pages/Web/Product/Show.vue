<template>
  <web-layout class="">
    <div class="product">
      <div class="container page__section product__show ">
        <div class="product__show__image-container ">
          <div>
            <ImageZoomComponent class="product__show__image"  :src="$page.item.item_image_url" :src-large="$page.item.item_image_url"></ImageZoomComponent>
<!--            <img-->
<!--                class="product__show__image"-->
<!--                :src="$page.item.item_image_url"-->
<!--            />-->
          </div>
          <div class="product__show__images-grid">
            <div v-for="image in $page.item.attachments" :key="image.id" class="">
              <img :src="image.url" class="product__show__images-grid-image "/>
            </div>
          </div>
        </div>
        <div
            class="product__show__details"
        >
          <h1 class="product__show__details-name">
            {{ $page.item.locale_name }}
          </h1>
          <h1 class="product__show__details-slash-price">
            {{ $page.item.locale_name }}
          </h1>
          <!-- <ProductRatingComponent
              :item="$page.item"
              align=""
              class="mt-2"
          ></ProductRatingComponent> -->
          <div class="pb-2 mb-3">
            <h4
                class="product__show__details-slash-price"
            >
              {{ parseFloat($page.item.price_with_tax).toFixed(2) }}
            </h4>
            <span class="product__show__details-currency">{{$page.$t.products.sar}}</span>
            <div>
              <h4
                  class="product__show__details-price"
              >
                {{ parseFloat($page.item.price).toFixed(2) }}
              </h4>
              <span class="product__show__details-currency">{{$page.$t.products.sar}}</span>
            </div>
            <!--            text-xl text-web-primary font-bold-->
            <div class="product__show__details-actions">
              <ToggleCartItemButtonComponent
                  :item="$page.item"
                  class="mt-2"
              ></ToggleCartItemButtonComponent>
            </div>
          </div>
          <h2 class="product__show__details-specification-title">Product Specification</h2>
          <div class=" specification-table product__show__details-specification-table-container">
            <table class="product__show__details-specification-table">
              <tbody>
              <tr
                  v-for="filter in $page.item.filters"
                  :key="filter.id"
                  class="product__show__details-specification-table-raw"
              >
                <td
                    class="product__show__details-specification-table-title-cell">
                  {{ filter.filter.locale_name }}
                </td>
                <td class="product__show__details-specification-table-value-cell">
                  {{ filter.value.locale_name }}
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="mt-32 page__section">
        <div
            class="products-grid container"
        >
          <ProductListItemComponent
              v-for="(item, index) in $page.relatedItems"
              :key="item.id"
              :index="index"
              :item="item"
          ></ProductListItemComponent>
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
import ImageZoomComponent from './../../../components/Web/Product/ImageZoomComponent'
export default {
  components: {
    WebLayout,
    ProductListItemComponent,
    ProductRatingComponent,
    ToggleCartItemButtonComponent,
    ToggleFavoriteItemButtonComponent,
    ImageZoomComponent
  },
  computed: {
    relatedImages() {
      return [
        "https://c1.neweggimages.com/ProductImageCompressAll1280/34-155-519-V80.jpg",
        "https://c1.neweggimages.com/ProductImageCompressAll1280/34-155-519-V12.jpg",
        "https://c1.neweggimages.com/ProductImageCompressAll1280/34-155-519-V06.jpg",
        "https://c1.neweggimages.com/ProductImageCompressAll1280/34-155-519-V07.jpg",
      ];
    },
  },
};
</script>

<style scoped>
.specification-table td {
  padding: 0px;
}
</style>