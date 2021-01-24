<template>
  <web-layout>
    <div class="container">
      <hero class=""></hero>
      <div class="home__categories-list">
        <a
          class="home__categories-list-item"
          :href="`/web/categories/${category.id}`"
          v-for="(category, index) in $page.main_categories"
          :key="index"
        >
          <SubategoryListItemComponent
            :category="category"
          ></SubategoryListItemComponent>
        </a>
      </div>

      <div class="home__products-area">
        <h1 class="home__products-count">
          {{ $page.$t.products.all_products_count }} ({{ $page.products_count }})
        </h1>
        <ItemsInfinityLoad :params="{}"></ItemsInfinityLoad>
      </div>
    </div>
  </web-layout>
</template>

<script>
import WebLayout from '../../../Layouts/WebAppLayout'
import SubategoryListItemComponent from './../../../components/Web/Category/SubategoryListItemComponent'
import Hero from '../../../components/Web/Page/Hero.vue'
import ItemsInfinityLoad from '../../../components/Web/Item/ItemsInfinityLoad.vue'

export default {
  name: 'Index',
  components: {
    WebLayout,
    SubategoryListItemComponent,
    Hero,
    ItemsInfinityLoad
  }

}
</script>

<style scoped>
</style>
