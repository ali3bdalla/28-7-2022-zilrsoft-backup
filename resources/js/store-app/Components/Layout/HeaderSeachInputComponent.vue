<template>
  <div
    class="page__header-search-input-container"
    style="width: 100%"
    v-click-outside="resetItemsToNull"
  >
    <div
      class="advanced-search"
      style="border-color: #d2e8ff !important; direction: ltr !important"
    >
      <!-- <input
        style="padding: 3px 10px; width: 100%;"
        v-if="pagePath !== '/web/items/search/results'"
        v-model="searchKey"
        :placeholder="$page.props.$t.header.search_placeholder"
        type="text"
        class="text-gray-800"
        @keypress="getItems"
      /> -->

<!-- v-else -->
      <alogria-search ></alogria-search>
    </div>
  </div>

</template>

<script>
import AlogriaSearch from './AlogriaSearch.vue'
export default {
  components: { AlogriaSearch },
  data () {
    return {
      pagePath: window.location.pathname,
      isSearching: false,
      categoryId: 0,
      items: [],
      categoriesGroup: [],
      categories: [],
      searchKey: this.$page.props.name,
      call: _.debounce(
        (e) => {
          axios
            .post('/api/web/items', {
              name: this.searchKey,
              categoryId: this.categoryId
            })
            .then((res) => {
              this.items = res.data.items
              this.categoriesGroup = res.data.categories_group
            })
            .finally(() => {
              this.isSearching = false
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

    getItems (e) {
      location.href = `/web/items/search/results?${this.$page.props.algolia_items_search_as}%5Bquery%5D=${e.target.value}`
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
