<template>
  <!-- animate__animated  animate__bounceIn -->
  <div
    class="home__categories-cell"
    style="border-color: #d2e8ff !important"
  >
    <div class="home__categories-cell-content">
      <div class="home__categories-cell-content__bg">
        <img
          class="home__categories-cell-content__image"
          :src="$processedImageUrl(`images/categories/category_${category.id}_${$page.props.active_locale == 'en' ? 'en' : 'ar'}.jpg`,546 * 3,226 *3 )"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    category: {
      type: Object,
      required: true
    }
  }
}
</script>

<style>
</style>>
