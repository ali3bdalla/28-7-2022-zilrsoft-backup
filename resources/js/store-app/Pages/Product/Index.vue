<template>
  <web-layout>
    <div class="container">
      <div class="product__search-page">
        <div class="page__mt-2" v-if="items.length > 2">
          <div class="product__search-options">
            <filters-pop
              v-if="$page.props.categoryId"
              :items="items"
              :search-name="$page.props.name"
              @subCategoryHasBeenUpdated="subCategoryHasBeenUpdated"
              :category-id="$page.props.categoryId"
              @selectedAttributesHasBeenUpdated="
                selectedAttributesHasBeenUpdated
              "
              @priceFilterRangeHasBeenUpdated="priceFilterRangeHasBeenUpdated"
            ></filters-pop>
            <categories-pop ></categories-pop>
            <sorting-pop @updated="sortingUpdated"></sorting-pop>
          </div>
        </div>
      </div>
      <div class="product__search-page">
        <div class="page__mt-2" v-if="items.length > 2">
          <div class="product__search-options items-center">
            <SwitchAvailableOnlyButtonComponent
              class="items-center"
              @changed="switchAvailableQtyChanged"
            ></SwitchAvailableOnlyButtonComponent>
          </div>
        </div>

        <AutoReloadProductListComponent
          @listUpdated="listUpdated"
          :params="params"
          :paramsUpdated="applyFilterSearch"
        ></AutoReloadProductListComponent>
      </div>
    </div>
  </web-layout>
</template>

<script>
import AutoReloadProductListComponent from '../../Components/Product/AutoReloadProductListComponent.vue'
import FiltersPop from '../../Components/Product/FiltersPop.vue'
import SortingPop from '../../Components/Product/SortingPop.vue'
import WebLayout from '../../Layouts/WebAppLayout'
import CategoriesPop from '../../Components/Product/CategoriesFilterPanelComponent.vue'
import SwitchAvailableOnlyButtonComponent from '../../Components/Product/SwitchAvailableOnlyButtonComponent.vue'

export default {
  components: {
    WebLayout,
    FiltersPop,
    SwitchAvailableOnlyButtonComponent,
    SortingPop,
    AutoReloadProductListComponent,
    CategoriesPop
  },
  data () {
    return {
      isLoading: false,
      filterValues: [],
      filters: this.$page.props.filters,
      items: [],
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
        search_via: this.$page.props.search_via,
        category_id: this.$page.props.categoryId,
        name: this.$page.props.name,
        order_by: this.orderBy,
        order_direction: this.orderDirection,
        filters_values: this.filterValues,
        forceUpdate: 0
      }
    }
  },
  methods: {
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
          if (
            item.locale_name.indexOf(subName) >= 0 &&
            result.indexOf(subName) === -1
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
      const filters = this.$page.props.filters
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
