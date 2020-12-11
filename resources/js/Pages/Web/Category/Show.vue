<template>
  <web-layout>
    <div class="container bg-white shadow-lg">
      <div
        class="grid grid-cols-2 gap-1 p-2 my-5 md:grid-cols-3 lg:grid-cols-5 lg:gap-2"
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
          Products ({{ $page.category.products_count }})
          <a
            :href="`/web/categories/${$page.category.id}/products`"
            class="ml-2 text-sm text-blue-400"
            >show all..</a
          >
        </h1>

        <div
          class="grid grid-cols-2 gap-1 p-2 mt-3 mb-5 lg:grid-cols-4 lg:gap-4"
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