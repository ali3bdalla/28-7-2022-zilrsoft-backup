<template>
  <RenderResultComponent :message="$page.props.$t.messages.account_not_verified" title="404" type="error"></RenderResultComponent>
</template>

<script>
import RenderResultComponent from '../../Components/Utility/RenderResultComponent'
export default {
  components: { RenderResultComponent },
  name: 'AccountUnverified'
}
</script>

<style scoped>

</style>
