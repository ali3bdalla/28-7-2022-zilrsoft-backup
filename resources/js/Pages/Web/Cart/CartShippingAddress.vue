<template>
  <div class="">
    <h2 class="checkout__title">
      {{$page.$t.cart.shipping_address}}

    </h2>

    <div class="">
      <div class="cart__shipping-address">
        <div class="cart__shipping-address-list">
          <div class="cart__shipping-address-right w-full">
          <el-select  no-match-text="No Data" no-data-text="No" :filterable="true" :allow-create="true" v-model="shippingAddressId" :placeholder="$page.$t.cart.select_shipping_address" class="page__w-full" filterable>
            <!-- <template #prefix>Click Me</template> -->
            <template #empty>
               <a
               style="padding:5px"
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
            </template>
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
        <!-- <div class="cart__shipping-address-left">

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
        </div> -->
        </div>

        <div v-if="shippingAddress !== null">
          <el-card class="box-card" shadow="never">
            <div class="cart__shipping-address-title">
              {{ shippingAddress.first_name }} {{ shippingAddress.last_name }}
            </div>
            <div class="cart__shipping-address-title">
              {{ shippingAddress.description }}
            </div>
            <div class="cart__shipping-address-title">
              {{ shippingAddress.city.name }}
            </div>

            <div class="cart__shipping-address-title">
            </div>

            <div class="cart__shipping-address-title">
             {{ $page.$t.common.internationalKey }}{{ shippingAddress.phone_number }}
            </div>
          </el-card>
        </div>

      </div>

    </div>

  </div>
</template>

<script>
export default {
  name: 'CartShippingAddress',

  data () {
    return {
      shippingAddressId: null,
      shippingAddress: null
    }
  },
  methods: {
    updateShippingId (id) {
      this.shippingAddress = this.$page.shippingAddresses.find(p => p.id === id)
      this.$emit('updateShippingId', this.shippingAddress)
    }

  },
  watch: {
    shippingAddressId: {
      deep: true,
      handler (value) {
        this.updateShippingId(value)
      }
    }
  }
}
</script>
