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
          <div class="col-lg-12">
            <cart-shipping-address
              v-if="activePage === 'checkout'"
              :shippingAddressId="shippingAddressId"
              @updateShippingId="updateShippingId"
            />
          </div>

          <!-- <el-radio-group v-model="shippingAddress">
            <div class="flex">
              <div class="flex flex-col flex-1" v-for="shippingMethod in $page.shippingMethods" :key="shippingMethod.id">
                <el-radio :label="shippingMethod.id"
                >
                  <el-image :src="shippingMethod.logo" class="object-cover h-32"></el-image>
                </el-radio>
              </div>
            </div>
          </el-radio-group> -->
 
          <div class="mt-3 col-lg-12">
            <h1 class="mb-3 text-xl text-gray-500">Shipping Method</h1>
            <div class="grid grid-cols-4 gap-2">
              <div
                class="flex flex-col items-center justify-center gap-5 pb-3 text-xl border rounded shadow-sm"
                v-for="shippingMethod in $page.shippingMethods"
                :key="shippingMethod.id"
              >
                <el-image
                  :class="{
                    'opacity-50': disableShippingMethod(shippingMethod),
                  }"
                  :src="shippingMethod.logo"
                  class="object-cover h-32"
                ></el-image>
                <el-radio
                  v-model="shippingMethodId"
                  :label="shippingMethod.id"
                  :disabled="disableShippingMethod(shippingMethod)"
                  >{{ shippingMethod.name }}</el-radio
                >
              </div>
            </div>
          </div>

          <div class="mt-8 col-lg-12">
            <h1 class="mb-3 text-xl text-gray-500">Payment Method</h1>
            <div class="grid grid-cols-4 gap-2">
              <div
                class="flex flex-col items-center justify-center gap-5 pb-3 text-xl border rounded shadow-sm"
                v-for="shippingMethod in $page.shippingMethods"
                :key="shippingMethod.id"
              >
                <el-image
                  :class="{
                    'opacity-50': disableShippingMethod(shippingMethod),
                  }"
                  :src="shippingMethod.logo"
                  class="object-cover h-32"
                ></el-image>
                <el-radio
                  v-model="shippingMethodId"
                  :label="shippingMethod.id"
                  :disabled="disableShippingMethod(shippingMethod)"
                  >{{ shippingMethod.name }}</el-radio
                >
              </div>
            </div>
          </div>

          <div class="mt-5 col-lg-4 offset-lg-8">
            <div class="proceed-checkout">
              <CartButton
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
      paymentMethods: [],
      shippingMethodId: 0,
      shippingAddress: 0,
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
  computed:{
      paymentMethods() {
        return [
          {
            name:"Bank Transefer",

          },

          {
            name:"Mada",

          }
        ];
      }
  },
  methods: {
    disableShippingMethod(shippingMethod) {
      // console.log(shippingMethod.cities);

      console.log(shippingMethod.cities.find((p) => p.id == 2));
      return true;
    },
    updateShippingId(e) {
      console.log(e.id);
      this.shippingAddressId = e.id;
    },
    updateOrderItems(e) {
      this.orderItems = e.items;
    },
    changeActivePage(e) {
      this.activePage = e.page;
    },
    sendOrder() {
      let items = this.orderItems;

      this.$confirm("confirm", "", "success").then(() => {
        this.$loading.show({ delay: 0 });
        this.$inertia.post(
          "/api/web/orders",
          {
            shipping_address_id: this.shippingAddressId,
            shipping_method_id: 2,
            items: items,
          },
          {
            replace: false,
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
              this.$loading.hide();
              this.$alert(
                `You will receive payment instructions via whatsapp  to ${this.$page.client.international_phone_number}`,
                "Thank You for your order",
                "success"
              );
            },
          }
        );
      });
    },
  },
};
</script>

<style>
.el-radio__inner {
  border: 1px solid black !important;
}
</style>