<template>
  <div class="">
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

    <div class="">
      <div class="flex gap-5">
        <div class="flex-1">


          <el-select v-model="shippingAddressId" placeholder="Please Select Address" class="w-full" filterable>
            <el-option
                v-for="shippingAddress in $page.shippingAddresses"
                :key="shippingAddress.id"
                :label="shippingAddress.first_name"
                :value="shippingAddress.id">
              {{ shippingAddress.first_name }} {{ shippingAddress.last_name }} - {{ shippingAddress.city.name }} - {{
              shippingAddress.phone_number }} 
            </el-option>
          </el-select>


          <div v-if="shippingAddress !== null">
            <el-card class="box-card" shadow="never">
              <div class="text-xl text-gray-500 font-bold">
                {{ shippingAddress.first_name }} {{ shippingAddress.last_name }}
              </div>
              <!--            <div class="text-xl text-gray-500 font-bold">-->
              <!--              {{ shippingAddress.street_name }}, {{ shippingAddress.building_number }}-->
              <!--            </div>-->
              <div class="text-xl text-gray-500 font-bold">
                {{ shippingAddress.description }}
              </div>
              <div class="text-xl text-gray-500 font-bold">
                {{ shippingAddress.city.name }}
              </div>

              <!--            , {{ shippingAddress.state }}-->
              <div class="text-xl text-gray-500 font-bold">
                <!--          {{ shippingAddress.country.name }}-->
              </div>

              <div class="text-xl text-gray-500 font-bold">
                {{ shippingAddress.phone_number }}
              </div>
            </el-card>
          </div>


        </div>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: "CartShippingAddress",

  data() {
    return {
      shippingAddressId: null,
      shippingAddress: null
    }
  },
  methods: {
    updateShippingId(id) {
      this.shippingAddress = this.$page.shippingAddresses.find(p => p.id === id);
      this.$emit('updateShippingId', {id: id})
    }

  },
  watch: {
    shippingAddressId: {
      deep: true,
      handler(value) {
        this.updateShippingId(value);
      }
    }
  }
};
</script>

