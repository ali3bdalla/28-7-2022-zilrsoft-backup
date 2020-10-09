<template>
  <div>
    <slot name="button"></slot>
    <vue-tailwind-modal
        :showClose="false"
        :showing="showModal"
        class="bg-white shadow-2xl"
        style="z-index: 2300;position: absolute !important;width: 100%;height: 100%;;top: 0;left: 0;"
        @close="showModal = false"
    >
      <slot name="content"></slot>
    </vue-tailwind-modal>
  </div>
</template>

<script>
import VueTailwindModal from "vue-tailwind-modal"

export default {
  name: "ModalComponent",
  props:['show'],
  components: {
    VueTailwindModal
  },
  data() {
    return {
      showModal: true

    }
  },
  created() {
    this.showModal = this.show;
  },
  watch:{
    show:(value)=> {
      console.log(value)
      this.showModal = value
    }
  }
}
</script>

<style scoped>

</style>