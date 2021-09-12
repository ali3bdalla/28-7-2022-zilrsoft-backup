<template>
  <WebAppLayout>
    <RenderResultComponent message="password Changed"
                           title="Password"></RenderResultComponent>
  </WebAppLayout>
</template>

<script>
import WebAppLayout from '../../Layouts/WebAppLayout'
import RenderResultComponent from '../../Components/Utility/RenderResultComponent'

export default {
  name: 'Verified',
  components: {
    RenderResultComponent,
    WebAppLayout
  }
}
</script>

<style scoped>

</style>
