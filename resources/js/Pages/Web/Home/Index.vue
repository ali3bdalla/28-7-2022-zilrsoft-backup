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
        <h3 class="page__mt-2 home__products-count">
          {{ $page.$t.products.sorting_lastest }}
        </h3>

        <vue-horizontal
          snap="center"
          scroll
          :button-between="false"
          :button="true"
          ref="horizontal"
          style="direction: ltr"
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
                Clothings Hot
                <!-- <br />
                <span>Shoe Collection</span> <br />
                Accessories -->
              </h2>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="categories__hot__deal">
              <img
                src="https://cdn.zilrsoft.com/images/items/z9FbB6x5FOau8G2SCyU4w0HxL8YumO8FZOgPyc2j.png"
                alt=""
              />
              <div class="hot__deal__sticker">
                <span>Sale Of</span>
                <h5>$29.99</h5>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="categories__deal__countdown text-center">
              <span>{{ $page.$t.products.special_offer }}</span>
              <h2>Multi-pocket Chest Bag Black</h2>
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

    <!-- <section class="bg-white set-bg" style="margin-left: 0px !important">
        <div class="flex items-center justify-center">
          <div class="flex-1 flex items-center justify-center">
            <div data-v-4c5cb6b4="" data-v-3e0d31e2="" style="direction: ltr">
              <div
                data-v-4c5cb6b4=""
                class="text-center border-none animate__animated animate__bounceIn product__hor-list-item"
                data-v-3e0d31e2=""
              >
                <div class="product__list-item-image-container">
                  <a href="/web/items/3277"
                    ><img
                      src="https://cdn.zilrsoft.com/images/items/z9FbB6x5FOau8G2SCyU4w0HxL8YumO8FZOgPyc2j.png"
                      class="product__list-item-image"
                  /></a>
                </div>
                <div class="product__list-item-content">
                  <a
                    href="/web/items/3277"
                    class="product__list-item-name"
                    style="text-overflow: clip"
                  >
                    لابتوب اتش بي سيليرون 4 جيجا 14 بوصة 1 تيرا 14-cf2011nx
                  </a>
                </div>
                <h4 class="product__list-item-price">1850.00</h4>
            <span class="product__list-item-currency">ر.س</span>
              </div>
            </div>
          </div>
          <div class="flex-1 text-center">
            <div class="section-title">
              <h2>{{ $page.$t.products.special_offer }}</h2>
            </div>
            <div class="countdown-timer" id="countdown">
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

            <div>
              <h4 class="product__list-item-old-price">1610.00</h4>
              <span class="product__list-item-currency">ر.س</span>
            </div>

            <div class="product__list-item-cart-options">
              <button class="product__add-to-cart-button">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  class="product__add-to-cart-icon"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                  ></path>
                </svg>
                أضف الى السلة
              </button>
            </div>
          </div>
        </div>
      </section> -->

    <div class="container">
      <div class="mt-3" style="border-color: #d2e8ff !important">
        <!-- منتجات ذات صلة -->
        <h3 class="page__mt-2 home__products-count">
          {{ $page.$t.products.sorting_most_sellers }}
        </h3>

        <vue-horizontal
          snap="center"
          scroll
          :button-between="false"
          :button="true"
          ref="horizontal"
          style="direction: ltr"
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
import ItemsInfinityLoad from '../../../components/Web/Item/ItemsInfinityLoad.vue'
import ProductListItemComponent from './../../../components/Web/Product/ProductListItemComponent'

export default {
  name: 'Index',
  components: {
    WebLayout,
    SubategoryListItemComponent,
    Hero,
    ProductListItemComponent,
    ItemsInfinityLoad,
    VueHorizontal
  }
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
  color: #b7b7b7;
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
  letter-spacing: 2px;
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
