<template>
  <web-layout>
    <div class="container">
      <div class="breadcrumb-text">
        <a
            v-for="(page, index) in $page.props.breadcrumb"
            :key="index"
            :href="page.url"
        >{{ page.title }}</a
        >
      </div>

      <div class="">
        <div class="product__show" style="margin-top: 2.5rem">
          <div class="product__show__details">
            <div
                class="product__show__details-bg"
                style="border-color: #d2e8ff !important"
            >
              <h1 class="product__show__details-name">
                {{ productName }}
              </h1>
              <h1 class="product__show__details-model-number">
                {{ $page.props.$t.products.model }} : {{ modelNumber }}
              </h1>
              <div class="page__mt-2">
                <div>
                  <h4 class="product__show__details-price">
                    {{ parseFloat($page.props.item.online_offer_price).toFixed(2) }}
                  </h4>
                  <span class="product__show__details-currency">{{
                    $page.props.$t.products.sar
                  }}</span>
                </div>
                <p class="product__list-item-including-tax">
                  {{ $page.props.$t.products.inc }}
                </p>
                <!--            text-xl text-web-primary font-bold-->
                <div class="product__show__details-actions">
                  <ToggleCartItemButtonComponent
                      :item="$page.props.item"
                      class="page__mt-2"
                  ></ToggleCartItemButtonComponent>

                  <div class="text-center flex items-center justify-center mt-2 md:hidden">
                    <a :href="`whatsapp://send?text=${$page.props.item.locale_name}+${$page.props.itemUrl}`"
                       data-action="share/whatsapp/share">
                      <img class="w-8 h-8" src="https://pcdn.sharethis.com/wp-content/uploads/2017/05/WhatsApp.png"/>
                    </a>
                  </div>

                </div>
              </div>
            </div>
            <div
                class="specification-table product__show__details-specification-table-container product__show__details-specification-table-container__mobile"
            >
              <table class="product__show__details-specification-table">
                <tbody>
                <tr class="product__show__details-specification-table-raw">
                  <td
                      class="p-2 text-center text-black product__show__details-specification-table-title-cell product__show__details-specification-title"
                      colspan="2"
                  >
                    {{ $page.props.$t.products.product_specifications }}
                  </td>
                </tr>
                <tr
                    v-for="filter in $page.props.item.filters"
                    :key="filter.id"
                    class="product__show__details-specification-table-raw"
                >
                  <td
                      class="product__show__details-specification-table-title-cell"
                  >
                    {{ filter.filter ? filter.filter.locale_name : "" }}
                  </td>
                  <td
                      class="product__show__details-specification-table-value-cell"
                  >
                    {{ filter.value ? filter.value.locale_name : "" }}
                  </td>
                </tr>

                <tr class="product__show__details-specification-table-raw">
                  <td
                      class="product__show__details-specification-table-title-cell"
                  >
                    {{ $page.props.$t.products.barcode }}
                  </td>
                  <td
                      class="product__show__details-specification-table-value-cell"
                  >
                    {{ $page.props.item.barcode }}
                  </td>
                </tr>
                <tr
                    v-if="$page.props.item.warranty_subscription"
                    class="product__show__details-specification-table-raw"
                >
                  <td
                      class="product__show__details-specification-table-title-cell"
                  >
                    {{ $page.props.$t.products.warranty_subscription }}
                  </td>
                  <td
                      class="product__show__details-specification-table-value-cell"
                  >
                    {{ $page.props.item.warranty_subscription.locale_name }}
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="product product__show__image-container">
            <div class="p-3 border" style="border-color: #d2e8ff !important">
              <div
                  :height="326 * 5"
                  :style="{ minHeight: 326 * 5 }"
                  class="flex items-center justify-center"
                  style="
                  min-height: 469px !important;
                  max-height: 469px !important;
                  height: 469px !important;
                "
              >
                <img
                    :height="469"
                    :src="$processedImageUrl(activeImage, 1000, 1000)"
                    class="product__show__image"
                />
              </div>
              <div
                  class="border product__show__images-grid"
                  style="
                  border-color: #d2e8ff !important;
                  min-height: 112px !important;
                  max-height: 112px !important;
                  height: 112px !important;
                "
              >
                <div
                    v-for="image in $page.props.item.attachments"
                    :key="image.id"
                    class=""
                    @click="changeActiveImage(image.url)"
                >
                  <img
                      :class="{
                      'product__show__images-active-image':
                        activeImage === image.url,
                    }"
                      :src="$processedImageUrl(image.url, 70 * 2, 92 * 2)"
                      class="product__show__images-grid-image"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="page__mt-2">
          <div
              class="specification-table product__show__details-specification-table-container product__show__details-specification-table-container__pc mb-3"
          >
            <table class="product__show__details-specification-table">
              <tbody>
              <tr class="product__show__details-specification-table-raw">
                <td
                    class="product__show__details-specification-table-title-cell product__show__details-specification-title"
                    colspan="2"
                >
                  {{ $page.props.$t.products.product_specifications }}
                </td>
              </tr>
              <tr
                  v-for="filter in $page.props.item.filters"
                  :key="filter.id"
                  class="product__show__details-specification-table-raw"
              >
                <td
                    class="product__show__details-specification-table-title-cell"
                >
                  {{ filter.filter ? filter.filter.locale_name : "" }}
                </td>
                <td
                    class="product__show__details-specification-table-value-cell"
                >
                  {{ filter.value ? filter.value.locale_name : "" }}
                </td>
              </tr>
              <tr class="product__show__details-specification-table-raw">
                <td
                    class="product__show__details-specification-table-title-cell"
                >
                  {{ $page.props.$t.products.barcode }}
                </td>
                <td
                    class="product__show__details-specification-table-value-cell"
                >
                  {{ $page.props.item.barcode }}
                </td>
              </tr>

              <tr
                  v-if="$page.props.item.warranty_subscription"
                  class="product__show__details-specification-table-raw"
              >
                <td
                    class="product__show__details-specification-table-title-cell"
                >
                  {{ $page.props.$t.products.warranty_subscription }}
                </td>
                <td
                    class="product__show__details-specification-table-value-cell"
                >
                  {{ $page.props.item.warranty_subscription.locale_name }}
                </td>
              </tr>
              </tbody>
            </table>
          </div>
          <div
              class="specification-table product__show__details-specification-table-container"
          >
            <table class="product__show__details-specification-table">
              <tbody>
              <tr class="product__show__details-specification-table-raw">
                <td
                    class="product__show__details-specification-table-title-cell product__show__details-specification-title"
                >
                  {{ $page.props.$t.products.description }}
                </td>
              </tr>

              <tr class="product__show__details-specification-table-raw">
                <td
                    class="product__show__details-specification-table-value-cell"
                >
                  {{ $page.props.item.locale_description }}
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="mt-2 bg-white p-3">
          <div class="filter-widget pb-0 mb-0">
            <h4 class="fw-title">{{ $page.props.$t.products.tags }}</h4>
            <div class="fw-tags">
              <inertia-link
                  v-for="tag in $page.props.item.tags"
                  :key="tag.id"
                  :href="'/web/items?search_via=tag&&name=' + tag.tag"
                  style="background-color: rgb(249, 249, 249)"
              >{{ tag.tag }}
              </inertia-link
              >
            </div>
          </div>
        </div>
        <div
            class="product__related-products-area"
            style="border-color: #d2e8ff !important"
        >
          <!-- منتجات ذات صلة -->
          <h3
              class="page__mt-2 home__products-count"
              style="
              background: #86bbf7;
              padding-top: 5px;
              padding-bottom: 5px;
              font-size: 22px;
              color: white;
              border-radius: 5px;
            "
          >
            {{ $page.props.$t.products.related_products }}
          </h3>

          <vue-horizontal
              ref="horizontal"
              :button="true"
              :button-between="false"
              class="products-grid"
              scroll
              snap="center"
              style="direction: ltr"
          >
            <div
                v-for="(item, index) in $page.props.relatedItems"
                :key="item.id"
                style="direction: ltr"
            >
              <ProductListItemComponent
                  :index="index"
                  :item="item"
                  class="product__hor-list-item"
              ></ProductListItemComponent>
            </div>
          </vue-horizontal>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import VueHorizontal from 'vue-horizontal'
import ProductListItemComponent from './../../Components/Product/ProductListItemComponent.vue'
import ToggleCartItemButtonComponent from './../../Components/Cart/ToggleCartItemButtonComponent.vue'
import WebLayout from '../../Layouts/WebAppLayout'

export default {
  data () {
    return {
      activeImage: this.$page.props.item.photo
    }
  },
  components: {
    WebLayout,
    ProductListItemComponent,
    ToggleCartItemButtonComponent,
    VueHorizontal
  },
  computed: {
    productName () {
      const modelNumber = this.modelNumber
      if (modelNumber != '') {
        return this.$page.props.item.locale_name.replace(modelNumber, '')
      }

      return this.$page.props.item.locale_name
    },
    modelNumber () {
      const modelNumber = this.$page.props.item.filters.find(
          (p) => parseInt(p.filter_id) == 38
      )

      if (modelNumber && modelNumber.value) {
        return modelNumber.value.locale_name
      }
      return ''
    }
  },
  created () {
    this.activeImage = this.$page.props.item.photo
    console.log(this.activeImage)
    document.title = this.$page.props.item.locale_name
  },
  methods: {
    changeActiveImage (url) {
      this.activeImage = url
    }
  }
}
</script>

<style scoped>
.specification-table td {
  padding: 5px !important;
}
</style>
