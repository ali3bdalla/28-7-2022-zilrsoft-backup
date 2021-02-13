<template>
  <!-- <div class="right-panel">-->
  <div class="w-full">
    <ais-search-box
      @change="startTyping"
      class=""
      :placeholder="$page.$t.header.search_placeholder"
      :autofocus="true"
      :show-loading-indicator="false"
    >
      <div
        slot="submit-icon"
        class="search_in_category"
        :style="$page.active_locale == 'ar' ? 'direction: rtl' : '' "
        v-if="$page.categories_search_list.length > 0"
      >
       <div class="truncate"> {{ $page.$t.common.search_in }} {{ $page.categories_search_list[0] }}</div>
      </div>
      <div slot="submit-icon"  class="search_in_category" v-else>
          {{ $page.$t.common.search_in_all_products }}
      </div>
      <div slot="reset-icon"></div>
    </ais-search-box>
    <!-- submit-title="string"
      reset-title="string" -->
  </div>
</template>

<script>
export default {
  data () {
    return {}
  },
  methods: {
    startTyping (t) {
      console.log(t)
    }
  }
}
</script>

<style>
.ais-SearchBox-input,
.inner-header .advanced-search .input-group input {
  width: 100% !important;
}
/* body {
  font-family: sans-serif;
  padding: 1em;
}

.ais-Hits-list {
  margin-top: 0;
  margin-bottom: 1em;
}

.ais-InstantSearch {
  display: grid;
  grid-template-columns: 1fr 4fr;
  grid-gap: 1em;
}

.ais-Hits-item img {
  margin-right: 1em;
}
.hit-name {
  margin-bottom: 0.5em;
}
.hit-description {
  color: #888;
  font-size: 0.8em;
  margin-bottom: 0.5em;
} */

.ais-SearchBox-loadingIndicator,
.ais-SearchBox-reset,
.ais-SearchBox-submit {
  width: auto !important;
  height: 28px !important;
  @apply flex items-center justify-items-end;
  padding: 0px !important;
}

</style>
