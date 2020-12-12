<template>
  <div class="relative p-0">
    <div class="advanced-search">
      <select v-model="categoryId" class="category-btn">
        <option value="0">All Categories</option>

        <option
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
        >
          {{ category.name }}
        </option>
      </select>
      <div class="input-group">
        <input
            v-model="searchKey"
            placeholder="What do you need?"
            type="text"
            @keydown="getItems"
            @keyup.enter="getToResultPage"
        />
        <button type="button"><i class="ti-search"></i></button>
      </div>
    </div>
    <div
        v-if="items.length > 0"
        class="bg-white absolute w-full z-50 shadow-lg mx-auto pt-2 px-3 "
    >
      <a
          v-for="item in items"
          :key="item.id"
          :href="`/web/items/${item.id}`"
          class="block px-2 py-1 border-b text-gray-800 font-bold font-bold hover:text-gray-600"
      >
        {{ item.name }}
      </a>
      <h2 class="mt-3 mb-1 p-2 text-lg text-gray-700 font-bold">
        categories
      </h2>
      <a

          v-for="category in categoriesGroup"
          :key="category.id"
          :href="`/web/items?categoryId=${category.id}&&name=${searchKey}`"
          class="block px-2 py-1 border-b text-gray-800 font-bold hover:text-gray-600 "
      >
        <span class="">{{ searchKey }}</span> - <span class=" ">{{ category.name }}</span>
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