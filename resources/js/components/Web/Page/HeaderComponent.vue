<template>
  <header class="header-section">
    <div class="fixedHeader border-b shadow" :class="{ hidden: !showHiddenNavbar }">
      <div class="pb-0" style="background: #f9f9f9">
        <div class="container">
          <div class="inner-header">
            <div class="flex items-center justify-between p-0 m-0">
              <div class="w-3/12">
                <div class="logo">
                  <a href="/web">
                    <img
                      alt=""
                      :src="$asset('images/logo_hd.png')"
                      style="width: 5rem; padding-top: 2px"
                    />
                  </a>
                </div>
              </div>

              <!-- <div class=" w-8/12 md:flex">
                <header-seach-input-component></header-seach-input-component>
              </div> -->
              <HeaderQuickCartComponent></HeaderQuickCartComponent>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    <div class="border-b shadow-xs">
      <div class="container">
        <div class="flex items-center justify-between h-12">
          <div class="flex-1">
            <a
              class="flex items-center text-gray-800 login-panel"
              href="/web/sign_in"
              v-if="!$page.client_logged"
              ><i class="fa fa-user" style="font-size: 22px"></i> &nbsp;
              {{ $page.$t.profile.login }}</a
            >
            <a
              class="flex items-center text-black login-panel"
              href="/web/profile"
              v-else
              ><i class="fa fa-user" style="font-size: 22px"></i> &nbsp;
              {{ $page.client.name }}</a
            >
          </div>

          <div class="flex justify-end flex-1 text-gray-800 gap-2 items-left">
            <img
              :src="$asset('web_assets/template/img/flag-1.jpg')"
              class="float-left h-4"
            />
            <span class="login-panel">English</span>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="hidden header-top md:block">
      <div class="container ">
        <div class="ht-left">
          <div class="mail-service">
            <i class="fa fa-envelope"></i>
            {{ $page.app.msbrshop.email_address }}
          </div>
          <div class="phone-service">
            <i class="fa fa-phone"></i>
            {{ $page.app.msbrshop.phone_number }}
          </div>
        </div>
        <div class="text-center ht-right">
          <a class="login-panel" href="/web/sign_in" v-if="!$page.client_logged"
            ><i class="fa fa-user"></i>{{ $page.$t.profile.login }}</a
          >
          <a class="login-panel" href="/web/profile" v-else
            ><i class="fa fa-user"></i>{{ $page.client.name }}</a
          >

         
        </div>
      </div>
    </div> -->

    <div class="pb-3" style="background: #f9f9f9">
      <div class="container">
        <div class="inner-header">
          <div class="flex items-center justify-between p-0 m-0">
            <div class="w-3/12">
              <div class="logo">
                <a href="/web">
                  <img
                    alt=""
                    :src="$asset('images/logo_hd.png')"
                    style="width: 6rem; padding-top: 2px"
                  />
                </a>
              </div>
            </div>

            <div class="hidden w-8/12 md:flex">
              <header-seach-input-component></header-seach-input-component>
            </div>
            <HeaderQuickCartComponent></HeaderQuickCartComponent>
          </div>
        </div>
        <div
          class="flex items-center justify-between p-0 m-0 inner-header md:hidden"
        >
          <div class="w-full">
            <header-seach-input-component></header-seach-input-component>
          </div>
        </div>
      </div>
    </div>
    <div class="nav-item">
      <div class="container">
        <div class="nav-depart">
          <div class="depart-btn">
            <i class="ti-menu"></i>
            <span>{{ $page.$t.header.categories }}</span>
            <ul class="depart-hover">
              <li v-for="category in $page.main_categories" :key="category.id">
                <a :href="`/web/categories/${category.id}`">{{
                  category.locale_name
                }}</a>
              </li>
            </ul>
          </div>
        </div>
        <nav class="nav-menu mobile-menu">
          <ul style="margin-bottom: 0px">
            <slot name="navbarListItems"></slot>
            <!-- 
              <li class="active"><a href="./index.html">Home</a></li>
            <li><a href="./shop.html">Shop</a></li>
            <li>
              <a href="#">Collection</a>
              <ul class="dropdown">
                <li><a href="#">Men's</a></li>
                <li><a href="#">Women's</a></li>
                <li><a href="#">Kid's</a></li>
              </ul>
            </li>
            <li><a href="./blog.html">Blog</a></li>
            <li><a href="./contact.html">Contact</a></li>
            <li>
              <a href="#">Pages</a>
              <ul class="dropdown">
                <li><a href="./blog-details.html">Blog Details</a></li>
                <li><a href="./shopping-cart.html">Shopping Cart</a></li>
                <li><a href="./check-out.html">Checkout</a></li>
                <li><a href="./faq.html">Faq</a></li>
                <li><a href="./register.html">Register</a></li>
                <li><a href="./login.html">Login</a></li>
              </ul>
            </li> -->
          </ul>
        </nav>
      </div>
    </div>
  </header>
</template>

<script>
import HeaderSeachInputComponent from "./HeaderSeachInputComponent";
import HeaderQuickCartComponent from "./../Cart/HeaderQuickCartComponent";

export default {
  components: { HeaderSeachInputComponent, HeaderQuickCartComponent },
  data() {
    return {
      showHiddenNavbar: false,
      lastScrollPosition: 0,
    };
  },
  mounted() {
    window.addEventListener("scroll", this.onScroll);
  },
  beforeDestroy() {
    window.removeEventListener("scroll", this.onScroll);
  },
  methods: {
    onScroll() {
      // Get the current scroll position
      const currentScrollPosition =
        window.pageYOffset || document.documentElement.scrollTop;

      this.showHiddenNavbar = currentScrollPosition > 65;

        // if()
        // console.log(currentScrollPosition)
      // Because of momentum scrolling on mobiles, we shouldn't continue if it is less than zero
      // if (currentScrollPosition < 0) {
      //   return;
      // }
      // // Here we determine whether we need to show or hide the navbar
      // this.showNavbar = currentScrollPosition < this.lastScrollPosition;
      // // Set the current scroll position as the last scroll position
      // this.lastScrollPosition = currentScrollPosition;
    },
  },
};
</script>

<style>
</style>