<template>
  <web-layout>
    <section
      class="shopping-cart spad cart"
      style="padding-top: 0px !important"
    >
      <div class="container">
        <div class="page__mt-5 col-lg-12">
          <div class="my-4">
            <el-select
              v-model="pickedCity"
              :default-first-option="true"
              :filterable="true"
              no-data-text="No"
              no-match-text="No Data"
              value-key="id"
              :placeholder="`${pickedCity ? pickedCity.locale_name : ''}`"
              @change="updateCity"
            >
              <el-option
                v-for="city in cities"
                :key="city.id"
                :label="city.locale_name"
                :value="city"
              >
                {{ city.locale_name }}
              </el-option>
            </el-select>
          </div>
          <h1 class="cart__shipping-method-title" v-if="pickedCity">
            {{ $page.props.$t.cart.shipping_method }}
          </h1>
          <div class="cart__shipping-method-list" v-if="pickedCity">
            <div
              v-for="cityMethod in pickedCity.allowed_shipping_methods"
              :key="cityMethod.shipping_method_id"
              class="cart__shipping-method-list-item bg-white"
            >
              <el-image
                :src="cityMethod.shipping_method.logo"
                class="cart__shipping-method-list-item-image object-contain"
              ></el-image>
              <el-radio
                v-model="activeShippingMethodId"
                :label="cityMethod.shipping_method_id"
                @change="updateShippingMethod"
                >{{ cityMethod.shipping_method.locale_name }}
              </el-radio>
            </div>
          </div>
        </div>
        <div class="page__mt-5 col-lg-12">
          <div class="">
            <div class="cart__totals-buttons">
              <CartAmount :total="true" />
              <inertia-link
                class="cart__submit-btn"
                href="/web/cart/shipping_address"
              >
                {{ $page.props.$t.cart.checkout }}
              </inertia-link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import CartAmount from "./../../Components/Cart/CartAmount";
import WebLayout from "../../Layouts/WebAppLayout";

export default {
  name: "ShippingMethod",
  components: { CartAmount, WebLayout },
  computed: {
    cities() {
      return this.$page.props.cities_with_shipping_methods;
    },
    isEmpty() {
      return this.$page.props.cart_items.length === 0;
    },
  },
  data() {
    return {
      activeShippingMethodId: null,
      activeCityId: null,
      pickedCity: null,
    };
  },
  created() {
    this.activeShippingMethodId = this.$page.props.cart.shipping_method_id;
    this.activeCityId = this.$page.props.cart.city_id;
    if (this.activeCityId) {
      this.pickedCity = this.cities.find((p) => p.id == this.activeCityId);
    }
  },
  methods: {
    updateCity(e) {
      axios.put("/api/web/cart/city", {
        city_id: e.id,
      });
    },
    updateShippingMethod(e) {
      axios.put("/api/web/cart/shipping_method", {
        shipping_method_id: e,
      });
    },
  },
};
</script>
