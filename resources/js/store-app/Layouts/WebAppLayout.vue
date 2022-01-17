<template>
  <div>
    <loading
      :active.sync="isPageLoading"
      :can-cancel="false"
      :is-full-page="true"
      :opacity="1"
    ></loading>
    <ais-instant-search
      :index-name="$page.props.algolia_items_search_as"
      :search-client="searchClient"
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
                class="container"
                v-if="
                  !isSearchPage(
                    hierarchicalFacetsRefinements,
                    numericRefinements,
                    tagRefinements,
                    query,
                    hits
                  )
                "
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

        <footer class="text-center footer-section" v-if="!isLoading">
          <div class="container">
            <div class="row">
              <div class="col-lg-3">
                <div class="footer-left">
                  <div class="footer-logo">
                    <a href="#"
                      ><img
                        :src="$page.props.active_logo"
                        class="page__footer-icon"
                        alt=""
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
                        href="https://maps.app.goo.gl/ZwuXJRrZkMhYt5wY6"
                        class="text-center flex items-center justify-center"
                        target="_blank"
                      >
                        <img
                          :src="
                            $asset('web_assets/template/img/our-location.png')
                          "
                          class="object-cover"
                          alt=""
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
                        href="/web/content/about"
                        style="

                          font-size: 18px;

                        "
                        class="footer_item"
                        >{{ $page.props.$t.footer.about_us }}</a
                      >
                    </li>
                    <li class="">
                      <a
                        href="/web/content/contact"
                        style="
                          font-size: 18px;
                        "
                        class="footer_item"
                        >{{ $page.props.$t.footer.contact }}</a
                      >
                    </li>
                    <li class="">
                      <a
                        href="/web/content/privacy"
                        style="
                          font-size: 18px;
                        "
                        class="footer_item"
                        >{{ $page.props.$t.footer.privacy }}</a
                      >
                    </li>
                    <li class="">
                      <a
                        href="/web/content/terms"
                        style="
                          font-size: 18px;
                        "
                        class="footer_item"
                        >{{ $page.props.$t.footer.terms }}</a
                      >
                    </li>
                    <li style="" class="flex items-center justify-center mt-2">
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
                      type="text"
                      :placeholder="$page.props.$t.footer.your_email"
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

export default {
  components: {
    HeaderComponent,
    ProductSearchResultListComponent,
    Loading,
  },
  name: "WebAppLayout",
  data() {
    return {
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
