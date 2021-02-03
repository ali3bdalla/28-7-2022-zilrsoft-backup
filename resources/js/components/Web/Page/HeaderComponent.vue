<template>
  <header class="header-section">
    <div class="fixedHeader" :class="{ fixedHeader__hide: !showHiddenNavbar }">
      <div class="pb-0" style="background: #f9f9f9">
        <div class="container">
          <div class="inner-header">
            <div class="page__header-navbar">
              <div class="page__header-logo-section">
                <div class="logo">
                  <a href="/web">
                    <img
                      alt=""
                      :src="$page.active_logo"
                      :style="
                        $page.active_locale == 'en'
                          ? 'width: 6rem; padding-top: 2px'
                          : 'width: 5rem; padding-top: 2px'
                      "
                    />
                  </a>
                </div>
              </div>
              <HeaderQuickCartComponent></HeaderQuickCartComponent>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="page__header-buttons">
      <div class="container">
        <div class="page__header-buttons-container">
          <div class="page__header-buttons__item-container">
            <a
              class="page__header-buttons__item login-panel"
              href="/web/sign_in"
              v-if="!$page.client_logged"
              ><i class="fa fa-user" style="font-size: 22px"></i> &nbsp;
              {{ $page.$t.profile.login }}</a
            >

            <a
              v-else
              class="page__header-buttons__item login-panel"
              href="/web/profile"
              ><i class="fa fa-user" style="font-size: 22px"></i> &nbsp;
              {{ $page.client.name }}</a
            >
          </div>

          <LanguageSwitcher></LanguageSwitcher>
        </div>
      </div>
    </div>

    <div class="pb-3" style="background: #f9f9f9">
      <div class="container">
        <div class="inner-header">
          <div class="page__header-navbar">
            <div class="page__header-logo-section">
              <div class="logo">
                <a href="/web">
                  <img
                    alt=""
                    :src="$page.active_logo"
                    :style="
                      $page.active_locale == 'en'
                        ? 'width: 7rem; padding-top: 2px'
                        : 'width: 6rem; padding-top: 2px'
                    "
                  />
                </a>
              </div>
            </div>

            <div
              class="page__header-search-field page__header-search-field__f"
              style="width: 100%"
            >
              <header-seach-input-component></header-seach-input-component>
            </div>
            <HeaderQuickCartComponent></HeaderQuickCartComponent>
          </div>
        </div>
        <div class="page__header-search-field__h inner-header">
          <div class="page__header-search-field__f">
            <header-seach-input-component></header-seach-input-component>
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="nav-item">
      <div class="container">
        <div class="nav-depart">
          <div class="depart-btn">
            <i class="ti-menu"></i>
            <span>{{ $page.$t.header.categories }}</span>
            <ul class="depart-hover">
              <li v-for="category in $page.main_categories" :key="category.id">
                <a :href="`/web/categories/${category.id}`">{{
                  category.locale_name
                }}</a>
              </li>
            </ul>
          </div>
        </div>
        <nav class="nav-menu mobile-menu">
          <ul style="margin-bottom: 0px">
            <slot name="navbarListItems"></slot>
          </ul>
        </nav>
      </div>
    </div> -->
  </header>
</template>

<script>
import HeaderSeachInputComponent from './HeaderSeachInputComponent'
import LanguageSwitcher from './LanguageSwitcher'
import HeaderQuickCartComponent from './../Cart/HeaderQuickCartComponent'

export default {
  components: {
    HeaderSeachInputComponent,
    HeaderQuickCartComponent,
    LanguageSwitcher
  },
  data () {
    return {
      showHiddenNavbar: false,
      lastScrollPosition: 0
    }
  },
  mounted () {
    window.addEventListener('scroll', this.onScroll)
  },
  beforeDestroy () {
    window.removeEventListener('scroll', this.onScroll)
  },
  methods: {
    onScroll () {
      const currentScrollPosition =
        window.pageYOffset || document.documentElement.scrollTop

      this.showHiddenNavbar = currentScrollPosition > 65
    }
  }
}
</script>

<style>
</style>
