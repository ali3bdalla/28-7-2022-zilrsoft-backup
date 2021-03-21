<template>
  <web-layout>
    <section class="contact-section spad">
        <create-shipping-address-form :return-object="true"></create-shipping-address-form>
    </section>
  </web-layout>
</template>

<script>
import WebLayout from '../../../Layouts/WebAppLayout'
import CreateShippingAddressForm from '../../../components/Web/ShippingAddress/CreateShippingAddressForm'

export default {
  components: {
    CreateShippingAddressForm,
    WebLayout
  }

}
</script>
