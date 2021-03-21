<template>
  <div class="">
    <h2 v-if="$page.shippingAddresses.length > 0" class="cart__shipping-method-title">
      {{$page.$t.cart.select_shipping_address }} {{$page.$t.common.or }}
      <inertia-link class="text-blue-400" href="/web/profile/create-shipping-address">{{ $page.$t.common.add_new }}
      </inertia-link>
    </h2>
    <div v-else class="cart__shipping-method-title">
      <create-shipping-address-form :return-object="true"
                                    @created="shippingAddressCreated"></create-shipping-address-form>
    </div>

    <div v-if="$page.shippingAddresses.length > 0" class="">
      <div class="cart__shipping-address">
        <div class="cart__shipping-address-list">
          <div class="cart__shipping-address-right w-full">
            <el-select v-model="shippingAddressId" :allow-create="true" :filterable="true"
                       :placeholder="$page.$t.cart.select_shipping_address"
                       class="page__w-full" filterable
                       no-data-text="No" no-match-text="No Data">
              <!-- <template #prefix>Click Me</template> -->
              <template #empty>
                <a
                    class="checkout__edit-icon"
                    href="/web/profile/create-shipping-address"
                    style="padding:5px"
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
                {{ shippingAddress.first_name }} {{ shippingAddress.last_name }} - {{ shippingAddress.city.locale_name
                }} - {{
                shippingAddress.phone_number
                }}
              </el-option>
            </el-select>

          </div>
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
              {{ shippingAddress.city.locale_name }}
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
import CreateShippingAddressForm from '../../../components/Web/ShippingAddress/CreateShippingAddressForm'

export default {
  name: 'CartShippingAddress',
  components: { CreateShippingAddressForm },
  data () {
    return {
      shippingAddresses: this.$page.shippingAddresses,
      shippingAddressId: null,
      shippingAddress: null
    }
  },
  methods: {
    shippingAddressCreated (e) {
      this.shippingAddresses.push(e);
      this.updateShippingId(e.id);
    },
    updateShippingId (id) {
      this.shippingAddress = this.shippingAddresses.find(p => p.id === id)
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
