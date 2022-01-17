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
                      :src="$page.props.active_logo"
                      :style="
                        $page.props.active_locale == 'en'
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
              v-if="!$page.props.client_logged"
              ><i class="fa fa-user" style="font-size: 22px"></i> &nbsp;
              {{ $page.props.$t.profile.login }}</a
            >
            <el-dropdown v-else>
              <el-button>
                {{ $page.props.client.name }} <i class="el-icon-arrow-down el-icon--right"></i>
              </el-button>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item>
                  <inertia-link href="/web/profile">{{ $page.props.$t.profile.profile }}</inertia-link>
                </el-dropdown-item>
                <el-dropdown-item>
                  <inertia-link href="/web/logout">{{ $page.props.$t.profile.logout }}</inertia-link>
                </el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
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
                    :src="$page.props.active_logo"
                    :style="
                      $page.props.active_locale == 'en'
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
