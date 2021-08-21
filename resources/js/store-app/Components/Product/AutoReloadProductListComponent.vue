
<template>
  <div>
    <div class="products-grid">
      <ProductListItemComponent
        v-for="(item, index) in dataItems"
        :key="item.id"
        :item="item"
        :index="index"
      ></ProductListItemComponent>
    </div>
    <div class="page__mt-5">
      <infinite-loading @infinite="infiniteHandler" v-if="page <= 3">
        <div slot="spinner" class="my-2">
            <loading-svg></loading-svg>
        </div>
        <div slot="no-more" class=""></div>
        <div slot="no-results" class="my-2 flex items-center justify-center">
            <img :src="$asset('images/no-result.png')" class="object-cover w-48 h-48">

        </div>
      </infinite-loading>
      <div v-else class="product__show-more-button-container">
        <button class="product__show-more-button" @click="infiniteHandler">
          {{ $page.$t.products.show_more }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import LoadingSvg from '../Layout/LoadingSvg.vue'
import ProductListItemComponent from './ProductListItemComponent.vue'
export default {
  components: { ProductListItemComponent, LoadingSvg },
  props: {
    forceUpdate: {
      type: Number
    },
    params: {
      type: Object,
      default: () => {}
    }

  },
  data () {
    return {
      page: 1,
      dataItems: []
    }
  },
  watch: {
    forceUpdate (value) {
      this.page = 1
      this.dataItems = []
      this.infiniteHandler()
    },
    params: {
      deep: true,
      handler (value) {
        this.page = 1
        this.dataItems = []
        this.infiniteHandler()
      }
    }
  },
  methods: {
    paramsUpdated () {
    },
    infiniteHandler ($state) {
      const params = this.params
      params.page = this.page
      axios
        .get('/api/web/items/using_filters', {
          params: params
        })
        .then(({ data }) => {
          if (data.data.length) {
            this.page += 1
            this.dataItems.push(...data.data)
            this.$emit('listUpdated', {
              total: data.total,
              data: this.dataItems
            })
            $state.loaded()
          } else {
            this.$emit('listUpdated', {
              total: data.total,
              data: this.dataItems
            })
            $state.complete()
          }
        })
    }
  }
}
</script>
