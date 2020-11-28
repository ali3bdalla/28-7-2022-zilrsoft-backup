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
          <div class="col-lg-4 offset-lg-8">
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
      shippingAddressId: 0,
    };
  },
  components: {
    CartButton,
    CartItems,
    WebLayout,
    CartShippingAddress,
  },

  methods: {
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

      this.$confirm('confirm', '', 'success').then(() => {
        this.$loading.show({delay: 0})
        this.$inertia.post('/api/web/orders', {
          'shipping_address_id': this.shippingAddressId,
          'shipping_method_id': 2,
          'items': items
        }, {
          replace: false,
          preserveState: true,
          preserveScroll: true,
          onFinish: () => {
            this.$loading.hide();
            this.$alert(`You will receive payment instructions via whatsapp  to ${this.$page.client.international_phone_number}`, 'Thank You for your order', 'success');
          }
        });
      })
    }
  },
};
</script>

<style scoped>
</style>