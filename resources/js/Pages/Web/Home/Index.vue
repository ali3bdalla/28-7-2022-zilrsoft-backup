<template>
  <web-layout>
    <!-- <SideBarComponent :items='$page.items'></SideBarComponent> -->
    
    <div class="container">
      <hero class="mt-2"></hero>
      <div
        class="grid w-full grid-cols-1 gap-3 mt-3 mb-10 md:grid-cols-2"
      >
        <a
          class="h-32 md:h-48" 
          :href="`/web/categories/${category.id}`"
          v-for="(category, index) in $page.main_categories"
          :key="index"
        >
        <SubategoryListItemComponent
            :category="category"
          ></SubategoryListItemComponent>

        </a>
      </div>

        <div class="mt-10">
       <h1
          class="flex items-center justify-center text-xl font-bold text-center text-gray-600 md:text-2xl"
        >
          {{ $page.$t.products.products_count }} ({{ $page.products_count }})
          <!-- &nbsp; <a
            :href="`/web/items?category_id=${$page.category.id}`"
            class="ml-2 text-sm text-blue-400"
            >{{$page.$t.products.show_all}}</a
          > -->
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