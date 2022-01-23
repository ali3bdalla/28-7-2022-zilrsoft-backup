<template>
  <web-layout>
    <section
      class="shopping-cart spad cart"
      style="padding-top: 0px !important"
    >
      <div class="container">
        <CartProgress :active="1" />
        <div class="page__mt-5 col-lg-12">
          <div class="my-4">
            <h3 class="text-lg mb-2">
              {{ $page.props.$t.messages.select_city }}
            </h3>
            <el-select
              v-model="pickedCity"
              :default-first-option="true"
              :filterable="true"
              no-data-text=""
              no-match-text=""
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
          <div class="cart__shipping-method-list" v-if="pickedCity != null">
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
          <CartAmount
            :total="true"
            :shipping="true"
            :shippingMethod="pickedShippingMethod"
          />
          <div class="flex justify-content-center">
            <div class="cart__totals-buttons">
              <button
                :disabled="!canMoveToNext()"
                class="cart__submit-btn"
                @click="$inertia.visit('/web/cart/shipping_address')"
              >
                {{ $page.props.$t.common.next }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import CartProgress from "./../../Components/Cart/CartProgress";
import CartAmount from "./../../Components/Cart/CartAmount";
import WebLayout from "../../Layouts/WebAppLayout";

export default {
  name: "ShippingMethod",
  components: { CartAmount, WebLayout, CartProgress },
  computed: {
    cities() {
      return this.$page.props.cities_with_shipping_methods;
    },
    pickedShippingMethod() {
      const shippingMethod = this.pickedCity
        ? this.pickedCity.allowed_shipping_methods.find(
            (p) => p.shipping_method_id == this.activeShippingMethodId
          )
        : null;
      return shippingMethod ? shippingMethod.shipping_method : null;
    },
  },
  data() {
    return {
      justSelected: false,
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
    canMoveToNext() {
      return (
        this.justSelected ||
        (this.activeCityId &&
          this.pickedCity &&
          this.activeShippingMethodId &&
          this.pickedShippingMethod)
      );
    },
    updateCity(e) {
      axios
        .put("/api/web/cart/city", {
          city_id: e.id,
        })
        .then((res) => {
          if (
            e.allowed_shipping_methods.length === 1 &&
            e.allowed_shipping_methods[0].shipping_method_id !=
              this.activeShippingMethodId
          ) {
            this.activeShippingMethodId =
              e.allowed_shipping_methods[0].shipping_method_id;

            this.updateShippingMethod(this.activeShippingMethodId);
          }
        });
    },
    updateShippingMethod(e) {
      axios
        .put("/api/web/cart/shipping_method", {
          shipping_method_id: e,
        })
        .then((res) => {
          this.justSelected = true;
        });
    },
  },
};
</script>
