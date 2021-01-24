<template>
  <web-layout>
    <div class="container">
      <div class="breadcrumb-text breadcrumb-text-disable-last" >
        <a :href="page.url"  v-for="(page,index) in $page.breadcrumb" :key="index">{{page.title}}</a>
      </div>
       <div class="page__mt-2" v-if="subCategories.length > 0">
          <div class="product__search-options">
            <categories-pop :categories="subCategories" :show-subcategories="true"></categories-pop>
             <sorting-pop @updated="sortingUpdated"></sorting-pop>
            <!-- <switchAvailableButton @changed="switchAvailableQtyChanged"></switchAvailableButton> -->
          </div>
        </div>

      <div class="product__search-page">
        <div class="page__mt-2">
          <div class="product__search-options items-center">
            <switchAvailableButton
              class="items-center"
              @changed="switchAvailableQtyChanged"
            ></switchAvailableButton>
          </div>
        </div>

      </div>
      <div class="page__mt-2 mt-4">
        <h1
          v-if="$page.category.products_count > 0 "
          class="home__products-count"
        >
          {{ $page.$t.products.products_count }} ({{ $page.category.products_count }})
        </h1>

        <items-infinity-load :params="params" :forceUpdate="forceUpdate" :paramsUpdated="applyFilterSearch"></items-infinity-load>

      </div>
    </div>
  </web-layout>
</template>

<script>

import WebLayout from '../../../Layouts/WebAppLayout'
import ItemsInfinityLoad from '../../../components/Web/Item/ItemsInfinityLoad.vue'
import CategoriesPop from '../../../components/Web/Product/List/CategoriesPop.vue'
import switchAvailableButton from '../../../components/Web/Product/List/switchAvailableButton'
import SortingPop from '../../../components/Web/Product/List/SortingPop.vue'

export default {
  components: {
    WebLayout,
    SortingPop,
    switchAvailableButton,
    ItemsInfinityLoad,
    CategoriesPop
  },
  data () {
    return {
      params: { parent_category_id: this.$page.category.id },
      images: [],
      forceUpdate: 0
    }
  },
  methods: {
    applyFilterSearch () {},
    switchAvailableQtyChanged (e) {
      this.params.available_only = e ? 'yes' : 'no'
      this.forceUpdate++
    },
    sortingUpdated (object) {
      console.log(object)
      this.params.order_by = object.key
      this.params.order_direction = object.direction
      //       order_by: this.orderBy,
      // order_direction: this.orderDirection,
      this.forceUpdate++
      console.log(this.params)
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

  }
}
</script>

<style>

</style>>
