<template>
  <ais-sort-by :items="list">
    <el-select
      slot-scope="{ items, currentRefinement, refine }"
      :value="sortingValue"
      @change="refine(sortingValue)"
      v-model="sortingValue"
    >
      <el-option
        :selected="item.value === currentRefinement"
        v-for="item in items"
        :key="item.value"
        :label="item.label"
        :value="item.value"
      >
      </el-option>
    </el-select>
  </ais-sort-by>
</template>

<script>
export default {
  data () {
    return {
      list: [
        {
          value: 'items_index',
          label: this.$page.$t.products.sorting_via
        },
        {
          value: 'items_index_price_desc',
          label: this.$page.$t.products.sorting_high_price
        },
        {
          value: 'items_index_price_asc',
          label: this.$page.$t.products.sorting_low_price
        },
        {
          value: 'items_index_latest',
          label: this.$page.$t.products.sorting_lastest
        },
        {
          value: 'items_index_oldest',
          label: this.$page.$t.products.sorting_oldest
        }
      ],
      sortingValue: 'items_index'
    }
  }
}
</script>

<style>
.product__search-sorting-list-item {
  /*padding-top: 0px !important;*/
}

.product__search-sorting-list-item_hide {
  display: none;
}
.el-input__inner {
  border-radius: 0px !important;
  height: 38px !important;
}
</style>
