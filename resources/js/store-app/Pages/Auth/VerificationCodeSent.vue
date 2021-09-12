<template>
  <WebAppLayout>
    <RenderResultComponent message="verification code has been sent,please check your whatsapp/sms notifications"
                           title="sent"></RenderResultComponent>
  </WebAppLayout>
</template>

<script>
import WebAppLayout from '../../Layouts/WebAppLayout'
import RenderResultComponent from '../../Components/Utility/RenderResultComponent'

export default {
  name: 'VerificationCodeSent',
  components: {
    RenderResultComponent,
    WebAppLayout
  }
}
</script>

<style scoped>

</style>
