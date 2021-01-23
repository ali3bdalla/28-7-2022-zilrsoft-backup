<template>
  <div
    class="page__header-search-input-container"
    style="width: 100%"
    v-click-outside="resetItemsToNull"
  >
    <div class="advanced-search" style="border-color: #d2e8ff !important">
      <div class="input-group" style="max-width: 100% !important">
        <input
          v-model="searchKey"
          :placeholder="$page.$t.header.search_placeholder"
          type="text"
          class="text-gray-800"
          @keypress.enter="getItems"
        />
        <!-- -->

        <button type="button" @click="getItems">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </div>
    <!-- <div>
      <div
        v-if="items.length > 0 && searchKey != ''"
        class="page__header-search-result"
        style="max-height: 80vh; overflow: scroll"
      >
        <a
          v-for="item in items"
          :key="item.id"
          :href="`/web/items/${item.id}`"
          class="page__header-search-result-item"
        >
          <span class="page__header-search-result-item-name">{{
            item.locale_name
          }}</span>

          <span
            v-if="item.available_qty <= 0"
            class="page__header-search-out-of-stock"
            >{{ $page.$t.products.out_of_stock }}</span
          >
        </a>
        <h2 class="page__header-search-categories">
          {{ $page.$t.header.categories }}
        </h2>
        <a
          v-for="(category, categoryIndex) in categoriesGroup"
          :key="`category_${category.id}_${categoryIndex}`"
          :href="`/web/items?categoryId=${category.id}&&name=${searchKey}`"
          class="page__header-search-categories-item"
        >
          <span class="">{{ searchKey }}</span> {{ $page.$t.products.in }} -
          <span class="">{{ category.locale_name }}</span>
        </a>
      </div>
    </div> -->
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
  data () {
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
            .post('/api/web/items', {
              name: this.searchKey,
              categoryId: this.categoryId
            })
            .then((res) => {
              console.log(res.data)
              this.items = res.data.items
              this.categoriesGroup = res.data.categories_group
            })
            .finally(() => {
              this.isSearching = false
              // e.cancel;
            })
        },
        250,
        { maxWait: 1000 }
      )
    }
  },

  methods: {
    resetItemsToNull () {
      this.items = []
    },
    hideItems () {
      this.resetItemsToNull()
      this.searchKey = ''
    },
    getToResultPage () {

    },

    getItems () {
      if (!this.searchKey) {
        this.$inertia.visit('/web')
      } else {
        this.$inertia.visit(`/web/items?name=${this.searchKey}`)
      }
    }
  }
}
</script>

<style>
.inner-header .advanced-search .input-group input {
  color: black;
}

.inner-header .advanced-search {
  height: 35px !important;
}
</style>
