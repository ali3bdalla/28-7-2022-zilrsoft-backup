<template>
  <web-layout>
    <div class="container">
      <div class="breadcrumb-text breadcrumb-text-disable-last" >
        <a :href="page.url"  v-for="(page,index) in $page.breadcrumb" :key="index">{{page.title}}</a>
      </div>
      <vue-horizontal
        snap="center"
        :button="true"
        :button-between="false"
        ref="horizontal"
        style="direction: ltr"
        class="products-grid page__categories__list"
      >
        <div v-for="(category, index) in subCategories" :key="category.id" >
          <a
            :href="`/web/categories/${category.id}`"
            class="page__categories__list-item"
          >
            <div
              class="page__categories__name" 
            >
                {{ category.locale_name }}
            </div>
          </a>
        </div>
      </vue-horizontal>
      <div class="page__mt-2">
        <h1
          class="home__products-count"
        >
          {{ $page.$t.products.products_count }} ({{ $page.category.products_count }})
        </h1>

        <div class="products-grid page__-mt-2">
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