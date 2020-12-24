<template>
  <div>
    <HeaderComponent></HeaderComponent>
   <div  style="background-color:#f9f9f9"> <slot></slot> </div>

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