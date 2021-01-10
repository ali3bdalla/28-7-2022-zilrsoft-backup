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
        <!-- <div class="content">
          <h2>The Great Title</h2>
          <a :href="item.credit.url" target="_blank"
            ><h4>{{ item.credit.name }}</h4></a
          >
          <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec
            fringilla congue enim, at convallis magna pulvinar vel. Suspendisse
            sed diam mi. Quisque purus velit, commodo non diam vitae, semper
            pharetra purus. Nam non gravida dolor.
          </p>
          <button>Action Button</button>
        </div>
      </div> -->
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
.v-hl-svg {
  background: rgba(0, 0, 0, 0.5) !important;
  color: white !important;
}
</style>