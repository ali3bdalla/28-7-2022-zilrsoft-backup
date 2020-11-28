<template>
  <div class="checkout">
    <h2 class="checkout__title">
      Shipping Address
      <a
          class="checkout__edit-icon"
          href="/web/profile/shipping_address/create"
      >
        <svg
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
        >
          <defs/>
          <path
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
          />
        </svg>
        Add Address
      </a>
    </h2>
    <!--    <div class="error_message">{{$page.errors.shipping_address_id}}-->
    <!--    </div>-->
    <div class="checkout__list">
      <div
          v-for="shippingAddress in $page.shippingAddresses"
          :key="shippingAddress.id"
          :class="{ '': shippingAddressId !== shippingAddress.id }"
          class="checkout__shipping-address"
      >

        <div class="text-xl text-gray-500 font-bold">
          {{ shippingAddress.first_name }} {{ shippingAddress.last_name }}
        </div>
        <div class="text-xl text-gray-500 font-bold">
          {{ shippingAddress.street_name }}, {{ shippingAddress.building_number }}
        </div>
        <div class="text-xl text-gray-500 font-bold">
          {{ shippingAddress.description }}
        </div>
        <div class="text-xl text-gray-500 font-bold">
          {{ shippingAddress.city }}, {{ shippingAddress.state }}
        </div>

        <div class="text-xl text-gray-500 font-bold">
          {{ shippingAddress.country.name }}
        </div>

        <div class="text-xl text-gray-500 font-bold">
          {{ shippingAddress.phone_number }}
        </div>




        <div class="h-12">
          <!--  -->
          <button
              v-if="shippingAddressId !== shippingAddress.id"
              class="checkout__shipping-address-button"
              @click="updateShippingId(shippingAddress.id)"
          >
            Select
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CartShippingAddress",
  props: {
    shippingAddressId: {
      type: Number,
      required: true
    }
  },
  methods: {
    updateShippingId(id) {
      this.$emit('updateShippingId', {id: id})
    }

  },
};
</script>

