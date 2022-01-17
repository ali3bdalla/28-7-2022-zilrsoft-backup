<template>
  <web-layout>
    <section
      class="shopping-cart spad cart"
      style="padding-top: 0px !important"
    >
      <div class="container">
        <CartProgress :active="3" />
        <CartItems :disable="true" />
        <div class="page__mt-5 col-lg-12">
          <CartAmount
            :all="true"
            :shippingMethod="$page.props.cart.shipping_method"
          />
          <div class="flex justify-content-center">
            <div class="cart__totals-buttons">
              <button class="cart__submit-btn" @click="checkout">
                {{ $page.props.$t.cart.confirm_order }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import CartAmount from "./../../Components/Cart/CartAmount";
import CartItems from "./../../Components/Cart/CartItems";
import CartProgress from "./../../Components/Cart/CartProgress";
import WebLayout from "../../Layouts/WebAppLayout";
export default {
  name: "Checkout",
  components: { CartAmount, WebLayout, CartItems, CartProgress },
  methods: {
    checkout() {
      this.$confirm("", this.$page.props.$t.messages.are_you_sure, "success", {
        confirmButtonText: this.$page.props.$t.messages.yes,
        cancelButtonText: this.$page.props.$t.messages.no,
      }).then(() => {
        this.$loading.show({ delay: 0 });
        axios
          .post("/api/web/orders")
          .then((res) => {
            this.alertUser(res);
          })
          .catch((error) => {})
          .finally(() => {
            this.$loading.hide();
          });
      });
    },
    alertUser(res) {
      const number = `${res.data.user.phone_number}`.replace("+", "");
      return new Promise((resolve, reject) => {
        this.$alert(
          `${this.$page.props.$t.order.instructions_for_payment} ${number}`,
          this.$page.props.$t.order.thanks_for_order,
          "success",
          {
            confirmButtonText: this.$page.props.$t.messages.yes,
            cancelButtonText: this.$page.props.$t.messages.no,
          }
        ).then(() => {
          location.href = "/web/profile";
        });
        resolve();
      });
    },
  },
};
</script>
