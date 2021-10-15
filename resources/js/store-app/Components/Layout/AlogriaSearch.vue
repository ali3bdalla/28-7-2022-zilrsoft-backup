<template>
  <div class="w-full">
    <ais-search-box
      @change="startTyping"
      class=""
      :placeholder="$page.props.$t.header.search_placeholder"
      :autofocus="true"
      :show-loading-indicator="false"
    >
      <div
        slot="submit-icon"
        class="search_in_category"
        :style="$page.props.active_locale == 'ar' ? 'direction: rtl' : '' "
        v-if="$page.props.categories_search_list.length > 0"
      >
       <div class="truncate"> {{ $page.props.$t.common.search_in }} {{ $page.props.categories_search_list[0] }}</div>
      </div>
    </ais-search-box>
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
.ais-SearchBox-loadingIndicator,
.ais-SearchBox-reset,
.ais-SearchBox-submit {
  @apply flex items-center justify-items-end;
  padding: 0px !important;
}

</style>
