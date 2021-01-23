<template>
  <web-layout>
    <div class="container">
      <div class="breadcrumb-text breadcrumb-text-disable-last" >
        <a :href="page.url"  v-for="(page,index) in $page.breadcrumb" :key="index">{{page.title}}</a>
      </div>
       <div class="page__mt-2" v-if="subCategories.length > 0">
          <div class="product__search-options">
            <categories-pop :categories="subCategories" :show-subcategories="true"></categories-pop>
            <!-- <sorting-pop @updated="sortingUpdated"></sorting-pop> -->
          </div>
        </div>
      <!-- <vue-horizontal
        snap="left"
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
      </vue-horizontal> -->
      <div class="page__mt-2">
        <h1
          v-if="$page.category.products_count > 0 "
          class="home__products-count"
        >
          {{ $page.$t.products.products_count }} ({{ $page.category.products_count }})
        </h1>

        <items-infinity-load :params="{parent_category_id:$page.category.id}"></items-infinity-load>

      </div>
    </div>
  </web-layout>
</template>

<script>
// import SubategoryListItemComponent from './../../../components/Web/Category/SubategoryListItemComponent'
// import ProductListItemComponent from './../../../components/Web/Product/ProductListItemComponent'
import WebLayout from '../../../Layouts/WebAppLayout'
// import VueHorizontal from 'vue-horizontal'
import ItemsInfinityLoad from '../../../components/Web/Item/ItemsInfinityLoad.vue'
import CategoriesPop from '../../../components/Web/Product/List/CategoriesPop.vue'

export default {
  components: {
    WebLayout,
    // SubategoryListItemComponent,
    // ProductListItemComponent,
    // VueHorizontal,
    ItemsInfinityLoad,
    CategoriesPop
  },
  data () {
    return {
      images: []
    }
  },
  computed: {
    subCategories () {
      const subcategories = []
      for (const index in this.$page.subcategories) {
        const category = this.$page.subcategories[index]
        category.image = this.images[index % 7]

        subcategories.push(category)
      }
      return subcategories
    }

    // items() {
    //   let items = [];
    //   for (const index in this.$page.items) {
    //     let item = this.$page.items[index];
    //     item.image = this.images[index % 7];
    //     items.push(item);
    //   }
    //   return items;
    // },
  }
}
</script>

<style>

</style>>
