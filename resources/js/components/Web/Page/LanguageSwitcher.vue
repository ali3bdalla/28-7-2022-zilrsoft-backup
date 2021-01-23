<template>
  <a :href="`?web_active_lang=${activateLang.changeTo}`" class="page__header-buttons__item-right items-left flex items-center">
    <img
      :src="$asset(activateLang.displayImage)"
      class="float-left h-4"
    />
    <span class="login-panel">{{activateLang.showText}}</span>
  </a>
</template>

<script>
export default {
  computed: {
    activateLang () {
      return this.langs[this.$page.active_locale]
    }
  },
  data () {
    return {
      langs: {
        ar: {
          changeTo: 'en',
          showText: 'English',
          displayImage: 'web_assets/flags/us-flag.gif'
        },
        en: {
          changeTo: 'ar',
          showText: 'عربي',
          displayImage: 'web_assets/flags/sa-flag.gif'
        }
      }
    }
  }

}
</script>

<style>
</style>
