<template>
  <web-layout>
    <div class="container">
      <div class="product__search-page">
        <div class="page__mt-2" v-if="items.length > 2">
          <div class="product__search-options">
            <filters-pop
              v-if="$page.categoryId"
              :items="items"
              :search-name="$page.name"
              @subCategoryHasBeenUpdated="subCategoryHasBeenUpdated"
              :category-id="$page.categoryId"
              @selectedAttributesHasBeenUpdated="
                selectedAttributesHasBeenUpdated
              "
              @priceFilterRangeHasBeenUpdated="priceFilterRangeHasBeenUpdated"
            ></filters-pop>

            <categories-pop v-if="!$page.categoryId"></categories-pop>
            <sorting-pop @updated="sortingUpdated"></sorting-pop>

            <ais-sort-by
              :items="[
                { value: 'category_name_asc', label: 'Featured' },
                { value: 'online_offer_price_asc', label: 'Price asc.' },
                { value: 'online_offer_price_desc', label: 'Price desc.' },
              ]"
            />
          </div>
        </div>
      </div>
      <div class="product__search-page">
        <div class="page__mt-2" v-if="items.length > 2">
          <div class="product__search-options items-center">
            <switchAvailableButton
              class="items-center"
              @changed="switchAvailableQtyChanged"
            ></switchAvailableButton>
          </div>
        </div>
        <div class="">
          <div class="flex gap-3">
            <div class="w-64">
              <!-- <ais-clear-refinements /> -->
              <!-- <h2>Categories</h2> -->

              <ais-sort-by-selector
                :items="[
                  {
                    value: 'online_offer_price_desc',
                    label: 'In Stock First',
                  },
                  {
                    value: 'online_offer_price_asc',
                    label: 'Out of Stock First',
                  },
                ]"
              />

              <div style="text-align: right" class="bg-white mb-2 broder-t p-2">
                <ais-numeric-menu
                  attribute="online_offer_price"
                  :items="[
                    { label: 'All' },
                    { label: '1-10 ر.س', end: 10 },
                    { label: '10-100  ر.س', start: 10, end: 100 },
                    { label: '100-500  ر.س', start: 100, end: 500 },
                    { label: '500-2000  ر.س', start: 500, end: 2000 },
                    { label: 'اعلى من 2000  ر.س', start: 2000 },
                  ]"
                />
              </div>

              <div
                style="text-align: right"
                v-if="isAvailableFilter(filter)"

                v-for="filter in $page.alogia_search_filters"
                :key="filter"
              >
                <ais-refinement-list
                  label="Name"
                  sorty-by="count:desc"
                  :transform-items="transformItems"
                  :show-more="true"
                  :attribute="filter"
                >
                <!-- !isFromSearch && ! -->
                  <div
                     class="bg-white mb-2 broder-t p-2"
                     v-show="items.length"
                    slot-scope="{
                      items,
                      isShowingMore,
                      isFromSearch,
                      canToggleShowMore,
                      refine,
                      createURL,
                      toggleShowMore,
                      searchForItems,
                    }"
                  >
                    <!-- <input
                      @input="searchForItems($event.currentTarget.value)"
                    /> -->
                    <ul>
                      <li v-if="isFromSearch && !items.length">No results.</li>
                      <li v-for="item in items" :key="item.value">
                        <a
                          :href="createURL(item)"
                          :style="{ fontWeight: item.isRefined ? 'bold' : '' }"
                          @click.prevent="refine(item.value)"
                        >
                          <ais-highlight attribute="item" :hit="item" />
                          ({{ item.count.toLocaleString() }})
                        </a>
                      </li>
                    </ul>
                    <button
                      @click="toggleShowMore"
                      :disabled="!canToggleShowMore"
                    >
                      {{ !isShowingMore ? "Show more" : "Show less" }}
                    </button>
                  </div>
                </ais-refinement-list>
              </div>

              <!-- <ais-refinement-list attribute="filters" searchable  /> -->
              <!-- <ais-refinement-list attribute="category_ar_name" searchable /> -->
              <!-- <ais-refinement-list
                attribute="online_offer_price"
                searchable
                range
              /> -->
              <!-- <ais-range-input attribute="price">
                <div slot-scope="{ currentRefinement, range, refine }">

                  <el-slider
                    :marks="marks(range.min, range.max)"
                    @change="refine({ min: $event[0], max: $event[1] })"
                    range
                    show-stops
                    :max="range.max"
                    :min="range.min"
                  >
                  </el-slider>
                </div>
              </ais-range-input> -->

              <ais-configure :hitsPerPage="20" />
            </div>
            <div class="flex-1">
              <ais-infinite-hits :escapeHTML="false" :show-previous="true">
                <div
                  slot-scope="{ items, refinePrevious, refineNext, isLastPage }"
                >
                  <div class="products-grid">
                    <alogira-list-product
                      v-for="(item, index) in items"
                      :key="item.id"
                      :item="item"
                      :index="index"
                    ></alogira-list-product>
                  </div>
                </div>
              </ais-infinite-hits>
            </div>
          </div>
          <!-- <div slot="item" slot-scope="{ item }">
                  <alogira-list-product :item="item"></alogira-list-product> -->
          <!-- <h2>{{ item.item_name }}</h2>
              <img :src="item.item_image_url" align="left" :alt="item.item_name" />
              <div class="hit-name">
                <ais-highlight attribute="item_name" :hit="item"></ais-highlight>
              </div>
              <div class="hit-description">
                <ais-highlight
                  attribute="item_ar_name"
                  :hit="item"
                ></ais-highlight>
              </div> -->
          <!-- </div> -->
          <!-- <items-infinity-load
            @listUpdated="listUpdated"
            :params="params"
            :paramsUpdated="applyFilterSearch"
          ></items-infinity-load> -->
        </div>
      </div>
    </div>
  </web-layout>
</template>

<script>
import ItemsInfinityLoad from '../../../components/Web/Item/ItemsInfinityLoad.vue'
import FiltersPop from '../../../components/Web/Product/List/FiltersPop.vue'
import SortingPop from '../../../components/Web/Product/List/SortingPop.vue'
import WebLayout from '../../../Layouts/WebAppLayout'
import CategoriesPop from '../../../components/Web/Product/List/CategoriesPop.vue'
import switchAvailableButton from '../../../components/Web/Product/List/switchAvailableButton'
import AlogiraListProduct from '../../../components/Web/Product/AlogiraListProduct.vue'
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'
export default {
  components: {
    WebLayout,
    VueSlider,
    FiltersPop,
    switchAvailableButton,

    SortingPop,
    ItemsInfinityLoad,
    CategoriesPop,
    AlogiraListProduct
  },
  data () {
    return {
      marks: function (min, max) {
        const len = max - min
        let step = 10
        if (len > 1000) step = 100
        if (len > 2000) step = 200
        if (len > 3000) step = 400
        if (len > 5000) step = 1200
        if (len > 6000) step = 2500
        const keys = {}
        for (let index = min; index < max; index = index + step) {
          keys[index] = `${index}`
        }

        return keys
      },
      isLoading: false,
      filterValues: [],
      filters: this.$page.filters,
      items: [], // this.$page.items.data
      priceRange: {},
      orderBy: 'available_qty',
      orderDirection: 'desc',
      available_only: 'no'
    }
  },
  computed: {
    filtersList () {
      return this.filters
    },
    params () {
      return {
        available_only: this.available_only,
        search_via: this.$page.search_via,
        category_id: this.$page.categoryId,
        name: this.$page.name,
        order_by: this.orderBy,
        order_direction: this.orderDirection,
        filters_values: this.filterValues,
        forceUpdate: 0
      }
    }
  },
  methods: {
    transformItems (items) {
      return items.map((item) => ({
        ...item,
        label: `${item.label}`.replace('ar_filters_', '').toUpperCase()
      }))
    },
    isAvailableFilter (filter) {
      return !filter.indexOf('ar_filters') && filter !== 'category_name'
    },
    toValue (value, range) {
      return [
        value.min !== null ? value.min : range.min,
        value.max !== null ? value.max : range.max
      ]
    },
    applyFilterSearch () {},
    switchAvailableQtyChanged (e) {
      this.available_only = e ? 'yes' : 'no'
      this.forceUpdate++
    },
    getSearchName (name, categoryName, categoryId = 0) {
      const names = name.split(' ')
      let result = ''
      names.forEach((subName) => {
        const items = this.items.filter((p) => p.category_id === categoryId)
        items.forEach((item) => {
          console.log(item.locale_name)
          if (
            item.locale_name.indexOf(subName) >= 0 &&
            result.indexOf(subName) == -1
          ) {
            result = result + ' ' + subName
          }
        })
      })

      return result
    },
    listUpdated (e) {
      this.items = e.data
    },
    addFilterValue (filterValue) {
      if (this.filterValues.includes(filterValue.id)) {
        this.filterValues.splice(this.filterValues.indexOf(filterValue.id), 1)
      } else {
        this.filterValues.push(filterValue.id)
      }
    },

    expandFilterValues (filterIndex, expand = true) {
      const filters = this.$page.filters
      filters[filterIndex].expand_values = true
      this.filters = filters
    },

    applyFilterSearch () {},

    sortingUpdated (object) {
      this.orderBy = object.key
      this.orderDirection = object.direction
    },
    selectedAttributesHasBeenUpdated (event) {
      this.filterValues = event.selectedValues
    },

    priceFilterRangeHasBeenUpdated (event) {
      this.priceRange = event.priceRange
    },

    subCategoryHasBeenUpdated (event) {
      this.selectedSubCategoryId = event.selectedSubCategoryId
    }
  }
}
</script>
