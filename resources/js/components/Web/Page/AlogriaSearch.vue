<template>
  <!-- <div class="right-panel">-->
  <div class="w-full">
    <ais-search-box
      class=""
      placeholder="string"
      submit-title="string"
      reset-title="string"
      :autofocus="true"
      :show-loading-indicator="true"
    />
  </div>
</template>

<script>
import algoliasearch from 'algoliasearch/lite'
import 'instantsearch.css/themes/algolia-min.css'

export default {
  data () {
    return {
      searchClient: algoliasearch('ZXFZ7FDM25', this.$page.alogria_search_key)
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
</style>
