<template>
  <div class="w-2/12 m-0 text-center flex items-center justify-end">
    <ul class="nav-right">
      <!--      <li class="heart-icon">-->
      <!--        <a href="#">-->
      <!--          <i class="icon_heart_alt"></i>-->
      <!--          <span>1</span>-->
      <!--        </a>-->
      <!--      </li>-->
      <li class="cart-icon ">
        <a :class="applyClass" :href="[$store.state.cartCount === 0 ? '#' :  '/web/cart']">
          <svg class="w-16 h-16 text-gray-800 m-0  " fill="none" stroke="currentColor" viewBox="0 0 24 24"
               xmlns="http://www.w3.org/2000/svg">
            <path
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2"/>
          </svg>
          <!--          <i class="icon_bag_alt text-xl"></i>-->
          <span class="inline-block text-xl w-8 h-8 rounded-full" style="width: 34px;
    height: 34px;
    text-align: center;
    font-size: 20px;
    vertical-align: middle;
    justify-content: center;
    justify-items: center;
    display: flex;
    align-items: center;">{{ $store.state.cartCount }}</span>
        </a>
        <!--        <div class="cart-hover">-->
        <!--          <div class="select-items">-->
        <!--            <table>-->
        <!--              <tbody>-->
        <!--              <tr>-->
        <!--                <td class="si-pic">-->
        <!--                  <img-->
        <!--                      alt=""-->
        <!--                      src="/Web/template/img/select-product-1.jpg"-->
        <!--                  />-->
        <!--                </td>-->
        <!--                <td class="si-text">-->
        <!--                  <div class="product-selected">-->
        <!--                    <p>$60.00 x 1</p>-->
        <!--                    <h6>Kabino Bedside Table</h6>-->
        <!--                  </div>-->
        <!--                </td>-->
        <!--                <td class="si-close">-->
        <!--                  <i class="ti-close"></i>-->
        <!--                </td>-->
        <!--              </tr>-->
        <!--              <tr>-->
        <!--                <td class="si-pic">-->
        <!--                  <img-->
        <!--                      alt=""-->
        <!--                      src="/Web/template/img/select-product-2.jpg"-->
        <!--                  />-->
        <!--                </td>-->
        <!--                <td class="si-text">-->
        <!--                  <div class="product-selected">-->
        <!--                    <p>$60.00 x 1</p>-->
        <!--                    <h6>Kabino Bedside Table</h6>-->
        <!--                  </div>-->
        <!--                </td>-->
        <!--                <td class="si-close">-->
        <!--                  <i class="ti-close"></i>-->
        <!--                </td>-->
        <!--              </tr>-->
        <!--              </tbody>-->
        <!--            </table>-->
        <!--          </div>-->
        <!--          <div class="select-total">-->
        <!--            <span>total:</span>-->
        <!--            <h5>$120.00</h5>-->
        <!--          </div>-->
        <!--          <div class="select-button">-->
        <!--            <a class="primary-btn view-card" href="#">VIEW CARD</a>-->
        <!--            <a class="primary-btn checkout-btn" href="#">CHECK OUT</a>-->
        <!--          </div>-->
        <!--        </div>-->
      </li>
      <!--      <li class="cart-price text-web-primary">{{ totalCartAmount }}</li>-->
    </ul>
  </div>
</template>

<script>
const animate = require('animate')
import {mapState} from 'vuex';

export default {
  name: "HeaderQuickCartComponent",
  data() {
    return {
      applyClass: ''
    };
  },
  computed: mapState(['cartCount']),
  // {
  //
  // cartCountMapped() {
  //   return ;
  // },
  // totalCartAmount() {
  //   if (this.$store.state.cartCount === 0)
  //     return 0;
  //
  //   let total = 0;
  //   for (const item of this.$store.state.cart) {
  //     total = total + (parseFloat(item.price) * parseInt(item.quantity));
  //   }
  //
  //   return parseFloat(total).toFixed(2);
  // }

  // },
  watch: {
    cartCount: function (oldValue, NewValue) {
      this.applyClass = "animate__animated animate__rubberBand  ";
      // let appVm = this;
      // setTimeout(function (){
      //   appVm.applyClass = " ";
      // },350)

    }
  }

}
</script>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css";
</style>