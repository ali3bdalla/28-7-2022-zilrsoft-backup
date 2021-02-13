
<template>
  <div>
    <div class="product__search-page">
      <div class="page__mt-2">
        <ais-configure
          v-if="$page.categories_search_list.length && $page.active_locale == 'ar'"
          :disjunctive-facets-refinements.camel="{
            category_ar_name: $page.categories_search_list
          }"
        />
         <ais-configure
          v-if="$page.categories_search_list.length  && $page.active_locale == 'en'"
          :disjunctive-facets-refinements.camel="{
            category_name: $page.categories_search_list
          }"
        />

        <div class="product__search-options">
          <alogria-pop-filters></alogria-pop-filters>
          <ais-numeric-menu
            class="hidden md:block"
            attribute="available_qty"
            :items="[
              {
                label: `${$page.$t.products.sorting_only_available}`,
                start: 1,
              },
            ]"
          >
            <div class="" slot-scope="{ items, canRefine, refine, createURL }">
              <div v-for="(item, index) in items" :key="index">
                <div
                  class="product__search-filter-value"
                  style="font-size: 15px; color: #575555"
                >
                  <toggle-button
                    @change="refine(item.value)"
                    :height="25"
                    :width="120"
                    v-model="item.isRefined"
                    :labels="{
                      checked: $page.$t.products.sorting_only_available,
                      unchecked: $page.$t.products.sorting_only_available,
                    }"
                  />
                </div>
              </div>
            </div>
          </ais-numeric-menu>
          <alogria-pop-sorting></alogria-pop-sorting>
        </div>
      </div>
    </div>
    <div
      class="product__search-page mt-3 flex items-center gap-3 justify-center flex-wrap"
    >
      <ais-numeric-menu
        class="md:hidden"
        attribute="available_qty"
        :items="[
          {
            label: `${$page.$t.products.sorting_only_available}`,
            start: 1,
          },
        ]"
      >
        <div class="" slot-scope="{ items, canRefine, refine, createURL }">
          <div v-for="(item, index) in items" :key="index">
            <div
              class="product__search-filter-value"
              style="font-size: 15px; color: #575555"
            >
              <toggle-button
                @change="refine(item.value)"
                :height="25"
                :width="120"
                v-model="item.isRefined"
                :labels="{
                  checked: $page.$t.products.sorting_only_available,
                  unchecked: $page.$t.products.sorting_only_available,
                }"
              />
            </div>
          </div>
        </div>
      </ais-numeric-menu>
    </div>
    <div
      class="product__search-page mt-3 flex items-center gap-3 justify-center"
    >
      <ais-current-refinements>
        <div slot-scope="{ items, createURL }">
          <div
            class="container pb-0 mb-0 flex items-center gap-3 justify-center"
          >
            <div class="flex-wrap flex items-center gap-3 justify-center">
              <div
                class="flex items-center gap-2 justify-center  flex-wrap"
                v-for="item in items"
                :key="item.attribute"
              >
                <inertia-link
                  :href="createURL(refinement)"
                  v-for="refinement in item.refinements"
                  :key="
                    [
                      refinement.attribute,
                      refinement.type,
                      refinement.value,
                      refinement.operator,
                    ].join(':')
                  "
                >
                  <el-tag
                    effect="dark"
                    closable
                    @close="item.refine(refinement)"
                  >
                    {{ refinement.label }} ({{ refinement.count }})
                  </el-tag>
                  <!-- <el-tag @close="item.refine(refinement)" v-if="refinement.attribute == 'available_qty'" effect="dark" closable>
                    {{ $page.$t.products.sorting_only_available }}
                  </el-tag> -->
                                      <!-- v-if="refinement.attribute !== 'available_qty'" -->

                </inertia-link>
              </div>

              <ais-clear-refinements>
                <span slot-scope="{ canRefine, refine, createURL }">
                  <inertia-link  v-if="$page.categories_search_list.length == 0 && canRefine" :href="createURL()">
                    <el-tag effect="dark" type="danger">
                      {{ $page.$t.products.remove_all }}
                    </el-tag>
                  </inertia-link>
                  <inertia-link  v-else-if="canRefine" href="/web/items/search/results">
                    <el-tag effect="dark" type="danger">
                      {{ $page.$t.products.remove_all }}
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
      <!-- <h1 class="home__products-count">
        <ais-stats>
          <p
            slot-scope="{
              hitsPerPage,
              nbPages,
              nbHits,
              page,
              processingTimeMS,
              query,
            }"
          >
            {{ $page.$t.products.products_count }} ({{ nbHits }})
          </p>
        </ais-stats>
      </h1> -->
      <h1 class="home__products-count">
        <ais-stats>
          <div
            slot-scope="{
              hitsPerPage,
              nbPages,
              nbHits,
              page,
              processingTimeMS,
              query,
            }"
          >
            {{ $page.$t.products.products_count }} ({{ nbHits }})

            <div
              slot="no-results"
              v-if="nbHits == 0"
              class="my-2 flex items-center justify-center"
            >
              <img
                :src="$asset('images/no-result.png')"
                class="object-cover w-48 h-48"
              />
            </div>
          </div>
        </ais-stats>
      </h1>

      <div class="flex gap-3 mt-3">
        <div class="flex-1">
          <div class="mt-0 pt-0">
            <ais-configure :hitsPerPage="21" />
            <ais-infinite-hits :escapeHTML="false" :show-previous="true">
              <div
                slot-scope="{ items, refinePrevious, refineNext, isLastPage }"
              >
                <div class="products-grid mt-0 pt-0">
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
    </div>
  </div>
</template>

<script>
import LoadingSvg from '../Page/LoadingSvg.vue'
import AlogiraListProduct from '../../../components/Web/Product/AlogiraListProduct.vue'
import AlogriaPopFilters from './List/AlogriaPopFilters.vue'
import AlogriaPopSorting from './List/AlogriaPopSorting.vue'

export default {
  components: {
    AlogiraListProduct,
    LoadingSvg,
    AlogriaPopFilters,
    AlogriaPopSorting
  },

  data () {
    return {
      page: 1,
      dataItems: []
    }
  },

  methods: {
    infiniteHandler ($state) {
      this.page += 1
      $state.loaded()
    }
  }
}
</script>

<style scoped>
</style>
