<template>
  <div
    v-click-outside="closePanel"
    class="product__search-sorting md:relative"
    style="width: 24rem"
  >
    <button
      v-if="activeCategory && this.$page.category.id != activeCategory.id"
      class="product__search-option-button"
      @click="fetchSubcategories(activeCategory.parent_id)"
    >
      <svg
        class="product__search-option-icon w-5 h-5"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          v-if="$page.active_locale == 'ar'"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M9 5l7 7-7 7"
        ></path>
        <path
          v-else
          d="M15 19l-7-7 7-7"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
        ></path>
      </svg>

      {{ $page.$t.common.back }}
    </button>

    <button v-else class="product__search-option-button" @click="toggleList">
      <svg
        class="product__search-option-icon"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
        />
      </svg>

      {{ $page.$t.products.subcategories }}
    </button>

    <div v-if="isOpen" class="product__search-sorting-panel">
      <div class="product__search-sorting-panel-content">
        <div class="product__search-sorting-list">
          <div
            v-if="activeCategory && this.$page.category.id != activeCategory.id"
            class="product__search-sorting-list-item flex items-center justify-between gap-2 border-b bg-gray-100"
            style=""
            @click="redirectTo(activeCategory)"
          >
            <div>
              <a
                :href="
                  showSubcategories === true
                    ? `/web/items/search/results?category_id=${activeCategory.id}`
                    : `/web/items/search/results?category_id=${subcategory.id}`
                "
                class="page__categories__list-item"
              >
                <div class="page__categories__name">
                  <span v-if="showSubcategories !== true" class="">{{
                    activeCategory.search_keywords
                  }}</span>
                  <span v-if="showSubcategories !== true">
                    {{ $page.$t.products.in }} -
                  </span>
                  <span class="">{{ $page.$t.products.all_of_them }}</span>
                  <!--                  {{ activeCategory.locale_name }}-->

                  <span v-if="showSubcategories !== true" class=""
                    >({{ activeCategory.result_items_count }})</span
                  >
                </div>
              </a>
            </div>
          </div>
          <div
            v-for="(subcategory, index) in subcategories"
            :key="subcategory.id"
            class="product__search-sorting-list-item flex items-center justify-between gap-2 border-b"
            style=""
            @click="fetchSubcategories(subcategory.id, subcategory)"
          >
            <!--            :href="showSubcategories === true ?`/web/categories/${subcategory.id}` : `/web/items?category_id=${subcategory.id}&&name=${$page.name}&&search_via=${$page.search_via}`"-->
            <!--            <div>-->
            <div class="page__categories__list-item">
              <div class="page__categories__name">
                <span v-if="showSubcategories !== true" class="">{{
                  subcategory.search_keywords
                }}</span>
                <span v-if="showSubcategories !== true">
                  {{ $page.$t.products.in }} -
                </span>
                <span class="">{{ subcategory.locale_name }}</span>

                <span v-if="showSubcategories !== true" class=""
                  >({{ subcategory.result_items_count }})</span
                >
              </div>
              <!--              </div>-->
            </div>
            <div
              v-if="subcategory.children_count > 0"
              class="w-1/4 flex items-center px-3 justify-end"
            >
              <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  v-if="$page.active_locale === 'en'"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7"
                ></path>
                <path
                  v-else
                  d="M15 19l-7-7 7-7"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                ></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['categories', 'showSubcategories', 'category'],
  data () {
    return {
      categoriesList: [],
      subcategories: [],
      isOpen: false,
      pipelineCategories: []
    }
  },
  computed: {
    activeCategory () {
      return this.pipelineCategories[this.pipelineCategories.length - 1]
    }
  },
  created () {
    this.fetchSubcategories(this.category.id, this.category)
  },
  methods: {
    fetchSubcategories (categoryId, category = null) {
      if (category && category.children_count === 0) {
        this.redirectTo(category)
        return
      }
      if (category != null) {
        this.pipelineCategories.push(category)
      } else {
        this.pipelineCategories.pop()
      }
      axios
        .get('/api/web/categories/' + categoryId + '/subcategories')
        .then((res) => (this.subcategories = res.data))
    },

    redirectTo (subcategory) {
      // this.$inertia.visit(this.showSubcategories === true ? `/web/categories/${subcategory.id}` : `/web/items?category_id=${subcategory.id}&&name=${this.$page.name}&&search_via=${this.$page.search_via}`)
      this.$inertia.visit(
        this.showSubcategories === true && subcategory
          ? `/web/items/search/results?category_id=${subcategory.id}`
          : `/web/items?category_id=${subcategory.id}&&name=${this.$page.name}&&search_via=${this.$page.search_via}`
      )
    },
    closePanel () {
      if (this.isOpen) {
        this.isOpen = false
      }
    },
    toggleList () {
      this.isOpen = !this.isOpen
    }
  }
}
</script>

<style>
</style>
