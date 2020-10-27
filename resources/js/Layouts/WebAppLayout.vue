<template>
  <div>
    <HeaderComponent></HeaderComponent>
    <slot></slot>
<!--    <HeaderQuickCartComponent></HeaderQuickCartComponent>-->

  </div>
</template>

<script>
import HeaderComponent from '../components/Web/Page/HeaderComponent';
// import HeaderQuickCartComponent from "./../components/Web/Cart/HeaderQuickCartComponent";

export default {
  components: {
    // HeaderQuickCartComponent,
    HeaderComponent
  },
  name: "WebAppLayout"
}
</script>

<style scoped>

</style>