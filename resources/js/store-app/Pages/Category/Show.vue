<template>
  <web-layout>
    <div class="container">
      <div class="breadcrumb-text breadcrumb-text-disable-last">
        <a
          :href="page.url"
          v-for="(page, index) in $page.breadcrumb"
          :key="index"
          >{{ page.title }}</a
        >
      </div>
      <div class="page__mt-2">
        <!--  -->
        <div class="product__search-options" v-if="items.length > 0">
          <filters-pop
            v-if="this.$page.subcategories.length === 0"
            :items="items"
            :search-name="$page.name"

            :category-id="$page.categoryId"
            @selectedAttributesHasBeenUpdated="selectedAttributesHasBeenUpdated"
          ></filters-pop>
          <categories-pop
              :category="$page.category"
            :categories="this.$page.subcategories"
            v-else
            :show-subcategories="true"
          ></categories-pop>

           <sorting-pop @updated="sortingUpdated"></sorting-pop>
        </div>
      </div>

      <div
        class="product__search-page"
        v-if="this.$page.category.products_count > 0"
      >
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
          v-if="this.$page.category.products_count > 0"
          class="home__products-count"
        >
          {{ this.$page.$t.products.products_count }} ({{ totalItems }})
        </h1>

        <items-infinity-load
          @listUpdated="listUpdated"
          :params="params"
          :forceUpdate="forceUpdate"
          :paramsUpdated="applyFilterSearch"
        ></items-infinity-load>
      </div>
    </div>
  </web-layout>
</template>

<script>
import WebLayout from '../../Layouts/WebAppLayout'
import ItemsInfinityLoad from '../../../components/Web/Item/ItemsInfinityLoad.vue'
import CategoriesPop from '../../../components/Web/Product/List/CategoriesPop.vue'
import switchAvailableButton from '../../../components/Web/Product/List/switchAvailableButton'
import SortingPop from '../../../components/Web/Product/List/SortingPop.vue'
import FiltersPop from '../../../components/Web/Product/List/FiltersPop.vue'

export default {
  components: {
    WebLayout,
    SortingPop,
    switchAvailableButton,
    ItemsInfinityLoad,
    CategoriesPop,
    FiltersPop
  },
  data () {
    return {
      // params: { parent_category_id: this.$page.category.id },
      images: [],
      items: [],
      filterValues: [],
      totalItems: this.$page.category.products_count,
      forceUpdate: 0
    }
  },
  computed: {
    // filtersList () {
    //   return this.filters
    // },
    params () {
      return {
        available_only: this.available_only,
        search_via: this.$page.search_via,
        parent_category_id: this.$page.category.id,
        name: this.$page.name,
        order_by: this.orderBy,
        order_direction: this.orderDirection,
        filters_values: this.filterValues,
        forceUpdate: 0
      }
    }
  },
  methods: {
    listUpdated (re) {
      this.totalItems = re.total
      this.items = re.data
    },
    applyFilterSearch () {},
    switchAvailableQtyChanged (e) {
      this.params.available_only = e ? 'yes' : 'no'
      this.forceUpdate++
    },
    sortingUpdated (object) {
      this.params.order_by = object.key
      this.params.order_direction = object.direction
      this.forceUpdate++
    },
    selectedAttributesHasBeenUpdated (event) {
      this.filterValues = event.selectedValues
    }
  }
  // computed: {
  //   subCategories () {
  //     const subcategories = []
  //     for (const index in this.$page.subcategories) {
  //       const category = this.$page.subcategories[index]
  //       category.image = this.images[index % 7]

  //       subcategories.push(category)
  //     }
  //     return subcategories
  //   }
  // }
}
</script>

<style>
</style>>
