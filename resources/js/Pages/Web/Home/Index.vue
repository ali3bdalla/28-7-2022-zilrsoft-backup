<template>
  <web-layout>
    <div class="container">
      <hero class=""></hero>
      <div class="home__categories-list">
        <a
          class="home__categories-list-item"
          :href="`/web/categories/${category.id}`"
          v-for="(category, index) in $page.main_categories"
          :key="index"
        >
          <SubategoryListItemComponent
            :category="category"
          ></SubategoryListItemComponent>
        </a>
      </div>

      <!-- <div class="home__products-area">
        <h1 class="home__products-count">
          {{ $page.$t.products.all_products_count }} ({{ $page.products_count }})
        </h1>
        <ItemsInfinityLoad :params="{}"></ItemsInfinityLoad>
      </div> -->
      <div class="">
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
          {{ $page.$t.products.new_arrival }}
        </h3>

        <vue-horizontal
          snap="center"
          scroll
          :button-between="false"
          :button="true"
          ref="horizontal"
          style="direction: ltr; margin-top: -11px"
          class="products-grid"
        >
          <div
            v-for="(item, index) in $page.latest"
            :key="item.id"
            style="direction: ltr"
          >
            <ProductListItemComponent
              class="product__hor-list-item"
              :index="index"
              :item="item"
            ></ProductListItemComponent>
          </div>
        </vue-horizontal>
      </div>
    </div>
    <section class="categories spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-3">
            <div class="categories__text flex items-center justify-center">
              <h2 class="flex items-center justify-center h-full">
                {{ $page.$t.products.agent_warrnaty }}
                <!-- <br />
                <span>Shoe Collection</span> <br />
                Accessories -->
              </h2>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="categories__hot__deal">
              <img
                :src="$processedImageUrl(offerItem.item_image_url, 350, 260)"
                alt=""
              />
              <div
                class="hot__deal__sticker flex flex-col items-center justify-center"
              >
                <h5 style="direction: rtl !important; margin-bottom: 0px">
                  {{ offerItem.online_offer_price }}
                </h5>
                <span style="margin-bottom: 0px">
                  {{ $page.$t.products.sar }}</span
                >
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="categories__deal__countdown text-center">
              <span class="title">{{ $page.$t.products.special_offer }}</span>
              <h2>{{ productName }}</h2>
              <div class="categories__deal__countdown__timer" id="countdown">
                <div class="cd-item">
                  <span>0</span>
                  <p>{{ $page.$t.products.time_days }}</p>
                </div>
                <div class="cd-item">
                  <span>0</span>
                  <p>{{ $page.$t.products.time_hrs }}</p>
                </div>
                <div class="cd-item">
                  <span>0</span>
                  <p>{{ $page.$t.products.time_mins }}</p>
                </div>
                <div class="cd-item">
                  <span>0</span>
                  <p>{{ $page.$t.products.time_secs }}</p>
                </div>
              </div>
              <!-- <a href="#" class="primary-btn">Shop now</a> -->
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="container">
      <div class="mt-3" style="border-color: #d2e8ff !important">
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
          {{ $page.$t.products.sorting_most_sellers }}
        </h3>

        <vue-horizontal
          snap="center"
          scroll
          :button-between="false"
          :button="true"
          ref="horizontal"
          style="direction: ltr; margin-top: -11px"
          class="products-grid"
        >
          <div
            v-for="(item, index) in $page.heigest_price"
            :key="item.id"
            style="direction: ltr"
          >
            <ProductListItemComponent
              class="product__hor-list-item"
              :index="index"
              :item="item"
            ></ProductListItemComponent>
          </div>
        </vue-horizontal>
      </div>
    </div>
  </web-layout>
</template>

<script>
import VueHorizontal from 'vue-horizontal'
import WebLayout from '../../../Layouts/WebAppLayout'
import SubategoryListItemComponent from './../../../components/Web/Category/SubategoryListItemComponent'
import Hero from '../../../components/Web/Page/Hero.vue'
import ProductListItemComponent from './../../../components/Web/Product/ProductListItemComponent'

export default {
  name: 'Index',
  components: {
    WebLayout,
    SubategoryListItemComponent,
    Hero,
    ProductListItemComponent,
    VueHorizontal
  },
  computed: {
    offerItem () {
      return this.$page.offer_item ?? {}
    },
    productName () {
      return this.$page.active_locale === 'en'
        ? this.offerItem.name
        : this.offerItem.ar_name
    }
  },
  methods: {}
}
</script>

<style scoped>
.categories {
  background: #f3f2ee;
  overflow: hidden;
  padding-top: 150px;
  padding-bottom: 125px;
  direction: ltr !important;
}

.categories__text {
  padding-top: 40px;
  position: relative;
  z-index: 1;
}

.categories__text:before {
  position: absolute;
  left: -485px;
  top: 0;
  height: 300px;
  width: 600px;
  background: #ffffff;
  z-index: -1;
  content: "";
}

.categories__text h2 {
  color: #192a56;
  line-height: 72px;
  font-size: 34px;
}

.categories__text h2 span {
  font-weight: 700;
  color: #111111;
}

.categories__hot__deal {
  position: relative;
  z-index: 5;
}

.categories__hot__deal img {
  min-width: 100%;
}

.hot__deal__sticker {
  height: 100px;
  width: 100px;
  background: #111111;
  border-radius: 50%;
  padding-top: 22px;
  text-align: center;
  position: absolute;
  right: 0;
  top: -36px;
}

.hot__deal__sticker span {
  display: block;
  font-size: 15px;
  color: #ffffff;
  margin-bottom: 4px;
}

.categories__deal__countdown .title {
  color: #192a56;
  font-size: 47px;
  text-shadow: 1px 1px 3px white;
  font-weight: bold;
  text-transform: uppercase;
}

.hot__deal__sticker h5 {
  color: #ffffff;
  font-size: 20px;
  font-weight: 700;
}

.categories__deal__countdown span {
  color: #e53637;
  font-size: 14px;
  font-weight: 700;
  text-transform: uppercase;

  /* letter-spacing: 2px; */
  margin-bottom: 15px;
  display: block;
}

.categories__deal__countdown h2 {
  color: #111111;
  font-weight: 700;
  line-height: 46px;
  margin-bottom: 25px;
}

.categories__deal__countdown .categories__deal__countdown__timer {
  margin-bottom: 20px;
  overflow: hidden;
  margin-left: -30px;
}

.categories__deal__countdown .categories__deal__countdown__timer .cd-item {
  width: 25%;
  float: left;
  margin-bottom: 25px;
  text-align: center;
  position: relative;
}

.categories__deal__countdown
  .categories__deal__countdown__timer
  .cd-item:after {
  position: absolute;
  right: 0;
  top: 7px;
  content: ":";
  font-size: 24px;
  font-weight: 700;
  color: #3d3d3d;
}

.categories__deal__countdown
  .categories__deal__countdown__timer
  .cd-item:last-child:after {
  display: none;
}

.categories__deal__countdown .categories__deal__countdown__timer .cd-item span {
  color: #111111;
  font-weight: 700;
  display: block;
  font-size: 36px;
}

.categories__deal__countdown .categories__deal__countdown__timer .cd-item p {
  margin-bottom: 0;
}
</style>
