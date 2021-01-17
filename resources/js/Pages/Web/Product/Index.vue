<template>
  <web-layout>
    <div class="container">
      <vue-horizontal
        v-if="!$page.categoryId"
        snap="center"
        :button="true"
        :button-between="false"
        ref="horizontal"
        style="direction: ltr"
        class="products-grid page__categories__list"
      >
        <div v-for="(category, index) in $page.categories" :key="category.id">
          <a
            :href="`/web/items?category_id=${category.id}&&name=${$page.name}`"
            class="page__categories__list-item"
          >
            <div class="page__categories__name">
               <span class="">{{ $page.name }}</span> {{ $page.$t.products.in }} -
          <span class="">{{ category.locale_name }}</span>
              <!-- {{ category.locale_name }} -->
            </div>
          </a>
        </div>
      </vue-horizontal>

      <div class="product__search-page">
        <div class="page__mt-2">
          <div class="product__search-options">
            <filters-pop
              v-if="$page.categoryId"
              :items="items"
              :search-name="$page.name"
              @subCategoryHasBeenUpdated="subCategoryHasBeenUpdated"
              :category-id="$page.categoryId"
              @selectedAttributesHasBeenUpdated="
                selectedAttributesHasBeenUpdated
              "
              @priceFilterRangeHasBeenUpdated="priceFilterRangeHasBeenUpdated"
            ></filters-pop>
            <sorting-pop @updated="sortingUpdated"></sorting-pop>
          </div>
        </div>

        <div class="">
          <items-infinity-load
            @listUpdated="listUpdated"
            :params="params"
            :paramsUpdated="applyFilterSearch"
          ></items-infinity-load>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import ItemsInfinityLoad from "../../../components/Web/Item/ItemsInfinityLoad.vue";
import FiltersPop from "../../../components/Web/Product/List/FiltersPop.vue";
import SortingPop from "../../../components/Web/Product/List/SortingPop.vue";
import WebLayout from "../../../Layouts/WebAppLayout";
import ProductListItemComponent from "./../../../components/Web/Product/ProductListItemComponent";
import VueHorizontal from "vue-horizontal";

export default {
  components: {
    WebLayout,
    ProductListItemComponent,
    FiltersPop,
    SortingPop,
    VueHorizontal,
    ItemsInfinityLoad,
  },
  data() {
    return {
      isLoading: false,
      filterValues: [],
      filters: this.$page.filters,
      items: [], //this.$page.items.data
      priceRange: {},
      orderBy: "id",
      orderDirection: "asc",
    };
  },
  computed: {
    
    filtersList() {
      return this.filters;
    },
    params() {
      return {
        category_id: this.$page.categoryId,
        name: this.$page.name,
        order_by: this.orderBy,
        order_direction: this.orderDirection,
        filters_values: this.filterValues,
      };
    },
  },
  methods: {
    listUpdated(e){
      this.items = e.data;
    },
    addFilterValue(filterValue) {
      if (this.filterValues.includes(filterValue.id)) {
        this.filterValues.splice(this.filterValues.indexOf(filterValue.id), 1);
      } else {
        this.filterValues.push(filterValue.id);
      }
    },

    expandFilterValues(filterIndex, expand = true) {
      let filters = this.$page.filters;
      filters[filterIndex].expand_values = true;
      this.filters = filters;
    },

    applyFilterSearch() {},

    sortingUpdated(object) {
      this.orderBy = object.key;
      this.orderDirection = object.direction;
      // this.applyFilterSearch();
    },
    selectedAttributesHasBeenUpdated(event) {
      this.filterValues = event.selectedValues;
    },

    priceFilterRangeHasBeenUpdated(event) {
      this.priceRange = event.priceRange;
    },

    subCategoryHasBeenUpdated(event) {
      this.selectedSubCategoryId = event.selectedSubCategoryId;
    },
  },
};
</script>

<style>
</style>