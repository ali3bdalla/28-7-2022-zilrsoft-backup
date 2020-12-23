<template>
  <div class="relative p-0" style="    width: 100%;">
    <div class="advanced-search">
      <select v-model="categoryId" class="category-btn">
        <option value="0">{{ $page.$t.header.categories }}</option>

        <option
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
        >
          {{ category.locale_name }}
        </option>
      </select>
      <div class="input-group">
        <input
            v-model="searchKey"
            :placeholder="$page.$t.header.search_placeholder"
            type="text"
            @keydown="getItems"
            @keyup.enter="getToResultPage"
        />
        <button type="button"><i class="ti-search"></i></button>
      </div>
    </div>
    <div
        v-if="items.length > 0 && searchKey != ''"
        class="absolute z-50 w-full px-3 pt-2 mx-auto bg-white shadow-lg "
    >
      <a
          v-for="item in items"
          :key="item.id"
          :href="`/web/items/${item.id}`"
          class="flex justify-between px-2 py-1 font-bold text-gray-800 border-b hover:text-gray-600"
      >
        <span>{{ item.locale_name }}</span>

        <span v-if="item.available_qty <= 0" class="text-red-500">{{$page.$t.products.out_of_stock}}</span>
      </a>
      <h2 class="p-2 pt-3 mt-4 mb-1 text-lg font-bold text-gray-700 border-t-2 border-black">
        {{$page.$t.header.categories}}
      </h2>
      <a

          v-for="(category,categoryIndex) in categoriesGroup"
          :key="`category_${category.id}_${categoryIndex}`"
          :href="`/web/items?categoryId=${category.id}&&name=${searchKey}`"
          class="block px-2 py-1 font-bold text-gray-800 border-b hover:text-gray-600 "
      >
        <span class="">{{ searchKey }}</span>  {{ $page.$t.products.in }} - <span class="">{{ category.locale_name }}</span>
      </a>

    </div>
  </div>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";

export default {
  data() {
    return {
      categoryId: 0,
      items: [],
      categoriesGroup: [],
      categories: [],
      searchKey: "",
    };
  },

  created() {
    this.getCategories();
  },
  methods: {
    getToResultPage() {
      console.log(this.searchKey);
      this.$inertia.visit(`/web/items?categoryId=${this.categoryId}&&name=${this.searchKey}`);
    },
    getCategories() {
      let appVm = this;
      axios.get("/api/web/categories").then((res) => {
        appVm.categories = res.data;
      });
    },
    getItems() {
      if (this.searchKey == "") {
        this.items = [];
      } else {
        let appVm = this;
        axios
            .post("/api/web/items", {
              name: this.searchKey,
              categoryId: this.categoryId,
            })
            .then((res) => {
              appVm.items = res.data.items;
              appVm.categoriesGroup = res.data.categories_group;
            });
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
</style>