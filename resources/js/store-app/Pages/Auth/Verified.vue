<template>
  <WebAppLayout>
    <RenderResultComponent message="phone number has been verified successfully"
                           title="verified"></RenderResultComponent>
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
