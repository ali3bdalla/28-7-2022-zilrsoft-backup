<template>
  <div>
    <loading
      :active.sync="isPageLoading"
      :can-cancel="false"
      :is-full-page="true"
      :opacity="1"
    ></loading>
    <ais-instant-search
      :index-name="$page.algolia_items_search_as"
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
                <alogira-product-result-list></alogira-product-result-list>
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

        <footer class="text-center footer-section">
          <div class="container">
            <div class="row">
              <div class="col-lg-3">
                <div class="footer-left">
                  <div class="footer-logo">
                    <a href="#"
                      ><img
                        :src="$page.active_logo"
                        class="page__footer-icon"
                        alt=""
                    /></a>
                  </div>
                  <ul>
                    <li>
                      {{ $page.$t.footer.phone }}:
                      <div style="direction: ltr !important">
                        {{ $page.app.msbrshop.phone_number }}
                      </div>
                    </li>
                    <li class="mt-2">
                      {{ $page.$t.footer.email }}:
                      <div>{{ $page.app.msbrshop.email_address }}</div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="footer-widget">
                  <ul>
                    <li class="text-center flex items-center justify-center">
                      <a href="https://maps.app.goo.gl/ZwuXJRrZkMhYt5wY6" class="text-center flex items-center justify-center" target="_blank">
                        <img
                        :src="$asset('web_assets/template/img/our-location.png')" class="object-cover"
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
                        >{{ $page.$t.footer.about_us }}</a
                      >
                    </li>
                    <li class="">
                      <a
                        href="/web/content/contact"
                        style="
                          font-size: 18px;
                        "
                        class="footer_item"
                        >{{ $page.$t.footer.contact }}</a
                      >
                    </li>
                    <li class="">
                      <a
                        href="/web/content/privacy"
                        style="
                          font-size: 18px;
                        "
                        class="footer_item"
                        >{{ $page.$t.footer.privacy }}</a
                      >
                    </li>
                    <li class="">
                      <a
                        href="/web/content/terms"
                        style="
                          font-size: 18px;
                        "
                        class="footer_item"
                        >{{ $page.$t.footer.terms }}</a
                      >
                    </li>
                    <li style="" class="flex items-center justify-center mt-2">
                      <img
                        :src="$asset('web_assets/template/img/payment-method.png')" alt=""/>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="newslatter-item">
                  <p>{{ $page.$t.footer.join_news_letter_bio }}</p>
                  <form action="#" class="subscribe-form">
                    <input
                      type="text"
                      :placeholder="$page.$t.footer.your_email"
                    />
                    <button type="button">
                      {{ $page.$t.footer.subscribe }}
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
                    {{ $page.$t.footer.copyright_saved }}
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
import HeaderComponent from '../Components/Layout/HeaderComponent'
import algoliasearch from 'algoliasearch/lite'
import { history as historyRouter } from 'instantsearch.js/es/lib/routers'
import { simple as simpleStateMapping } from 'instantsearch.js/es/lib/stateMappings'
import 'instantsearch.css/themes/algolia-min.css'
import AlogiraProductResultList from '../Components/Product/AlogiraProductResultList.vue'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

export default {
  components: {
    HeaderComponent,
    AlogiraProductResultList,
    Loading
  },
  name: 'WebAppLayout',
  data () {
    return {
      searchClient: algoliasearch(
        this.$page.algolia_app_key,
        this.$page.aloglia_daily_search_key
      ),
      routing: {
        router: historyRouter(),
        stateMapping: simpleStateMapping()
      },
      isLoading: true,
      isPageLoading: true
    }
  },
  mounted () {
    setTimeout(() => {
      this.isPageLoading = false
    }, 750)
  },
  methods: {
    isSearchPage (
      hierarchicalFacetsRefinements,
      numericRefinements,
      tagRefinements,
      query,
      hits
    ) {
      const isSearchPage = !query.length
      // JSON.stringify(numericRefinements.online_offer_price) === '{}' && JSON.stringify(numericRefinements.available_qty) === '{}' && !tagRefinements.length &&
      this.isLoading = false
      return isSearchPage
    }
  }
}
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
