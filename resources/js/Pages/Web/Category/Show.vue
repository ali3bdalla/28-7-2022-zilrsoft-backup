<template>
  <web-layout>
    <div class="container">
      <div class="breadcrumb-text breadcrumb-text-disable-last" >
        <!-- <a href="#"><i class="fa fa-home"></i> Home</a> -->
        <a :href="page.url"  v-for="(page,index) in $page.breadcrumb" :key="index">{{page.title}}</a>

      </div>
      <div
        v-if="$page.level == 'main'"
        class="grid-cols-1 md:grid-cols-2 products-grid"
      >
        <!--bg-gray-200-->
        <a
          :href="`/web/categories/${category.id}`"
          v-for="category in subCategories"
          :key="category.id"
        >
          <SubategoryListItemComponent
            :category="category"
          ></SubategoryListItemComponent>
        </a>
      </div>
      <vue-horizontal
        v-else-if="subCategories.length > 0"
        snap="center"
        :button="true"
        :button-between="false"
        ref="horizontal"
        style="direction: ltr"
        class="products-grid mb-2  rounded"
      >
      <!-- animate__animated  animate__fadeInRightBig -->
      <!-- class=" " style=" animation-duration: 8s;" -->
        <div v-for="(category, index) in subCategories" :key="category.id" >
          <a
            :href="`/web/categories/${category.id}`"
            class="text-gray-800 hover:text-gray-900"
          >
          <!-- px-2 mx-1  my-2 -->
            <div
              class=" py-2 px-3 mx-2 border bg-white rounded-full " style="border-color:rgb(249, 249, 249)"
            >
              <div class="font-bold text-center">
                {{ category.locale_name }}
              </div>
            </div>
          </a>
        </div>
      </vue-horizontal>
      <div class="mt-2">
        <h1
          class="flex items-center justify-center text-xl font-bold text-center text-gray-600 md:text-2xl"
        >
          {{ $page.$t.products.products_count }} ({{ $page.category.products_count }})
        </h1>

        <div class="products-grid -mt-2">
          <ProductListItemComponent
            v-for="(item, index) in items"
            :key="item.id"
            :item="item"
            :index="index"
          ></ProductListItemComponent>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import SubategoryListItemComponent from "./../../../components/Web/Category/SubategoryListItemComponent";
import ProductListItemComponent from "./../../../components/Web/Product/ProductListItemComponent";
import WebLayout from "../../../Layouts/WebAppLayout";
import VueHorizontal from "vue-horizontal";

export default {
  components: {
    WebLayout,
    SubategoryListItemComponent,
    ProductListItemComponent,
    VueHorizontal,
  },
  data() {
    return {
      images: [],
    };
  },
  computed: {
    subCategories() {
      let subcategories = [];
      for (const index in this.$page.subcategories) {
        let category = this.$page.subcategories[index];
        category.image = this.images[index % 7];

        subcategories.push(category);
      }
      return subcategories;
    },

    items() {
      let items = [];
      for (const index in this.$page.items) {
        let item = this.$page.items[index];
        item.image = this.images[index % 7];
        items.push(item);
      }
      return items;
    },
  },
};
</script>

<style>

</style>>