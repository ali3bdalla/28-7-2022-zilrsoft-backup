<template>
  <web-layout class="">
    <div class="mt-3 container bg-white shadow-lg rounded-lg">
      <div class="pt-3">
        <div class="flex justify-between items-center gap-6">
          <filters-pop
            :items="items"
            :search-name="$page.name"
            @subCategoryHasBeenUpdated="subCategoryHasBeenUpdated"
            :category-id="$page.categoryId"
            @selectedAttributesHasBeenUpdated="selectedAttributesHasBeenUpdated"
            @priceFilterRangeHasBeenUpdated="priceFilterRangeHasBeenUpdated"
          ></filters-pop>
          <sorting-pop @updated="sortingUpdated"></sorting-pop>
        </div>
      </div>

      <div class="flex items-center justfiy-center">
        <items-infinity-load :params="params" :paramsUpdated="applyFilterSearch"></items-infinity-load>
      </div>
    </div>
  </web-layout>
</template>

<script>
import ItemsInfinityLoad from '../../../components/Web/Item/ItemsInfinityLoad.vue';
import FiltersPop from "../../../components/Web/Product/List/FiltersPop.vue";
import SortingPop from "../../../components/Web/Product/List/SortingPop.vue";
import WebLayout from "../../../Layouts/WebAppLayout";
import ProductListItemComponent from "./../../../components/Web/Product/ProductListItemComponent";

export default {
  components: { WebLayout, ProductListItemComponent, FiltersPop, SortingPop, ItemsInfinityLoad },
  data() {
    return {
      isLoading: false,
      filterValues: [],
      filters: this.$page.filters,
      items: this.$page.items.data,
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
    addFilterValue(filterValue) {
      if (this.filterValues.includes(filterValue.id)) {
        this.filterValues.splice(this.filterValues.indexOf(filterValue.id), 1);
      } else {
        this.filterValues.push(filterValue.id);
      }

      // this.applyFilterSearch();
    },

    expandFilterValues(filterIndex, expand = true) {
      let filters = this.$page.filters;
      filters[filterIndex].expand_values = true;
      this.filters = filters;
    },

    applyFilterSearch() {
      // if (!this.isLoading) {
      //   this.isLoading = true;
      //   let appVm = this;
      //   axios
      //     .post("/api/web/items/using_filters", {
      //       category_id: this.$page.categoryId,
      //       name: this.$page.name,
      //       order_by: this.orderBy,
      //       order_direction: this.orderDirection,
      //       filters_values: this.filterValues,
      //     })
      //     .then((res) => {
      //       appVm.items = res.data.data;
      //     })
      //     .catch((err) => {
      //       console.log(err);
      //     })
      //     .finally(() => {
      //       appVm.isLoading = false;
      //     });
      // }
    },

    sortingUpdated(object) {
      this.orderBy = object.key;
      this.orderDirection = object.direction;
      // this.applyFilterSearch();
    },
    selectedAttributesHasBeenUpdated(event) {
      // this.items = [];
      this.filterValues = event.selectedValues;
      // this.applyFilterSearch();
    },

    priceFilterRangeHasBeenUpdated(event) {
      // this.items = [];
      this.priceRange = event.priceRange;
      // this.applyFilterSearch();
    },

    subCategoryHasBeenUpdated(event) {
      this.selectedSubCategoryId = event.selectedSubCategoryId;
      // this.items = [];
      // this.applyFilterSearch();
    },
  },
};
</script>

<style>
</style>