<template>
  <div>
    <loading
      :active.sync="isPageLoading"
      :can-cancel="false"
      :is-full-page="true"
      :opacity="1"
    ></loading>
    <ais-instant-search
      :routing="routing"
      :index-name="$page.props.algolia_items_search_as"
      :search-client="searchClient"
      :search-function="searchHook"
    >
      <div>
        <HeaderComponent>
          <template v-slot:navbarListItems>
            <slot name="navbarItems"></slot>
          </template>
        </HeaderComponent>
        <div style="background-color: #f9f9f9">
          <ais-state-results>
            <template
              slot-scope="{
                state: {
                  hierarchicalFacetsRefinements,
                  numericRefinements,
                  tagRefinements,
                  query,
                  hits,
                },
              }"
            >
              <div
                v-if="
                  !isSearchPage(
                    hierarchicalFacetsRefinements,
                    numericRefinements,
                    tagRefinements,
                    query,
                    hits
                  )
                "
                class="container"
              >
                <ProductSearchResultListComponent></ProductSearchResultListComponent>
              </div>
              <slot
                v-if="
                  isSearchPage(
                    hierarchicalFacetsRefinements,
                    numericRefinements,
                    tagRefinements,
                    query,
                    hits
                  )
                "
              ></slot>
            </template>
          </ais-state-results>
        </div>

        <footer v-if="!isLoading" class="text-center footer-section">
          <div class="container">
            <div class="row">
              <div class="col-lg-3">
                <div class="footer-left">
                  <div class="footer-logo">
                    <a href="#"
                      ><img
                        :src="$page.props.active_logo"
                        alt=""
                        class="page__footer-icon"
                    /></a>
                  </div>
                  <ul>
                    <li>
                      {{ $page.props.$t.footer.phone }}:
                      <div style="direction: ltr !important">
                        {{ $page.props.app.msbrshop.phone_number }}
                      </div>
                    </li>
                    <li class="mt-2">
                      {{ $page.props.$t.footer.email }}:
                      <div>{{ $page.props.app.msbrshop.email_address }}</div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="footer-widget">
                  <ul>
                    <li class="text-center flex items-center justify-center">
                      <a
                        class="text-center flex items-center justify-center"
                        href="https://maps.app.goo.gl/ZwuXJRrZkMhYt5wY6"
                        target="_blank"
                      >
                        <img
                          :src="
                            $asset('web_assets/template/img/our-location.png')
                          "
                          alt=""
                          class="object-cover"
                        />
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="footer-widget">
                  <ul>
                    <li class="-mt-1">
                      <a
                        class="footer_item"
                        href="/web/content/about"
                        style="

                          font-size: 18px;

                        "
                        >{{ $page.props.$t.footer.about_us }}</a
                      >
                    </li>
                    <li class="">
                      <a
                        class="footer_item"
                        href="/web/content/contact"
                        style="
                          font-size: 18px;
                        "
                        >{{ $page.props.$t.footer.contact }}</a
                      >
                    </li>
                    <li class="">
                      <a
                        class="footer_item"
                        href="/web/content/privacy"
                        style="
                          font-size: 18px;
                        "
                        >{{ $page.props.$t.footer.privacy }}</a
                      >
                    </li>
                    <li class="">
                      <a
                        class="footer_item"
                        href="/web/content/terms"
                        style="
                          font-size: 18px;
                        "
                        >{{ $page.props.$t.footer.terms }}</a
                      >
                    </li>
                    <li class="flex items-center justify-center mt-2" style="">
                      <img
                        :src="
                          $asset('web_assets/template/img/payment-method.png')
                        "
                        alt=""
                      />
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="newslatter-item">
                  <p>{{ $page.props.$t.footer.join_news_letter_bio }}</p>
                  <form action="#" class="subscribe-form">
                    <input
                      :placeholder="$page.props.$t.footer.your_email"
                      type="text"
                    />
                    <button type="button">
                      {{ $page.props.$t.footer.subscribe }}
                    </button>
                  </form>
                </div>

                <div class="footer-left">
                  <div class="footer-social">
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-instagram"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-pinterest"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="copyright-reserved">
            <div class="container">
              <div class="row">
                <div class="col-lg-12">
                  <div class="copyright-text">
                    {{ $page.props.$t.footer.copyright_saved }}
                  </div>
                  <div
                    class="flex items-center justify-center payment-pic"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </ais-instant-search>
  </div>
</template>

<script>
import HeaderComponent from "../Components/Layout/HeaderComponent";
import algoliasearch from "algoliasearch/lite";
import { history as historyRouter } from "instantsearch.js/es/lib/routers";
import { simple as simpleStateMapping } from "instantsearch.js/es/lib/stateMappings";
import "instantsearch.css/themes/algolia-min.css";
import ProductSearchResultListComponent from "../Components/Product/ProductSearchResultListComponent.vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { history } from "instantsearch.js/es/lib/routers";
import { simple } from "instantsearch.js/es/lib/stateMappings";
export default {
  components: {
    HeaderComponent,
    ProductSearchResultListComponent,
    Loading,
  },
  name: "WebAppLayout",
  data() {
    return {
      routing: {
        router: history(),
        stateMapping: simple(),
      },
      initialUiState: {
        query: "",
        // refinementList: {
        //   colors: ["white", "black"],
        // },
        // configure: {
        //   distinct: true,
        // },
        // menu: {
        //   category: "Decoration",
        // },
        // hierarchicalMenu: {
        //   categories: ["Decoration > Clocks"],
        // },
        numericMenu: {
          available_qty: "1:",
        },
        // ratingMenu: {
        //   rating: 4,
        // },
        // range: {
        //   ageInYears: "2:10",
        // },
        // toggle: {
        //   freeShipping: true,
        // },
        // geoSearch: {
        //   boundingBox: "47.3165,4.9665,47.3424,5.0201",
        // },
        // sortBy: "most_popular_index",
        // page: 2,
        // hitsPerPage: 20,
      },
      searchClient: algoliasearch(
        this.$page.props.algolia_app_key,
        this.$page.props.aloglia_daily_search_key
      ),
      routing: {
        router: historyRouter(),
        stateMapping: simpleStateMapping(),
      },
      isLoading: true,
      isPageLoading: true,
    };
  },
  mounted() {
    this.isPageLoading = false;
  },

  methods: {
    searchHook(helper) {
      const urlSearchParams = new URLSearchParams(window.location.search);
      const params = Object.fromEntries(urlSearchParams.entries());

      if (!params.["items[numericMenu][available_qty]"]) {
        helper = helper.addNumericRefinement("available_qty", ">=", 1);
      }
      helper.search();
    },
    isSearchPage(
      hierarchicalFacetsRefinements,
      numericRefinements,
      tagRefinements,
      query,
      hits
    ) {
      const isSearchPage = !query.length;
      this.isLoading = false;
      return isSearchPage;
    },
  },
};
</script>

<style>
.footer_item {
  background-color: #86bbf7 !important;

  font-size: 18px;
  color: white;
  border-radius: 5px;
  margin: 1px 0px;
}
</style>
