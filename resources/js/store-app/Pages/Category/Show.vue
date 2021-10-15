<template>
  <web-layout>
    <div class="container">
      <div class="breadcrumb-text breadcrumb-text-disable-last">
        <a
          :href="page.url"
          v-for="(page, index) in $page.props.breadcrumb"
          :key="index"
          >{{ page.title }}</a
        >
      </div>
      <div class="page__mt-2">
        <!--  -->
        <div class="product__search-options" v-if="items.length > 0">
          <filters-pop
            v-if="this.$page.props.subcategories.length === 0"
            :items="items"
            :search-name="$page.props.name"

            :category-id="$page.props.categoryId"
            @selectedAttributesHasBeenUpdated="selectedAttributesHasBeenUpdated"
          ></filters-pop>
          <CategoriesFilterPanelComponent
              :category="$page.props.category"
            :categories="this.$page.props.subcategories"
            v-else
            :show-subcategories="true"
          ></CategoriesFilterPanelComponent>

           <sorting-pop @updated="sortingUpdated"></sorting-pop>
        </div>
      </div>

      <div
        class="product__search-page"
        v-if="this.$page.props.category.products_count > 0"
      >
        <div class="page__mt-2">
          <div class="product__search-options items-center">
            <SwitchAvailableOnlyButtonComponent
              class="items-center"
              @changed="switchAvailableQtyChanged"
            ></SwitchAvailableOnlyButtonComponent>
          </div>
        </div>
      </div>
      <div class="page__mt-2 mt-4">
        <h1
          v-if="this.$page.props.category.products_count > 0"
          class="home__products-count"
        >
          {{ this.$page.props.$t.products.products_count }} ({{ totalItems }})
        </h1>
        <AutoReloadProductListComponent
          @listUpdated="listUpdated"
          :params="params"
          :forceUpdate="forceUpdate"
          :paramsUpdated="applyFilterSearch"
        ></AutoReloadProductListComponent>
      </div>
    </div>
  </web-layout>
</template>

<script>
import WebLayout from '../../Layouts/WebAppLayout'
import AutoReloadProductListComponent from '../../Components/Product/AutoReloadProductListComponent.vue'
import CategoriesFilterPanelComponent from '../../Components/Product/CategoriesFilterPanelComponent.vue'
import SwitchAvailableOnlyButtonComponent from '../../Components/Product/SwitchAvailableOnlyButtonComponent.vue'
import SortingPop from '../../Components/Product/SortingPop.vue'
import FiltersPop from '../../Components/Product/FiltersPop.vue'

export default {
  components: {
    WebLayout,
    SortingPop,
    SwitchAvailableOnlyButtonComponent,
    AutoReloadProductListComponent,
    CategoriesFilterPanelComponent,
    FiltersPop
  },
  data () {
    return {
      // params: { parent_category_id: this.$page.props.category.id },
      images: [],
      items: [],
      filterValues: [],
      totalItems: this.$page.props.category.products_count,
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
        search_via: this.$page.props.search_via,
        parent_category_id: this.$page.props.category.id,
        name: this.$page.props.name,
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
}
</script>

<style>
</style>>
