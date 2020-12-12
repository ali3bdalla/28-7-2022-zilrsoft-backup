<template>
  <div>
    <HeaderComponent></HeaderComponent>
    <slot></slot>

  </div>
</template>

<script>
import HeaderComponent from '../components/Web/Page/HeaderComponent';


export default {
  components: {

    HeaderComponent
  },
  name: "WebAppLayout"
}
</script>

<style scoped>

</style>