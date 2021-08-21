<template>
  <web-layout>
    <section class="contact-section spad">
        <CreateShippingAddressFormComponent :return-object="true"></CreateShippingAddressFormComponent>
    </section>
  </web-layout>
</template>

<script>
import WebLayout from '../../Layouts/WebAppLayout'
import CreateShippingAddressFormComponent from '../../Components/Customer/CreateShippingAddressFormComponent.vue'

export default {
  components: {
    CreateShippingAddressFormComponent,
    WebLayout
  }

}
</script>
