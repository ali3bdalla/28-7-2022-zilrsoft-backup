
<template>
  <div>
    <div class="product__search-page">
      <div class="page__mt-2">
        <div class="product__search-options">
          <alogria-pop-filters></alogria-pop-filters>
        <ais-stats>
  <p slot-scope="{ hitsPerPage, nbPages, nbHits, page, processingTimeMS, query }">
    <!-- Page {{ page + 1 }} of {{ nbPages }} with {{ hitsPerPage }} hits per page  -
    {{ nbHits }} hits retrieved in {{ processingTimeMS }}ms for <q>{{ query }}</q> -->
    {{ nbHits }}
  </p>
</ais-stats>

           <ais-numeric-menu
        attribute="available_qty"
        :items="[
          { label: `${$page.$t.products.sorting_only_available}`, start: 1 },
        ]"
      >
        <div
          class=""
          slot-scope="{ items, canRefine, refine, createURL }"
        >

            <div v-for="(item,index) in items" :key="index">
              <div
                class="product__search-filter-value"
                style="font-size: 15px; color: #575555"
              >
                  <toggle-button @change="refine(item.value)" :height="25" :width="120" v-model="item.isRefined" :labels="{checked: $page.$t.products.sorting_only_available, unchecked: $page.$t.products.sorting_only_available}"/>
            </div>
            <!-- </div> -->
          </div>
        </div>
        <!-- </div> -->
      </ais-numeric-menu>
      <!-- <alogria-pop-sorting></alogria-pop-sorting> -->
        </div>
      </div>
    </div>

    <div class="product__search-page mt-3  flex items-center gap-3 justify-center">

        <ais-query-rule-context :tracked-filters="{
         available_qty:(values)=> {
           return [];
         }
      }" />

        <ais-current-refinements>

    <div  slot-scope="{ items, createURL }"
          >
          <div class="container pb-0 mb-0 flex items-center gap-3 justify-center">
            <div class="flex-wrap flex items-center gap-3 justify-center">

              <div class=" flex items-center gap-2 justify-center" v-for="item in items" :key="item.attribute" >
                    <inertia-link :href="createURL(refinement)" v-for="refinement in item.refinements"
                      :key="[
                    refinement.attribute,
                    refinement.type,
                    refinement.value,
                    refinement.operator
                  ].join(':')">
                        <el-tag
                      effect="dark"
                        closable>

                        {{refinement.label}} ({{ refinement.count }})
                      </el-tag>
                    </inertia-link>
              </div>

              <ais-clear-refinements >
                  <span slot-scope="{ canRefine, refine, createURL }">
                    <inertia-link v-if="canRefine"  :href="createURL()">
                        <el-tag
                      effect="dark"
                      type="danger"
                        >

                        {{  $page.$t.products.remove_all }}
                      </el-tag>
                    </inertia-link>

                  </span>
              </ais-clear-refinements>
            </div>
            </div>
          </div>

</ais-current-refinements>
    </div>
    <div class="product__search-page">

      <div class="flex gap-3">

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
// import AlogiraSearchFilters from './List/AlogiraSearchFilters.vue'
import AlogriaPopFilters from './List/AlogriaPopFilters.vue'
import AlogriaPopSorting from './List/AlogriaPopSorting.vue'

export default {
  components: {
    AlogiraListProduct,
    // ProductListItemComponent,
    LoadingSvg,
    // AlogiraSearchFilters,
    AlogriaPopFilters,
    AlogriaPopSorting
  },
  props: {
    // forceUpdate: {
    //   type: Number
    // },
    // params: {
    //   type: Object,
    //   default: {}
    // }
  },
  data () {
    return {
      page: 1,
      dataItems: []
    }
  },
  watch: {
    // forceUpdate (value) {
    //   this.page = 1
    //   this.dataItems = []
    //   this.infiniteHandler()
    // },
    // params: {
    //   deep: true,
    //   handler (value) {
    //     this.page = 1
    //     this.dataItems = []
    //     this.infiniteHandler()
    //   }
    // }
  },
  methods: {
    // paramsUpdated () {
    //   console.log('working')
    // },
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

<style scoped>

</style>
