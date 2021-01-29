<template>
  <div class="product__search-sorting" v-click-outside="closePanel">
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
      {{ $page.$t.products.sorting_via }}
    </button>
    <!-- ${$page.algolia_items_search_as}_ -->

    <ais-sort-by
      :items="[
        {
          value: `items_index_price_desc`,
          label: this.$page.$t.products.sorting_high_price,
        },
        {
          value: `items_index_price_asc`,
          label: this.$page.$t.products.sorting_low_price,
        },
        {
          value: `items_index_latest`,
          label: this.$page.$t.products.sorting_lastest,
        },
        {
          value: `items_index_oldest`,
          label: this.$page.$t.products.sorting_oldest,
        },
      ]"
    >
      <div class="product__search-sorting-panel" v-if="isOpen">
        <div class="product__search-sorting-panel-content">
          <ul
            class="product__search-sorting-list"
            slot-scope="{ items, currentRefinement, refine }"
          >
            <li
              @click="refine(item.value)"
              class="product__search-sorting-list-item"
              :class="{
                'product__search-sorting-list-item__active':
                  item.value === currentRefinement,
              }"
              v-for="item in items"
              :key="item.value"
              :value="item.value"
            >
              {{ item.label }}
            </li>
          </ul>
        </div>
      </div>
    </ais-sort-by>
  </div>
</template>

<script>
export default {
  data () {
    return {
      activeSorting: {},

      list: [
        {
          title: this.$page.$t.products.sorting_low_price,
          key: 'online_offer_price',
          direction: 'asc'
        },
        {
          title: this.$page.$t.products.sorting_high_price,
          key: 'online_offer_price',
          direction: 'desc'
        },

        {
          title: this.$page.$t.products.sorting_lastest,
          key: 'id',
          direction: 'desc'
        },

        {
          title: this.$page.$t.products.sorting_oldest,
          key: 'id',
          direction: 'asc'
        }
      ],
      isOpen: false
    }
  },
  methods: {
    setSorting (object) {
      if (object.title !== this.activeSorting.title) {
        this.activeSorting = object
        this.$emit('updated', object)
      }

      this.toggleList()
    },
    closePanel () {
      this.isOpen = false
    },
    toggleList () {
      this.isOpen = !this.isOpen
    }
  }
}
</script>

<style>
</style>
