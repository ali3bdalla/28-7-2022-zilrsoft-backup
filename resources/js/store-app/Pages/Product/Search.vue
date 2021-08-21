<template>
  <web-layout>
    <div class="container">
      <div class="product__search-page">
          <ProductSearchResultListComponent
          ></ProductSearchResultListComponent>
      </div>
    </div>
  </web-layout>
</template>

<script>

import WebLayout from '../../Layouts/WebAppLayout'
import ProductSearchResultListComponent from '../../Components/Product/ProductSearchResultListComponent.vue'

export default {
  components: {
    WebLayout,
    ProductSearchResultListComponent
  }

}
</script>
