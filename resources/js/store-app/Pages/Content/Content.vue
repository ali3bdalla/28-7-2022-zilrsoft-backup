<template>
  <div class="mb-5 mt-2">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
         <!-- <h1 class="text-center text-gray-600 text-3xl content__title">{{$page.props.$t.content.about_us_title}}</h1> -->
         <div class="mt-1 text-xl"><pre style="overflow:hidden; white-space: pre-line;    line-height: 1.6;" :style="$page.props.active_locale == 'ar' ? 'direction:rtl;' : ''" v-html="$page.props.content"></pre></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import Markdown from '../../../components/Markdown.vue'
import WebAppLayout from '../../Layouts/WebAppLayout'
export default {
  // components: { Markdown },
  layout: WebAppLayout
}
</script>

<style>
</style>
