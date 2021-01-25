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
          <items-infinity-load
            @listUpdated="listUpdated"
            :params="params"
            :paramsUpdated="applyFilterSearch"
          ></items-infinity-load>
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

export default {
  components: {
    WebLayout,
    FiltersPop,
    switchAvailableButton,

    SortingPop,
    ItemsInfinityLoad,
    CategoriesPop
  },
  data () {
    return {
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
          ) { result = result + ' ' + subName }
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
