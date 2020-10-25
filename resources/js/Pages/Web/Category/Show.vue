<template>
  <web-layout>
    <div class="container bg-white shadow-lg">
    <div
      class="grid  p-2 grid-cols-2  md:grid-cols-3 lg:grid-cols-5 gap-1 lg:gap-2 my-5 "
    >
    <!--bg-gray-200-->
      <a
        :href="`/web/categories/${category.id}`"
        v-for="category in subCategories"
        :key="category.id"
      >
        <SubategoryListItemComponent :category="category"></SubategoryListItemComponent>
      </a>
    </div>

    <div class="mt-10">
      <h1 class="text-xl text-center md:text-2xl font-bold text-gray-600 flex justify-center items-center">Products ({{$page.category.products_count}})  <a :href="`/web/categories/${$page.category.id}/products`" class="ml-2 text-sm text-blue-400">show all..</a></h1>

      <div
        class="grid p-2 grid-cols-2 md:grid-cols-4  gap-1 lg:gap-4 mb-5 mt-3"
      >
        <a
          :href="`/web/items/${item.id}`"
          v-for="(item,index) in items"
          :key="item.id"
        >
          <ProductListItemComponent :item="item" :index="index"></ProductListItemComponent>
        </a>
      </div>
    </div>
  </div>
  </web-layout>
</template>

<script>
import SubategoryListItemComponent from "./../../../components/Web/Category/SubategoryListItemComponent";
import ProductListItemComponent from "./../../../components/Web/Product/ProductListItemComponent";
import WebLayout from '../../../Layouts/WebAppLayout';

export default {
  components: {
    WebLayout,
    SubategoryListItemComponent,
    ProductListItemComponent
  },
  data() {
    return {
      images: [
        "https://images10.newegg.com/ProductImageCompressAll180/11-133-244-41.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/A8X5_131058525764839488GVB8iSbB5E.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/96-110-022-11.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/20-721-108-02.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/35-608-044_R01.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/75-100-554-02.jpg",
        "https://images10.newegg.com/ProductImageCompressAll180/11-133-244-41.jpg",
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