<template>
  <div class="">
    <h2 class="checkout__title">
      {{$page.$t.cart.shipping_address}}

    </h2>

    <div class="">
      <div class="flex flex-col gap-5">
        <div class="flex">
          <div class="w-7/12 lg:w-10/12">
          <el-select v-model="shippingAddressId" :placeholder="$page.$t.cart.select_shipping_address" class="w-full" filterable>
            <el-option
                v-for="shippingAddress in $page.shippingAddresses"
                :key="shippingAddress.id"
                :label="shippingAddress.first_name"
                :value="shippingAddress.id">
              {{ shippingAddress.first_name }} {{ shippingAddress.last_name }} - {{ shippingAddress.city.name }} - {{
                shippingAddress.phone_number
              }}
            </el-option>
          </el-select>

        </div>
        <div class="flex items-center justify-center w-5/12 text-sm lg:w-2/12">

          <a
              class="checkout__edit-icon"
              href="/web/profile/create-shipping-address"
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
           {{$page.$t.cart.create_shipping_address}}
          </a>
        </div>
        </div>


        <div v-if="shippingAddress !== null">
          <el-card class="box-card" shadow="never">
            <div class="text-xl font-bold text-gray-500">
              {{ shippingAddress.first_name }} {{ shippingAddress.last_name }}
            </div>
            <div class="text-xl font-bold text-gray-500">
              {{ shippingAddress.description }}
            </div>
            <div class="text-xl font-bold text-gray-500">
              {{ shippingAddress.city.name }}
            </div>

            <div class="text-xl font-bold text-gray-500">
            </div>

            <div class="text-xl font-bold text-gray-500">
              {{ shippingAddress.phone_number }}
            </div>
          </el-card>
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
      this.$emit('updateShippingId', this.shippingAddress)
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

