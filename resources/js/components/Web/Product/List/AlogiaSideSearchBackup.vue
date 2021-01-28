    <ais-menu
      :attribute="
        $page.active_locale == 'en' ? 'category_name' : 'category_ar_name'
      "
    >
      <div
        class="border filter-widget p-3 pt-0"
        v-if="items.length"
        slot-scope="{
          items,
          canToggleShowMore,
          isShowingMore,
          toggleShowMore,
          refine,
          createURL,
        }"
      >
        <h6 class="fw-title">
          {{ $page.$t.products.category }}
        </h6>
        <div class="fw-brand-check">
          <!-- <div class="row">
                    class="col-md-4 col-4" -->
          <div v-for="item in items" :key="item.value">
            <div
              class="product__search-filter-value"
              style="font-size: 15px; color: #575555"
            >
              <input
                :checked="item.isRefined"
                type="checkbox"
                @change="refine(item.value)"
              />

              {{ item.value }}
              <ais-highlight attribute="item" :hit="item" />
              <span :class="[item.isRefined ? 'bg-gray-200' : '']">
                ({{ item.count.toLocaleString() }})
              </span>
            </div>
          </div>
        </div>
        <!-- </div> -->

        <div class="mt-2">
          <a
            href="#"
            v-if="!isShowingMore && canToggleShowMore"
            @click.prevent="toggleShowMore"
            class="text-sm text-gray-800"
            >عرض المزيد</a
          >
        </div>
      </div>
    </ais-menu>

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
        <h6 class="fw-title">
          {{ $page.$t.products.price }}
        </h6>
        <div class="fw-brand-check">
          <!-- <div class="row"> -->
          <!-- class="col-md-4 col-4"
                     row -->
          <div v-for="item in items" :key="item.label">
            <div
              class="product__search-filter-value"
              style="font-size: 15px; color: #575555"
            >
              <input
                :checked="item.isRefined"
                type="checkbox"
                @change="refine(item.value)"
              />
              {{ item.label }}
            </div>
          </div>
          <!-- </div> -->
        </div>
      </div>
      <!-- </div> -->
    </ais-numeric-menu>
    <div
      v-if="shouldBeAvailable(filter)"
      v-for="filter in $page.alogia_search_filters"
      :key="filter"
    >
      <ais-refinement-list
        :class-names="{
          'ais-RefinementList-list': '',
          'ais-RefinementList-showMore': 'hidden',
        }"

        :transform-items="applyTransformation"
        :show-more="true"
        :attribute="filter"
        :show-more-limit="20"
      >
      <!-- sorty-by="count:desc" -->
        <div
          class="border filter-widget p-3 pt-0"
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
          <h6 class="fw-title">
            {{ actulFilterName(filter) }}
          </h6>
          <div class="fw-brand-check">
            <!-- <div class="row"> -->
            <!-- col-md-4 col-4 -->
            <div class="" v-for="item in items" :key="item.value">
              <div
                class="product__search-filter-value"
                style="font-size: 15px; color: #575555"
              >
                <!-- <input
                  :checked="item.isRefined"
                  type="checkbox"
                  @change="refine(item.value)"
                /> -->
                <el-switch
                  v-model="item.isRefined"
                  @change="refine(item.value)"
                  active-color="#13ce66"
                  inactive-color="#ff4949"
                >
                </el-switch>
                <!-- {{ item.value }} -->

                <span :class="[item.isRefined ? 'bg-gray-200 px-2 py-1' : '']"><ais-highlight attribute="item" :hit="item" /> ({{ item.count.toLocaleString() }})</span>
              </div>
            </div>
          </div>
          <!-- </div> -->

          <div class="mt-2">
            <a
              href="#"
              v-if="!isShowingMore && canToggleShowMore"
              @click.prevent="toggleShowMore"
              class="text-sm text-gray-800"
              >عرض المزيد</a
            >
          </div>
        </div>
      </ais-refinement-list>
    </div>
