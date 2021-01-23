<template>
  <div class="product__search-sorting" style="width: 24rem;"  v-click-outside="closePanel">
    <button
    @click="toggleList"
      class="product__search-option-button"
      style="background: rgb(87, 87, 87)"
    >
      <svg
        class="product__search-option-icon"
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

      {{ $page.$t.products.filters }}
    </button>
        <!-- <vue-horizontal
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
            :href="`/web/items?category_id=${category.id}&&name=${$page.name}&&search_via=${$page.search_via}`"
            class="page__categories__list-item"
          >
            <div class="page__categories__name">
              <span class="">{{category.search_keywords }}</span>
              {{ $page.$t.products.in }} -
              <span class="">{{ category.locale_name }}</span>

              <span class="">({{ category.result_items_count }})</span>
            </div>
          </a>
        </div>
      </vue-horizontal> -->

    <div class="product__search-sorting-panel" v-if="isOpen">
      <div class="product__search-sorting-panel-content">
        <ul class="product__search-sorting-list">
          <li v-for="(category, index) in categoriesList" :key="category.id" class="product__search-sorting-list-item" >
             <a
            :href=" showSubcategories == true ?`/web/categories/${category.id}` : `/web/items?category_id=${category.id}&&name=${$page.name}&&search_via=${$page.search_via}`"
            class="page__categories__list-item"
          >
            <div class="page__categories__name">
              <!-- getSearchName($page.name, category.locale_name,category.id) -->
              <span class="" v-if="showSubcategories !== true">{{category.search_keywords }}</span>
              <span  v-if="showSubcategories !== true"> {{ $page.$t.products.in }} - </span>
              <span class="">{{ category.locale_name }}</span>

              <span class=""  v-if="showSubcategories !== true">({{ category.result_items_count }})</span>
              <!-- {{ category.locale_name }} -->
            </div>
          </a>
          </li>

        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['categories', 'showSubcategories'],
  data () {
    return {
      categoriesList: [],
      isOpen: false
    }
  },
  created () {
    if (this.categories) {
      this.categoriesList = categories
    } else {
      this.categoriesList = this.$page.categories
    }
  // $page.categories    //
  },
  methods: {
    closePanel () {
      if (this.isOpen) { this.isOpen = false }
    },
    toggleList () {
      this.isOpen = !this.isOpen
    }
  }
}
</script>

<style>
</style>
