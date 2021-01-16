<template>
  <div>
    <div
      class="container-fluid filters-layout-modal loading-progress"
      v-if="isLoading"
      width="100%"
      height="100%"
      style="overflow-y: scroll"
    >
      <circle-spin class="loading" v-show="isLoading"></circle-spin>
    </div>
    <div v-else>
      <HeaderComponent>
        <template v-slot:navbarListItems>
          <slot name="navbarItems"></slot>
        </template>
      </HeaderComponent>
      <div style="background-color: #f9f9f9;"> 
        <slot></slot>
      </div>

      <footer class="text-center footer-section">
        <div class="container">
          <div class="row">
            <div class="col-lg-3">
              <div class="footer-left">
                <div class="footer-logo">
                  <a href="#"
                    ><img
                      :src="$asset('images/logo_hd.png')"
                      class="page__footer-icon"
                      alt=""
                  /></a>
                </div>
                <ul>
                  <!-- <li>{{ __('store.footer.address')}}: {{ config('app.msbrshop.address') }}</li> -->
                  <li>
                    {{ $page.$t.footer.phone }}:
                    {{ $page.app.msbrshop.phone_number }}
                  </li>
                  <li>
                    {{ $page.$t.footer.email }}:
                    {{ $page.app.msbrshop.email_address }}
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="footer-widget">
                <!-- <h5>{{ $page.$t.footer.information }}</h5> -->
                <ul>
                  <li>
                    <a href="#">{{ $page.$t.footer.about_us }}</a>
                  </li>

                  <li>
                    <a href="#">{{ $page.$t.footer.contact }}</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="footer-widget">
                <!-- <h5>{{ __('store.footer.my_account') }}</h5> -->
                <ul>
                  <li>
                    <a href="#">{{ $page.$t.footer.my_account }}</a>
                  </li>
                  <li>
                    <a href="#">{{ $page.$t.footer.contact }}</a>
                  </li>
                  <li>
                    <a href="#">{{ $page.$t.footer.cart }}</a>
                  </li>
                  <li>
                    <a href="#">{{ $page.$t.footer.logout }}</a>
                  </li>
                  <!-- <li><a href="#">Shop</a></li> -->
                </ul>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="newslatter-item">
                <!-- <h5>{{ __('store.footer.join_news_letter') }}</h5> -->
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
                <div class="flex items-center justify-center payment-pic">
                  <img
                    :src="$asset('web_assets/template/img/payment-method.png')"
                    alt=""
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<script>
import HeaderComponent from "../components/Web/Page/HeaderComponent";

export default {
  components: {
    HeaderComponent,
  },
  name: "WebAppLayout",
  data() {
    return {
      isLoading: true,
    };
  },

  mounted() {
    this.isLoading = false;
  },
};
</script>

<style scoped>
</style>