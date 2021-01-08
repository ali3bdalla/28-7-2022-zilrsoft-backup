<template>
  <header class="header-section">
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
            <a class="flex items-center text-black login-panel " href="/web/profile" v-else
              ><i class="fa fa-user"  style="font-size: 22px"></i> &nbsp; {{ $page.client.name }}</a
            >
          </div>

          <div class="flex justify-end flex-1 text-gray-800 gap-2 items-left">
            <img :src="$asset('web_assets/template/img/flag-1.jpg')"  class="float-left h-4"/> 
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

   <div class="pb-3" style="background:#f9f9f9" >
 <div class="container">
      <div class="inner-header">
        <div class="flex items-center justify-between p-0 m-0">
          <div class="w-3/12">
            <div class="logo">
              <a href="/web">
                <img alt="" :src="$asset('images/logo_hd.png')" style="width:6rem;padding-top:2px" />
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
      </div>
    </div>
  </header>
</template>

<script>
import HeaderSeachInputComponent from "./HeaderSeachInputComponent";
import HeaderQuickCartComponent from "./../Cart/HeaderQuickCartComponent";

export default {
  components: { HeaderSeachInputComponent, HeaderQuickCartComponent },
};
</script>

<style>
</style>