<template>
  <div class="relative p-0" style="width: 100%">
    <div class="advanced-search" style="border-color: #d2e8ff !important">
      <div class="input-group">
        <input
          v-model="searchKey"
          :placeholder="$page.$t.header.search_placeholder"
          type="text"
          class="text-gray-800"
          @keypress.enter="getItems" 
        />
                  <!-- -->

         <button type="button"  @click="getItems"><i class="ti-search"></i></button>
        <!-- <button v-if="items.length > 0" @click="getItems" type="button" style="    font-size: 32px;"><i class="li-search"></i></button> -->
      </div>
    </div>
    <div
      v-if="items.length > 0 && searchKey != ''"
      class="absolute z-50 w-full px-3 pt-2 mx-auto bg-white shadow-lg"
    >
      <a
        v-for="item in items"
        :key="item.id"
        :href="`/web/items/${item.id}`"
        class="flex justify-between px-2 py-1 font-bold text-gray-800 border-b hover:text-gray-600"
      >
        <span class="truncate">{{ item.locale_name }}</span>

        <span
          v-if="item.available_qty <= 0"
          class="text-red-500 text-sm w-32 text-left"
          >{{ $page.$t.products.out_of_stock }}</span
        >
      </a>
      <h2
        class="p-2 pt-3 mt-4 mb-1 text-lg font-bold text-gray-700 border-t-2 border-black"
      >
        {{ $page.$t.header.categories }}
      </h2>
      <a
        v-for="(category, categoryIndex) in categoriesGroup"
        :key="`category_${category.id}_${categoryIndex}`"
        :href="`/web/items?categoryId=${category.id}&&name=${searchKey}`"
        class="block px-2 py-1 font-bold text-gray-800 border-b hover:text-gray-600"
      >
        <span class="">{{ searchKey }}</span> {{ $page.$t.products.in }} -
        <span class="">{{ category.locale_name }}</span>
      </a>
    </div>
  </div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";

export default {
  data() {
    return {
      isSearching: false,
      categoryId: 0,
      items: [],
      categoriesGroup: [],
      categories: [],
      searchKey: this.$page.name,
      call: _.debounce(
        (e) => {
          axios
            .post("/api/web/items", {
              name: this.searchKey,
              categoryId: this.categoryId,
            })
            .then((res) => {
              console.log(res.data);
              this.items = res.data.items;
              this.categoriesGroup = res.data.categories_group;
            })
            .finally(() => {
              this.isSearching = false;
              // e.cancel;
            });
        },
        250,
        { maxWait: 1000 }
      ),
    };
  },

  created() {
    // this.getCategories();
  },
  methods: {

    hideItems()
    {
      this.items = [];
      this.searchKey = "";
    },
    getToResultPage() {
      this.$inertia.visit(
        `/web/items?categoryId=${this.categoryId}&&name=${this.searchKey}`
      );
    },
    // getCategories() {
    //   let appVm = this;
    //   axios.get("/api/web/categories").then((res) => {

    //     appVm.categories = res.data;
    //   });
    // },
    getItems() {
      if (this.searchKey == "") {
        this.items = [];
      } else {
        this.call();
      }

      //   this.$inertia.visit("/api/web/items", {
      //     method: "POST",
      //     data: {},
      //     replace: false,
      //     preserveState: false,
      //     preserveScroll: false,
      //     only: [],
      //     headers: {},
      //     onCancelToken: (cancelToken) => {},
      //     onCancel: () => {},
      //     onStart: (visit) => {
      //       console.log(visit);
      //     },
      //     onProgress: (progress) => {
      //       console.log(progress);
      //     },
      //     onSuccess: (page) => {
      //       onsole.log(page);
      //     },
      //     onFinish: (response) => {
      //       console.log(response);
      //     },
      //   });
    },
  },
};
</script>

<style>
.inner-header .advanced-search .input-group input {
  color: black;
}
</style>