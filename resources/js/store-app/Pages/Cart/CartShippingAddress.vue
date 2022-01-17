<template>
  <div class="">
    <h2
      v-if="$page.props.shippingAddresses.length > 0"
      class="cart__shipping-method-title"
    >
      {{ $page.props.$t.cart.select_shipping_address }}
      {{ $page.props.$t.common.or }}
      <inertia-link
        class="text-blue-400"
        href="/web/profile/create-shipping-address"
        >{{ $page.props.$t.common.add_new }}
      </inertia-link>
    </h2>
    <div v-else class="cart__shipping-method-title">
      <CreateShippingAddressFormComponent
        :return-object="true"
        @created="shippingAddressCreated"
      ></CreateShippingAddressFormComponent>
    </div>

    <div v-if="$page.props.shippingAddresses.length > 0" class="">
      <div class="cart__shipping-address">
        <div class="cart__shipping-address-list">
          <div class="cart__shipping-address-right w-full">
            <el-select
              v-model="shippingAddressId"
              :allow-create="true"
              :filterable="true"
              :placeholder="$page.props.$t.cart.select_shipping_address"
              class="page__w-full"
              no-data-text="No"
              no-match-text="No Data"
            >
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
                    <defs />
                    <path
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                    />
                  </svg>
                  {{ $page.props.$t.cart.create_shipping_address }}
                </a>
              </template>
              <el-option
                v-for="shippingAddress in shippingAddressList"
                :key="shippingAddress.id"
                :label="shippingAddress.first_name"
                :value="shippingAddress.id"
              >
                {{ shippingAddress.first_name }}
                {{ shippingAddress.last_name }} -
                {{ shippingAddress.city.locale_name }} -
                {{ shippingAddress.phone_number }}
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

            <div class="cart__shipping-address-title"></div>

            <div class="cart__shipping-address-title">
              {{ $page.props.$t.common.internationalKey
              }}{{ shippingAddress.phone_number }}
            </div>
          </el-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CreateShippingAddressFormComponent from "../../Components/Customer/CreateShippingAddressFormComponent.vue";

export default {
  name: "CartShippingAddress",
  components: { CreateShippingAddressFormComponent },
  data() {
    return {
      shippingAddresses: this.$page.props.shippingAddresses,
      shippingAddressId: null,
      shippingAddress: null,
    };
  },

  computed: {
    shippingAddressList() {
      let city = JSON.parse(
        localStorage.getItem("cart_shipping_city_id", "{}")
      );
      return this.$page.props.shippingAddresses.filter(
        (p) => p.city_id == city.id
      );
    },
  },
  methods: {
    shippingAddressCreated(e) {
      this.shippingAddresses.push(e);
      this.updateShippingId(e.id);
    },
    updateShippingId(id) {
      this.shippingAddress = this.shippingAddresses.find((p) => p.id == id);
      this.$emit("updateShippingId", this.shippingAddress);
    },
  },
  watch: {
    shippingAddressId: {
      deep: true,
      handler(value) {
        this.updateShippingId(value);
      },
    },
  },
};
</script>
