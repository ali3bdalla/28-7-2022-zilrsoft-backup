<template>
  <web-layout class="">
    <div class="mt-3 container bg-white shadow-lg rounded-lg">
      <div class="pt-3">
        <div class="flex justify-between items-center gap-6">
          <!-- md:justify-end -->

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
        <div
          v-if="isLoading"
          class="flex items-center justify-center w-full h-full"
        >
          <circle-spin class="loading" v-show="isLoading"></circle-spin>
        </div>
        <div class="w-full" v-else>
          <div
            class="grid grid-cols-1 gap-1 md:grid-cols-3 md:gap-3 mb-5 mt-1 h-auto"
          >
            <a
              v-for="(item, index) in items"
              :key="item.id"
              :href="`/web/items/${item.id}`"
            >
              <ProductListItemComponent
                :index="index"
                :item="item"
              ></ProductListItemComponent>
            </a>
          </div>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import FiltersPop from "../../../components/Web/Product/List/FiltersPop.vue";
import SortingPop from "../../../components/Web/Product/List/SortingPop.vue";
import WebLayout from "../../../Layouts/WebAppLayout";
import ProductListItemComponent from "./../../../components/Web/Product/ProductListItemComponent";

export default {
  components: { WebLayout, ProductListItemComponent, FiltersPop, SortingPop },
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
  },
  methods: {
    addFilterValue(filterValue) {
      if (this.filterValues.includes(filterValue.id)) {
        this.filterValues.splice(this.filterValues.indexOf(filterValue.id), 1);
      } else {
        this.filterValues.push(filterValue.id);
      }

      this.applyFilterSearch();
    },

    expandFilterValues(filterIndex, expand = true) {
      let filters = this.$page.filters;
      filters[filterIndex].expand_values = true;
      this.filters = filters;
    },

    applyFilterSearch() {
      if (!this.isLoading) {
        this.isLoading = true;
        let appVm = this;
        axios
          .post("/api/web/items/using_filters", {
            category_id: this.$page.categoryId,
            name: this.$page.name,
            order_by: this.orderBy,
            order_direction: this.orderDirection,
            filters_values: this.filterValues,
          })
          .then((res) => {
            appVm.items = res.data.data;
          }).catch(err => {
            console.log(err)
          })
          .finally(() => {
            appVm.isLoading = false;
          });
      }
    },

    sortingUpdated(object) {
      this.orderBy = object.key;
      this.orderDirection = object.direction;
      this.applyFilterSearch();
    },
    selectedAttributesHasBeenUpdated(event) {
      // this.items = [];
      this.filterValues = event.selectedValues;
      this.applyFilterSearch();
    },

    priceFilterRangeHasBeenUpdated(event) {
      // this.items = [];
      this.priceRange = event.priceRange;
      this.applyFilterSearch();
    },

    subCategoryHasBeenUpdated(event) {
      this.selectedSubCategoryId = event.selectedSubCategoryId;
      // this.items = [];
      this.applyFilterSearch();
    },
  },
};
</script>

<style>
</style>