<template>
  <web-layout class="">
    <div class="mt-3 container bg-white shadow-lg rounded-lg">
      <div class="pt-3">
        <div class="flex justify-between items-center gap-6">
          <!-- md:justify-end -->

          <filters-pop
            :search-name="$page.name"
            @subCategoryHasBeenUpdated="subCategoryHasBeenUpdated"
            :category-id="$page.categoryId"
            @selectedAttributesHasBeenUpdated="selectedAttributesHasBeenUpdated"
            @priceFilterRangeHasBeenUpdated="priceFilterRangeHasBeenUpdated"
          ></filters-pop>
          <button
            class="w-full md:w-48 h-full text-white flex justify-center gap-3 items-center py-1  text-white"
            style="background: rgb(87, 87, 87)"
          >
            <svg
              class="w-4 h-4"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"
              />
            </svg>

            الترتيب حسب
          </button>
        </div>
      </div>

      <div class="flex">
        <!-- <div class="w-3/12 hidden md:block">
          <div
            v-for="(filter, filterIndex) in filtersList"
            :key="filter.id"
            class="border-b p-2 font-bold"
          >
            {{ filter.filter.locale_name }}

            <div class="mt-2 ml-2">
              <div
                v-if="filter.expand_values || index < 5"
                v-for="(filterValue, index) in filter.values"
                :key="filterValue.id"
                class="my-1 text-gray-700"
              >
                <input
                  :checked="filterValues.includes(filterValue.id)"
                  class="mr-2"
                  type="checkbox"
                  @change="addFilterValue(filterValue)"
                />
                {{ filterValue.locale_name }}
              </div>
              <button
                v-if="filter.values.length > 5"
                @click="expandFilterValues(filterIndex, true)"
                class="text-gray-600"
              >
                {{ $page.$t.products.all }}.. ({{ filter.values.length }})
              </button>
            </div>
          </div>
        </div> -->
        <!--        v-if="!isLoading"-->
        <!-- md:w-9/12 -->
        <div class="w-full">
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
import WebLayout from "../../../Layouts/WebAppLayout";
import ProductListItemComponent from "./../../../components/Web/Product/ProductListItemComponent";

export default {
  components: { WebLayout, ProductListItemComponent, FiltersPop },
  data() {
    return {
      isLoading: false,
      filterValues: [],
      filters: this.$page.filters,
      items: this.$page.items.data,
      priceRange: {},
      // items: [],
      // attributes: [],
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
      // if(!this.showLoading)
      //           {
      //               let categoryId = this.selectedSubCategoryId === 0 ? this.categoryId : this.selectedSubCategoryId;
      //               this.showLoading = true;
      //               let appVm = this;

      //               console.log(this.attributes);
      //               axios.post(getRequestUrl('items'),{
      //                   page:this.currentPage,
      //                   category_id: categoryId,
      //                   attributes: this.attributes,
      //               }).then(function(response){
      //                   let data = response.data.data;
      //                   data.forEach(function (item) {
      //                       appVm.items.push(item);
      //                   })
      //                   appVm.lastPage = response.data.last_page;
      //                   appVm.currentPage = response.data.current_page;
      //               }).catch(function(error) {
      //                   alert(`server error : ${error}`);
      //               }).finally(function () {
      //                   appVm.showLoading = false;
      //               });
      //           }
      this.isLoading = true;
      // console.log(this.filterValues);
      let appVm = this;
      axios
        .post("/api/web/items/using_filters", {
          categoryId: this.$page.categoryId,
          name: this.$page.name,
          filters_values: this.filterValues,
        })
        .then((res) => {
          console.log(res.data.data.length);
          appVm.items = res.data.data;
        })
        .finally(() => {
          appVm.isLoading = false;
        });
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