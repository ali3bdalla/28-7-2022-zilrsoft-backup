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
          :src="$processedImageUrl(category.image_url,546 * 5,226 * 5)"
        />
        <!-- `http://46.101.185.238:8080/AfrOrF3gWeDA6VOlDG4TzxMv39O7MXnF4CXpKUwGqRM/fit/546/226/sm/0/plain/` +  -->
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
