<template>
  <div>
    <!-- @scroll-debounce="onScrollDebounce" -->
    <vue-horizontal
      snap="center"
      :button-between="false"
      :button="true"
      ref="horizontal"
      style="direction: ltr"
    >
      <div
        class="item hero__item"
        v-for="item in heroItems"
        :key="item.id"
        
        :style="{ backgroundImage: 'url(' + item + ')' }"
      >
      </div>
    </vue-horizontal>
  </div>
</template>

<script>
import VueHorizontal from "vue-horizontal";

export default {
  components: {
    VueHorizontal,
  },
  data() {
    return {
      heroItems: [
        "https://m.xcite.com/media/wysiwyg/KSABannersNew2/20201213-Watch-S6-AN-AR-HP.jpg",
        "https://m.xcite.com/media/wysiwyg/KSABannersNew2/xSLA_Express-delivery-KSA-AR-BN.jpg",
        "https://m.xcite.com/media/wysiwyg/KSABannersNew2/14-12-2020-offer_coffee_machines_2-AR-HP.jpg",
        "https://m.xcite.com/media/wysiwyg/KSABannersNew2/Meshaiei/X1foldHPar.jpg",
      ],
    };
  },
};
</script>

<style>

</style>