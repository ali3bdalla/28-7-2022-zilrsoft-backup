<template>
  <web-layout>
    <section class="shopping-cart spad cart">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <CartItems
              :active-page="activePage"
              @orderItems="updateOrderItems"
            />
          </div>
          <div v-if="$page.client">
            <div class="col-lg-12">
              <cart-shipping-address
                v-if="activePage === 'checkout'"
                :shippingAddressId="shippingAddressId"
                @updateShippingId="updateShippingId"
              />
            </div>

            <div v-if="activePage == 'checkout'">
              <div class="page__mt-5 col-lg-12">
                <h1 class="cart__shipping-method-title">
                  {{ $page.$t.cart.shipping_method }}
                </h1>
                <div class="cart__shipping-method-list">
                  <div
                    class="cart-shipping-method-list-item"
                    v-for="shippingMethod in $page.shippingMethods"
                    :key="shippingMethod.id"
                  >
                    <el-image
                      :class="{
                        'cart__shipping-method-list-item-image__hidden': disableShippingMethod(shippingMethod),
                      }"
                      :src="shippingMethod.logo"
                      class="cart-shipping-method-list-item-image "
                    ></el-image>
                    <el-radio
                      v-model="shippingMethodId"
                      :label="shippingMethod.id"
                      :disabled="disableShippingMethod(shippingMethod)"
                      >{{ shippingMethod.name }}
                    </el-radio>
                  </div>
                </div>
              </div>

              <div class="page__mt-5 col-lg-12">
                <h1 class="page__mt-2 home__products-count">
                  {{ $page.$t.cart.payment_method }}
                </h1>
                <div class="cart__shipping-method-list">
                  <div
                    class="cart-shipping-method-list-item"
                    v-for="paymentMethod in paymentMethods"
                    :key="paymentMethod.name"
                  >
                    <el-image
                      :class="{
                        'cart__shipping-method-list-item-image__hidden': !paymentMethod.active,
                      }"
                      :src="paymentMethod.logo"
                      class="cart__shipping-method-list-item-image"
                    ></el-image>
                    <el-radio
                      :selected="paymentMethod.active"
                      v-model="paymentMethodId"
                      :label="paymentMethod.name"
                      :disabled="!paymentMethod.active"
                    >
                      {{ $page.$t.cart[paymentMethod.name] }}
                    </el-radio>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="cart__checkout">
            <div class="proceed-checkout">
              <CartButton
                :shipping-method-id="shippingMethodId"
                :active-page="activePage"
                :order-items="orderItems"
                @changeActivePage="changeActivePage"
                @sendOrder="sendOrder"
              />
            </div>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import WebLayout from "../../../Layouts/WebAppLayout";
import CartItems from "./CartItems";
import CartButton from "./CartButton";
import CartShippingAddress from "./CartShippingAddress.vue";

export default {
  name: "Index",

  data() {
    return {
      activePage: "cart",
      orderItems: [],
      shippingMethodId: 0,

      paymentMethodId: "Bank Transfer",
      shippingAddress: null,
      shippingAddressMethods: [],
      shippingAddressId: 0,
    };
  },
  components: {
    CartButton,
    CartItems,
    WebLayout,
    CartShippingAddress,
  },
  computed: {
    paymentMethods() {
      return [
        {
          name: "bank_transfer",
          logo: "/images/shipping_methods/bank_transfer.jpeg",
          active: true,
          selected: true,
        },

        {
          name: "mada",
          logo: "/images/shipping_methods/mada.jpg",
          active: false,
        },
        {
          name: "visa",
          logo: "/images/shipping_methods/visa.jpg",
          active: false,
        },
        {
          name: "sada",
          logo: "/images/shipping_methods/sdad.jpg",
          active: false,
        },
      ];
    },
  },
  methods: {
    disableShippingMethod(shippingMethod) {
      if (this.shippingAddress) {
        // this.shippingMethodId = shippingMethod.id;
        return !shippingMethod.cities_ids.includes(
          parseInt(this.shippingAddress.city_id)
        );
      }
      return true;
    },
    updateShippingId(e) {
      this.shippingAddress = e;
      this.shippingAddressId = e.id;

      // this.$page.shippingMethods.forEach((shippingMethod) => {
      //   shippingMethod.cities_ids.includes(parseInt(this.shippingAddress.city_id))
      // })
    },
    updateOrderItems(e) {
      this.orderItems = e.items;
    },
    changeActivePage(e) {
      this.activePage = e.page;
    },
    sendOrder() {
      let items = this.orderItems;

      this.$confirm(this.$page.$t.messages.confirm,this.$page.$t.messages.are_you_sure, "success").then(() => {
        this.$loading.show({ delay: 0 });
        this.$inertia.post(
          "/api/web/orders",
          {
            shipping_address_id: this.shippingAddressId,
            shipping_method_id: this.shippingMethodId,
            payment_method_id: this.paymentMethodId,
            items: items,
          },
          {
            replace: true,
            preserveState: (page) => Object.keys(page.props.errors).length,
            preserveScroll: (page) => Object.keys(page.props.errors).length,
            onSuccess: () => {
              return Promise.all([this.alertUser()]);
            },
            onFinish: () => {
              this.$loading.hide();
            },
          }
        );
      });
    },

    alertUser() {
      return new Promise((resolve, reject) => {
        this.$alert(
          `${this.$page.$t.order.instructions_for_payment} ${this.$page.client.international_phone_number}`,
          this.$page.$t.order.thanks_for_order,
          this.$page.$t.order.created
        );
        resolve();
      });
    },
  },
};
</script>

<style>
.el-radio__inner {
  border: 1px solid #888 !important;
}
</style>