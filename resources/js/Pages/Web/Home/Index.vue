<template>
  <web-layout>
    <!-- <SideBarComponent :items='$page.items'></SideBarComponent> -->
    <div class="container">
      <div
        class="grid w-full grid-cols-1 gap-3 my-5 mb-10 md:grid-cols-2"
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
          {{$page.$t.products.products}} 
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

export default {
  name: "Index",
  components: {
    WebLayout,
    SideBarComponent,
    SubategoryListItemComponent,
    ProductListItemComponent
  },

};
</script>

<style scoped>
</style>