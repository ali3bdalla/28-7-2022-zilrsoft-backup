<template>
  <div>
    <!-- @scroll-debounce="onScrollDebounce" -->
    <vue-horizontal
      snap="center"
      :button-between="false"
      :button="true"
      ref="horizontalBanner"
      style="direction: ltr"
      @scroll-debounce="onScrollDebounce"
    >
      <div
        class="item hero__item"
        v-for="item in heroItems"
        :key="item.id"

      >
      <!-- :style="{ backgroundImage: 'url(' + item + ')' }" -->
        <img :src="item" class="object-cover" />
      </div>
    </vue-horizontal>
  </div>
</template>

<script>
import VueHorizontal from 'vue-horizontal'

export default {
  components: {
    VueHorizontal
  },

  data () {
    return {
      heroItems: [
        'https://m.xcite.com/media/wysiwyg/KSABannersNew2/20201213-Watch-S6-AN-AR-HP.jpg',
        'https://m.xcite.com/media/wysiwyg/KSABannersNew2/xSLA_Express-delivery-KSA-AR-BN.jpg',
        'https://m.xcite.com/media/wysiwyg/KSABannersNew2/14-12-2020-offer_coffee_machines_2-AR-HP.jpg',
        'https://m.xcite.com/media/wysiwyg/KSABannersNew2/Meshaiei/X1foldHPar.jpg'
      ],
      hasPrev: false,
      hasNext: false,
      interval: null,
      scrollWidth: 0,
      left: 0,
      progress: 0,
      index: 0
    }
  },
  mounted () {
    // Custom observe visibility is below
    // Much easier way: https://www.npmjs.com/package/vue-observe-visibility
    observeVisibility(this.$refs.horizontalBanner.$el, (visible) => {
      if (visible) {
        this.interval = setInterval(this.play, 4000)
      } else {
        clearInterval(this.interval)
      }
    })
  },
  destroyed () {
    clearInterval(this.interval)
  },
  methods: {
    onScrollDebounce ({ hasNext, hasPrev, scrollWidth, width, left }) {
      this.hasPrev = hasPrev
      this.hasNext = hasNext
      this.scrollWidth = scrollWidth
      this.left = left
      this.progress = left / scrollWidth
      this.index = Math.round(left / width)
    },
    onIndexClick (i) {
      this.$refs.horizontalBanner.scrollToIndex(i)
    },
    play () {
      if (!this.hasNext && this.hasPrev) {
        this.$refs.horizontalBanner.scrollToIndex(0)
        return
      }

      if (this.hasNext) {
        this.$refs.horizontalBanner.next()
      }
    }
  }
}

/**
 * Custom function, much easier way: https://www.npmjs.com/package/vue-observe-visibility
 *
 * @param element to track visibility
 * @param callback: function(boolean) when visibility change
 */
function observeVisibility (element, callback) {
  const observer = new IntersectionObserver((records) => {
    callback(records.find(record => record.isIntersecting))
  }, { rootMargin: '10% 0% 10% 0%', threshold: 0.5 })
  observer.observe(element)
}

</script>

<style>

</style>
