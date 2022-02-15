<template>
  <div>
    <div class="product__search-page">
      <div class="page__mt-2">
        <ais-configure :distinct="true" :hitsPerPage="21" />
        <ais-configure
          v-if="
            $page.props.categories_search_list.length &&
              $page.props.active_locale == 'ar'
          "
          :disjunctive-facets-refinements.camel="{
            category_ar_name: $page.props.categories_search_list,
          }"
        />
        <ais-configure
          v-if="
            $page.props.categories_search_list.length &&
              $page.props.active_locale == 'en'
          "
          :disjunctive-facets-refinements.camel="{
            category_name: $page.props.categories_search_list,
          }"
        />

        <div class="product__search-options">
          <alogria-pop-filters></alogria-pop-filters>
          <ais-numeric-menu
            :items="[
              {
                label: `${$page.props.$t.products.sorting_only_available}`,
                start: 1,
              },
            ]"
            attribute="available_qty"
            class="hidden md:block"
          >
            <div slot-scope="{ items, canRefine, refine, createURL }" class="">
              <div v-for="(item, index) in items" :key="index">
                <div
                  class="product__search-filter-value"
                  style="font-size: 15px; color: #575555"
                >
                  <toggle-button
                    v-model="item.isRefined"
                    :height="25"
                    :labels="{
                      checked: $page.props.$t.products.sorting_only_available,
                      unchecked: $page.props.$t.products.sorting_only_available,
                    }"
                    :width="120"
                    @change="refine(item.value)"
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
        :items="[
          {
            label: `${$page.props.$t.products.sorting_only_available}`,
            start: 1,
          },
        ]"
        attribute="available_qty"
        class="md:hidden"
      >
        <div slot-scope="{ items, canRefine, refine, createURL }" class="">
          <div v-for="(item, index) in items" :key="index">
            <div
              class="product__search-filter-value"
              style="font-size: 15px; color: #575555"
            >
              <toggle-button
                v-model="item.isRefined"
                :height="25"
                :labels="{
                  checked: $page.props.$t.products.sorting_only_available,
                  unchecked: $page.props.$t.products.sorting_only_available,
                }"
                :width="120"
                @change="refine(item.value)"
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
                v-for="item in items"
                :key="item.attribute"
                class="flex items-center gap-2 justify-center  flex-wrap"
              >
                <inertia-link
                  v-for="refinement in item.refinements"
                  :key="
                    [
                      refinement.attribute,
                      refinement.type,
                      refinement.value,
                      refinement.operator,
                    ].join(':')
                  "
                  :href="createURL(refinement)"
                >
                  <el-tag
                    closable
                    effect="dark"
                    @close="item.refine(refinement)"
                  >
                    {{ refinement.label }} ({{ refinement.count }})
                  </el-tag>
                </inertia-link>
              </div>

              <ais-clear-refinements>
                <span slot-scope="{ canRefine, refine, createURL }">
                  <inertia-link
                    v-if="
                      $page.props.categories_search_list.length == 0 &&
                        canRefine
                    "
                    :href="createURL()"
                  >
                    <el-tag effect="dark" type="danger">
                      {{ $page.props.$t.products.remove_all }}
                    </el-tag>
                  </inertia-link>
                  <inertia-link
                    v-else-if="canRefine"
                    href="/web/items/search/results"
                  >
                    <el-tag effect="dark" type="danger">
                      {{ $page.props.$t.products.remove_all }}
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
            {{ $page.props.$t.products.products_count }} ({{ nbHits }})
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
            {{ $page.props.$t.products.products_count }} ({{ nbHits }})

            <div
              v-if="nbHits == 0"
              slot="no-results"
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
            <ais-infinite-hits :escapeHTML="false" :show-previous="true">
              <div
                slot-scope="{ items, refinePrevious, refineNext, isLastPage }"
              >
                <div class="products-grid mt-0 pt-0">
                  <alogira-list-product
                    v-for="(item, index) in items"
                    :key="item.id"
                    :index="index"
                    :item="item"
                    class="mt-0"
                  ></alogira-list-product>
                </div>
                <div v-if="!isLastPage" class="page__mt-5">
                  <infinite-loading
                    v-if="page <= 3"
                    @infinite="
                      ($state) => {
                        refineNext();
                        page += 1;
                        $state.loaded();
                      }
                    "
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
                      {{ $page.props.$t.products.show_more }}
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
import LoadingSvg from "../Layout/LoadingSvg.vue";
import AlogiraListProduct from "./AlogiraListProduct.vue";
import AlogriaPopFilters from "./AlogriaPopFilters.vue";
import AlogriaPopSorting from "./AlogriaPopSorting.vue";

export default {
  components: {
    AlogiraListProduct,
    LoadingSvg,
    AlogriaPopFilters,
    AlogriaPopSorting,
  },

  data() {
    return {
      page: 1,
      dataItems: [],
    };
  },

  methods: {
    infiniteHandler($state) {
      this.page += 1;
      $state.loaded();
    },
  },
};
</script>

<style scoped></style>
