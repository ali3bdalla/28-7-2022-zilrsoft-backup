<template>
  <web-layout>
    <div  class="container shadow-lg" >
      <div
        class="grid-cols-1 md:grid-cols-2 products-grid "
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

      <div class="mt-10">
        <h1
          class="flex items-center justify-center text-xl font-bold text-center text-gray-600 md:text-2xl"
        >
          {{$page.$t.products.products}} ({{ $page.category.products_count }})
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

export default {
  components: {
    WebLayout,
    SubategoryListItemComponent,
    ProductListItemComponent,
  },
  data() {
    return {
      images: [

      ],
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