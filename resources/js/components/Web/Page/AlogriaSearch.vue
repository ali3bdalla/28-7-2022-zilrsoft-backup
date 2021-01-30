<template>
  <!-- <div class="right-panel">-->
  <div class="w-full">
    <ais-search-box
      class=""
      :placeholder="$page.$t.header.search_placeholder"
      :autofocus="true"
      :show-loading-indicator="false"
    >
    </ais-search-box>
    <!-- submit-title="string"
      reset-title="string" -->
  </div>
</template>

<script>
export default {
  data () {
    return {}
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
