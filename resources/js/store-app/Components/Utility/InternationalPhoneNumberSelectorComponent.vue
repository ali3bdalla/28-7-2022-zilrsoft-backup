<template>
<!--  :only-countries="['SA','EG','SD']"-->
  <VuePhoneNumberInput
      v-model="phoneNumber"
      :no-country-selector="false"
      :no-example="true"

      :translations="{countrySelectorLabel: $page.$t.profile.country,phoneNumberLabel: '5XXXXXXXXX',example: 'ex: 5XXXXXXXXX'}"
      default-country-code="SD"
      @update="phoneNumberUpdated"
  />
</template>

<script>
import VuePhoneNumberInput from 'vue-phone-number-input'
import 'vue-phone-number-input/dist/vue-phone-number-input.css'

export default {
  name: 'InternationalPhoneNumberSelectorComponent',
  components: { VuePhoneNumberInput },
  data () {
    return {
      phoneNumber: '966324018'
    }
  },
  methods: {
    phoneNumberUpdated (v) {
      this.$emit('input', v.formattedNumber)
    }
  }
}
</script>

<style scoped>

</style>
