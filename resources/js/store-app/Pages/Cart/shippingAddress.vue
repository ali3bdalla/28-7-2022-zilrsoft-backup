<template>
  <web-layout>
    <section
      class="shopping-cart spad cart"
      style="padding-top: 0px !important"
    >
      <div class="container">
        <CartProgress :active="2" />
        <div class="page__mt-5 col-lg-12">
          <h2 class="cart__shipping-method-title">
            {{ $page.props.$t.cart.select_shipping_address }}
            {{ $page.props.$t.common.or }}
            <inertia-link
              class="text-blue-400"
              href="/web/profile/create-shipping-address"
              >{{ $page.props.$t.common.add_new }}
            </inertia-link>
          </h2>
          <div class="page__mt-5 col-lg-12">
            <div class="my-4">
              <el-select
                v-model="pickedShippingAddress"
                :default-first-option="true"
                :filterable="true"
                no-data-text="No"
                no-match-text="No Data"
                value-key="id"
                :placeholder="
                  `${
                    pickedShippingAddress
                      ? `${pickedShippingAddress.locale_name} ${pickedShippingAddress.last_name} - ${pickedShippingAddress.city.locale_name} - ${pickedShippingAddress.phone_number}`
                      : ''
                  }`
                "
                @change="updateShippingAddress"
              >
                <el-option
                  v-for="shippingAddress in $page.props.shippingAddresses"
                  :key="shippingAddress.id"
                  :label="
                    `${shippingAddress.locale_name} ${shippingAddress.last_name} - ${shippingAddress.city.locale_name} - ${shippingAddress.phone_number}`
                  "
                  :value="shippingAddress"
                >
                  {{ shippingAddress.first_name }}
                  {{ shippingAddress.last_name }} -
                  {{ shippingAddress.city.locale_name }} -
                  {{ shippingAddress.phone_number }}
                </el-option>
              </el-select>
            </div>
            <CartAmount
              :total="true"
              :shipping="true"
              :shippingMethod="$page.props.cart.shipping_method"
            />
            <div class="flex justify-content-center">
              <div class="cart__totals-buttons">
                <button
                  :disable="!canMoveToNext"
                  class="cart__submit-btn"
                  @click="$inertia.visit('/web/cart/checkout')"
                >
                  {{ $page.props.$t.common.next }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import CartAmount from "./../../Components/Cart/CartAmount";
import CartProgress from "./../../Components/Cart/CartProgress";
import WebLayout from "../../Layouts/WebAppLayout";
export default {
  name: "ShippingAddress",
  components: { CartAmount, WebLayout, CartProgress },
  data() {
    return {
      shippingAddressId: null,
      pickedShippingAddress: null,
    };
  },
  created() {
    this.shippingAddressId = this.$page.props.cart.shipping_address_id;
    this.pickedShippingAddress = this.$page.props.cart.shipping_address;
  },
  methods: {
    updateShippingAddress(e) {
      axios
        .put("/api/web/cart/shipping_address", {
          shipping_address_id: e.id,
        })
        .then((res) => {
          this.pickedShippingAddress = e;
        });
    },
  },
  computed: {
    canMoveToNext() {
      return this.shippingAddressId;
    },
  },
};
</script>
