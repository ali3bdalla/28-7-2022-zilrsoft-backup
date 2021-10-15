<template>
  <WebAppLayout>
    <RenderResultComponent :message="$page.props.$t.messages.verification_code_sent"  :title="$page.props.$t.common.done"></RenderResultComponent>
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
