<template>
  <div
    class="home__categories-cell animate__animated  animate__bounceIn"
    style="border-color: #d2e8ff !important"
  >
    <div class="home__categories-cell-content">
      <div class="home__categories-cell-content__bg">
        <img
          class="home__categories-cell-content__image"
          :src="category.image_url_attribute"
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
