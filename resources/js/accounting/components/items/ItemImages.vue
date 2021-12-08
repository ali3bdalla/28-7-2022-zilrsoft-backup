<template>
  <div class="row mt-5" style="margin-top: 20px">
    <images :item="item" :attachments="item.attachments"></images>

  </div>
</template>

<script>
import Images from '../../../components/BackEnd/Product/Images.vue'

export default {
  components: { Images },
  props: {
    item: {
      type: Object,
      required: true
    }
  }

}
</script>

<style scoped>
</style>
