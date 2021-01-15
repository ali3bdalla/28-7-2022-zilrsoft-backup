<template>
  <web-layout>
    
    <div class="container">
      <hero class=""></hero>
      <div
        class="home__categories-list"
      >
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

        <div class="home__products-area">
       <h1
          class="home__products-count"
        >
          {{ $page.$t.products.products_count }} ({{ $page.products_count }})
        </h1>
        <div

          class="products-grid" 
        >
          <ProductListItemComponent
            v-for="(item, index) in $page.items"
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
import SideBarComponent from "./../../../components/Web/Page/SideBarComponent";
import WebLayout from "../../../Layouts/WebAppLayout";
import SubategoryListItemComponent from "./../../../components/Web/Category/SubategoryListItemComponent";
import ProductListItemComponent from '../../../components/Web/Product/ProductListItemComponent.vue';
import Hero from '../../../components/Web/Page/Hero.vue';

export default {
  name: "Index",
  components: {
    WebLayout,
    SideBarComponent,
    SubategoryListItemComponent,
    ProductListItemComponent,
    Hero
  },

};
</script>

<style scoped>
</style>