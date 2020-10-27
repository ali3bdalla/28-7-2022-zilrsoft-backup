<template>
  <web-layout class="">
    <div class="mt-3 container bg-white shadow-lg rounded-lg">
      <div class="flex">
        <div class="w-3/12">
          <div v-for="filter in $page.filters" :key="filter.id" class="border-b p-2 font-bold">
            {{ filter.filter.name }}
            <div class="mt-2 ml-2">
              <div v-for="filterValue in filter.values" :key="filterValue.id" class="my-1 text-gray-700">
                <input :checked="filterValues.includes(filterValue.id)" class="mr-2" type="checkbox"
                       @change="addFilterValue(filterValue)"/>
                {{ filterValue.name }}
              </div>
            </div>
          </div>

        </div>
        <div v-if="!isLoading" class="w-9/12 grid p-2 grid-cols-3 gap-1 md:gap-3 mb-5 mt-3">
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
        <div v-else class="w-7/12 flex-1 flex   justify-center ">
          <div class="mt-20">
            <svg class=" fill-current w-64 h-64 " display="block" preserveAspectRatio="xMidYMid"
                 style="margin:auto;background:0 0" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" width="15" x="42.5" y="8.5">
                <animate attributeName="opacity" begin="-0.9333333333333333s" dur="1s" keyTimes="0;1"
                         repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(24 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.8666666666666667s" dur="1s" keyTimes="0;1"
                         repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(48 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.8s" dur="1s" keyTimes="0;1" repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(72 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.7333333333333333s" dur="1s" keyTimes="0;1"
                         repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(96 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.6666666666666666s" dur="1s" keyTimes="0;1"
                         repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(120 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.6s" dur="1s" keyTimes="0;1" repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(144 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.5333333333333333s" dur="1s" keyTimes="0;1"
                         repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(168 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.4666666666666667s" dur="1s" keyTimes="0;1"
                         repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(192 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.4s" dur="1s" keyTimes="0;1" repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(216 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.3333333333333333s" dur="1s" keyTimes="0;1"
                         repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(240 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.26666666666666666s" dur="1s" keyTimes="0;1"
                         repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(264 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.2s" dur="1s" keyTimes="0;1" repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(288 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.13333333333333333s" dur="1s" keyTimes="0;1"
                         repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(312 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="-0.06666666666666667s" dur="1s" keyTimes="0;1"
                         repeatCount="indefinite"
                         values="1;0"/>
              </rect>
              <rect fill="#85a2b6" height="9" rx="4.8" ry="4.5" transform="rotate(336 50 50)" width="15" x="42.5"
                    y="8.5">
                <animate attributeName="opacity" begin="0s" dur="1s" keyTimes="0;1" repeatCount="indefinite"
                         values="1;0"/>
              </rect>
            </svg>

          </div>
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import WebLayout from "../../../Layouts/WebAppLayout";
import ProductListItemComponent from "./../../../components/Web/Product/ProductListItemComponent";

export default {
  components: {WebLayout, ProductListItemComponent},
  data() {
    return {
      isLoading: false,
      filterValues: [],
      items: this.$page.items.data
    };
  },
  computed: {
    // items() {
    //   return this.$page.items.data;
    // },
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

    applyFilterSearch() {
      this.isLoading = true;
      // console.log(this.filterValues);
      let appVm = this;
      axios.post('/api/web/items/using_filters', {
        categoryId: this.$page.categoryId,
        name: this.$page.name,
        filters_values: this.filterValues
      }).then(res => {
        console.log(res.data);
        appVm.items = res.data.data;
      }).finally(() => {
        appVm.isLoading = false;
      });
    }
  }
};
</script>

<style>
</style>