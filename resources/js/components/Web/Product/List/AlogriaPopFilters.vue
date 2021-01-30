<template>
  <div class="product__search-filters" v-click-outside="hide">
    <button
      @click="show"
      class="product__search-option-button"
      style="background: rgb(87, 87, 87)"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="product__search-option-icon"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
        />
      </svg>
      {{ $page.$t.products.filters }}
    </button>
    <div ref="filters_pop_model" class="filters_pop_model" name="alogira-search-filters-modal">
        <div style="overflow-y: scroll; height: 100vh !important">
          <div class="container mb-2">
            <div class="row page__mt-5">
              <div class="col-md-6 col-6 text-center">
                <button @click="hide" class="btn btn-primary applyBtn px-5">
                  {{ $page.$t.products.apply }}
                </button>
              </div>
              <div class="col-md-6 col-6 text-center">
                <button
                  @click="hide"
                  class="btn btn-default resetBtn bg-web-primary px-5"
                >
                  {{ $page.$t.products.reset }}
                </button>
              </div>
            </div>
          </div>

          <div class="container-fluid filters-layout-modal">
            <div class="grid grid-cols-1">

              <div   class=" w-full" >
                   <ais-numeric-menu
        attribute="online_offer_price"
        :items="[
          {
            label: `${$page.$t.products.all} ${$page.$t.products.prices}`,
          },
          { label: `1-100 ${$page.$t.products.sar}`, end: 100 },
          {
            label: `101-1000 ${$page.$t.products.sar}`,
            start: 101,
            end: 1000,
          },
          {
            label: `1001-5000 ${$page.$t.products.sar}`,
            start: 1001,
            end: 5000,
          },
          {
            label: `5001-20000 ${$page.$t.products.sar}`,
            start: 5001,
            end: 20000,
          },
          {
            label: `${$page.$t.products.more_than} 2000 ${$page.$t.products.sar}`,
            start: 20000,
          },
        ]"
      >
        <div
          class="border filter-widget p-3 pt-0"
          slot-scope="{ items, canRefine, refine, createURL }"
        >
                              <h6  class=" w-full text-center py-1 text-2xl rounded-full  border" style="background-color: rgb(249, 249, 249);font-size:18px !important;border-color: #d2e8ff !important; border-width: 2px !important" >
            {{ $page.$t.products.price }}
          </h6>
                              <div class="mb-1 fw-brand-check pt-3 grid  grid-cols-1 md:grid-cols-4 gap-0 md:gap-2">
            <!-- <div class="row"> -->
            <!-- class="col-md-4 col-4"
                     row -->
            <div v-for="(item,index) in items" :key="index">
              <div
                class="product__search-filter-value"
                style="font-size: 15px; color: #575555"
              >
              <el-switch
                                        v-model="item.isRefined"
                                        active-color="#13ce66"
 @change="refine(item.value)"
                                      >
                                      </el-switch>
                                      <span class="px-2 py-1" :class="[item.isRefined ? '' : '']"
                                        ><ais-highlight attribute="item" :hit="item" /> ({{item.label}})</span
                                      >
                <!-- <input
                  :checked="item.isRefined"
                  type="checkbox"
                  @change="refine(item.value)"
                />
                {{ item.label }} -->
              </div>
            </div>
            <!-- </div> -->
          </div>
        </div>
        <!-- </div> -->
      </ais-numeric-menu>

                  <ais-refinement-list
                  :class-names="{
                    'ais-RefinementList-list': '',
                    'ais-RefinementList-showMore': 'hidden',
                  }"
                  :transform-items="applyTransformation"
                  :show-more="false"
                  :attribute=" $page.active_locale == 'en' ? 'category_name' : 'category_ar_name'"
                  :show-more-limit="20"
                  class="w-full "
                  >
                      <div
                        class="    w-full pt-0 mt-3"
                        v-if="items.length"
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
                              <h6  class=" w-full text-center py-1 text-2xl rounded-full  border" style="background-color: rgb(249, 249, 249);font-size:18px !important;border-color: #d2e8ff !important; border-width: 2px !important" >
                                {{ $page.$t.products.category }}
                              </h6>
                              <div class="mb-1 fw-brand-check pt-3 grid  grid-cols-1 md:grid-cols-4 gap-0 md:gap-2">

                                <div class="" v-for="item in items" :key="item.value">
                                  <div
                                  class="product__search-filter-value"
                                  style="font-size: 15px; color: #575555"
                                  >
                                      <el-switch
                                        v-model="item.isRefined"
                                        @change="refine(item.value)"
                                        active-color="#13ce66"

                                      >
                                      </el-switch>
                                      <span class="px-2 py-1" :class="[item.isRefined ? '' : '']"
                                        ><ais-highlight attribute="item" :hit="item" /> ({{item.count.toLocaleString()}})</span
                                      >
                                  </div>
                                </div>
                              </div>

                      </div>

                  </ais-refinement-list>
                </div>

                <div v-if="shouldBeAvailable(filter)" v-for="filter in $page.algolia_search_filters"  :key="filter" class=" w-full" >
                  <ais-refinement-list
                  :class-names="{
                    'ais-RefinementList-list': '',
                    'ais-RefinementList-showMore': 'hidden',
                  }"
                  :transform-items="applyTransformation"
                  :show-more="false"
                  :attribute="filter"
                  :show-more-limit="20"
                  class="w-full "
                  >
                      <div
                        class="    w-full pt-0 mt-3"
                        v-if="items.length"
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
                              <h6  class=" w-full text-center py-1 text-2xl rounded-full  border" style="background-color: rgb(249, 249, 249);font-size:18px !important;border-color: #d2e8ff !important; border-width: 2px !important" >
                                {{ actulFilterName(filter) }}
                              </h6>
                              <div class="mb-1 fw-brand-check pt-3 grid  grid-cols-1 md:grid-cols-4 gap-0 md:gap-2">

                                <div class="" v-for="item in items" :key="item.value">
                                  <div
                                  class="product__search-filter-value"
                                  style="font-size: 15px; color: #575555"
                                  >
                                      <el-switch
                                        v-model="item.isRefined"
                                        @change="refine(item.value)"
                                        active-color="#13ce66"

                                      >
                                      </el-switch>
                                      <span class="px-2 py-1" :class="[item.isRefined ? '' : '']"
                                        ><ais-highlight attribute="item" :hit="item" /> ({{item.count.toLocaleString()}})</span
                                      >
                                  </div>
                                </div>
                              </div>

                      </div>

                  </ais-refinement-list>
                </div>

            </div>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    applyTransformation (items) {
      return items.map((item) => ({
        ...item,
        label: this.actulFilterName(item.name).toUpperCase()
      }))
    },
    actulFilterName (filename) {
      if (this.$page.active_locale === 'ar') { return `${filename}`.replace('ar_filters_', '') }
      return `${filename}`.replace('filters_', '')
    },

    shouldBeAvailable (filter) {
      if (this.$page.active_locale === 'ar') { return !filter.indexOf('ar_filters_') && filter !== 'category_name' }

      return filter.indexOf('ar_filters_') && filter !== 'category_ar_name'
    },
    hide () {
      this.$refs.filters_pop_model.style.display = 'none'
      // this.$modal.hide('alogira-search-filters-modal')
    },

    show () {
      this.$refs.filters_pop_model.style.display = 'block'
      // this.$modal.show('alogira-search-filters-modal')
    }
  }
}
</script>

<style >
.filter-widget {
  margin-bottom: 10px !important;
}
.vm--overlay {
  overflow-y: scroll;
}
.filters-layout-modal {
  padding-top: 5px;
}

.toggleButton {
  font-size: 20px;
  color: #888888;
  margin-right: 28px;
  position: relative;
  background: none;
  border: none;
  /*text-decoration: underline;*/
  display: flex;
  padding-top: 4px;
}

.applyBtn {
  height: 42px;
  border-radius: 17px;
  box-shadow: 1px 5px 7px #c1baba;
}

.resetBtn {
  height: 42px;
  border-radius: 17px;
  box-shadow: 2px 1px 7px #c1baba;
}

.fw-title {
  /*color: #777;*/
  color: #252424;

  font-size: 13px !important;
  text-transform: lowercase;
  /* border-bottom: 1px solid #e8e0e0; */
  padding-bottom: 0px;
  margin-bottom: 5px;
  margin-top: 20px;
}
.fw-brand-check {
  padding-left: 20px;
}

.container-fluid .filters-layout-modal .filter-widget {
  margin-bottom: 10px !important;
}

.loading-progress {
  padding-top: 10%;
}

.closeBtnClass {
  padding: 17px;
  padding-bottom: 0px;
  font-size: 19px;
  font-weight: bold;
  text-align: right;
}

.closeBtnClass i {
  font-size: 22px;
  color: #777;
  margin: 6px;
  border: 1px solid #eee;
  padding: 8px;
  border-radius: 50%;
  box-shadow: 0px 2px 5px #ddd;
  cursor: pointer;
}

.toGrayBg {
  background: #f9f9f9;
}

.filters_pop_model {
  position: fixed;
  top: 0;
  display:none;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 500;
  background: white;
}
</style>
