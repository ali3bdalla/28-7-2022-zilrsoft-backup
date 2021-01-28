
<template>
  <div>
    <div class="product__search-page">
      <div class="page__mt-2">
        <div class="product__search-options">
          <!-- <alogira-search-filters></alogira-search-filters> -->
 <alogria-pop-filters></alogria-pop-filters>
        </div>
      </div>
    </div>
    <div class="product__search-page">
      <!-- <div class="page__mt-2" v-if="items.length > 2">
          <div class="product__search-options items-center">
            <switchAvailableButton
              class="items-center"
              @changed="switchAvailableQtyChanged"
            ></switchAvailableButton>
          </div>
        </div> -->
      <div class="flex gap-3">
        <!--<div class="w-64">
          <alogria-pop-filters></alogria-pop-filters>
          <alogira-search-filters></alogira-search-filters>
        </div>-->
        <div class="flex-1">
          <div class="mt-0 pt-0">
            <ais-configure :hitsPerPage="50" />
            <ais-infinite-hits :escapeHTML="false" :show-previous="true">
              <div
                slot-scope="{ items, refinePrevious, refineNext, isLastPage }"
              >
                <div class="products-grid  mt-0 pt-0">
                  <alogira-list-product
                    class="mt-0"
                    v-for="(item, index) in items"
                    :key="item.id"
                    :item="item"
                    :index="index"
                  ></alogira-list-product>
                </div>
                <div class="page__mt-5" v-if="!isLastPage">
                  <infinite-loading
                    @infinite="
                      ($state) => {
                        refineNext();
                        page += 1;
                        $state.loaded();
                      }
                    "
                    v-if="page <= 3"
                  >
                    <div slot="spinner" class="my-2">
                      <loading-svg></loading-svg>
                    </div>
                    <div slot="no-more" class=""></div>
                    <div
                      slot="no-results"
                      class="my-2 flex items-center justify-center"
                    >
                      <img
                        :src="$asset('images/no-result.png')"
                        class="object-cover w-48 h-48"
                      />
                    </div>
                  </infinite-loading>
                  <div v-else class="product__show-more-button-container">
                    <button
                      class="product__show-more-button"
                      @click="refineNext"
                    >
                      {{ $page.$t.products.show_more }}
                    </button>
                  </div>
                </div>
              </div>
            </ais-infinite-hits>
          </div>
        </div>

      </div>

      <!-- <div class="products-grid mt-20 border-t bg-red-500">
      <ProductListItemComponent
        v-for="(item, index) in dataItems"
        :key="item.id"
        :item="item"
        :index="index"
      ></ProductListItemComponent>
    </div> -->
      <!-- <div class="page__mt-5">
      <infinite-loading @infinite="infiniteHandler" v-if="page <= 3">
        <div slot="spinner" class="my-2">
          <loading-svg></loading-svg>
        </div>
        <div slot="no-more" class=""></div>
        <div slot="no-results" class="my-2 flex items-center justify-center">
          <img
            :src="$asset('images/no-result.png')"
            class="object-cover w-48 h-48"
          />
        </div>
      </infinite-loading>
      <div v-else class="product__show-more-button-container">
        <button class="product__show-more-button" @click="infiniteHandler">
          {{ $page.$t.products.show_more }}
        </button>
      </div>
    </div> -->
    </div>
  </div>
</template>

<script>
import LoadingSvg from '../Page/LoadingSvg.vue'
// import ProductListItemComponent from '../Product/ProductListItemComponent.vue'
import AlogiraListProduct from '../../../components/Web/Product/AlogiraListProduct.vue'
import AlogiraSearchFilters from './List/AlogiraSearchFilters.vue'
import AlogriaPopFilters from './List/AlogriaPopFilters.vue'

export default {
  components: {
    AlogiraListProduct,
    // ProductListItemComponent,
    LoadingSvg,
    AlogiraSearchFilters,
    AlogriaPopFilters
  },
  props: {
    forceUpdate: {
      type: Number
    },
    params: {
      type: Object,
      default: {}
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
      console.log('working')
    },
    infiniteHandler ($state) {
      this.page += 1
      $state.loaded()
      //   const params = this.params
      //   params.page = this.page
      //   axios
      //     .get('/api/web/items/using_filters', {
      //       params: params
      //     })
      //     .then(({ data }) => {
      //       if (data.data.length) {
      //
      //         this.dataItems.push(...data.data)
      //         this.$emit('listUpdated', {
      //           total: data.total,
      //           data: this.dataItems
      //         })
      //         $state.loaded()
      //       } else {
      //         this.$emit('listUpdated', {
      //           total: data.total,
      //           data: this.dataItems
      //         })
      //         $state.complete()
      //       }
      //     })
    }
  }
}
</script>
