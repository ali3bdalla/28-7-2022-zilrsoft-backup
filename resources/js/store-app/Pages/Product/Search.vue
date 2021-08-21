<template>
  <web-layout>
    <div class="container">
      <div class="product__search-page">

          <alogira-product-result-list

          ></alogira-product-result-list>

      </div>
    </div>
  </web-layout>
</template>

<script>

import WebLayout from '../../Layouts/WebAppLayout'
import AlogiraProductResultList from '../../../components/Web/Product/AlogiraProductResultList.vue'

export default {
  components: {
    WebLayout,
    AlogiraProductResultList
  }

}
</script>
